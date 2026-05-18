/**
 * Scoring engine for startup → investor matching.
 *
 * Score = 0.35*S_semantic + 0.25*S_stage + 0.20*S_ticket + 0.10*S_geo + 0.10*S_activity
 */

// Ordered stage list for adjacency calculation
const STAGE_ORDER = [
  "idea",
  "pre-seed",
  "seed",
  "series-a",
  "series-b",
  "series-c",
  "growth",
  "late-stage",
];

export interface StartupData {
  id: number;
  startup_name: string;
  domain: string;
  industry: string;
  stage: string;
  funding_requirement: number;
  location: string;
  pitch_description: string;
}

export interface InvestorData {
  id: number;
  user_id: number;
  investor_type: string;
  investment_min: number;
  investment_max: number;
  preferred_industries: string[];
  location_preference: string | null;
  last_active_at: string | null; // ISO date string
}

export interface ScoreBreakdown {
  semantic: number;
  stage: number;
  ticket: number;
  geo: number;
  activity: number;
  total: number;
}

export interface InvestorRecommendation {
  investor_id: number;
  user_id: number;
  score: number;
  breakdown: ScoreBreakdown;
}

// ── S_semantic ──────────────────────────────────────────────────────────────
// Computed outside this file using cosine(startup_embedding, investor_embedding)
// Passed in as a parameter.

// ── S_stage ──────────────────────────────────────────────────────────────────
export function scoreStage(startupStage: string, investorPreferredStage: string | null): number {
  if (!investorPreferredStage) return 0.5; // investor has no preference — treat as adjacent

  const sIdx = STAGE_ORDER.indexOf(startupStage.toLowerCase());
  const iIdx = STAGE_ORDER.indexOf(investorPreferredStage.toLowerCase());

  if (sIdx === -1 || iIdx === -1) {
    // If stage strings are equal (even if not in our list) → exact match
    return startupStage.toLowerCase() === investorPreferredStage.toLowerCase() ? 1.0 : 0.0;
  }

  const diff = Math.abs(sIdx - iIdx);
  if (diff === 0) return 1.0;
  if (diff === 1) return 0.5;
  return 0.0;
}

// ── S_ticket ──────────────────────────────────────────────────────────────────
export function scoreTicket(ask: number, investMin: number, investMax: number): number {
  if (ask >= investMin && ask <= investMax) return 1.0;

  const distance = Math.min(Math.abs(ask - investMin), Math.abs(ask - investMax));
  const dMax = 2 * ask; // D_max = 2 * ask
  return Math.max(0, 1 - distance / dMax);
}

// ── S_geo ──────────────────────────────────────────────────────────────────
// Region mapping: country → region tag
const GEO_REGIONS: Record<string, string> = {
  // North America
  us: "north-america", usa: "north-america", "united states": "north-america",
  canada: "north-america", ca: "north-america", mexico: "north-america",
  // Europe
  uk: "europe", "united kingdom": "europe", gb: "europe",
  germany: "europe", de: "europe", france: "europe", fr: "europe",
  netherlands: "europe", nl: "europe", sweden: "europe", se: "europe",
  spain: "europe", es: "europe", italy: "europe", it: "europe",
  switzerland: "europe", ch: "europe", norway: "europe", denmark: "europe",
  // South Asia
  india: "south-asia", in: "south-asia", pakistan: "south-asia",
  bangladesh: "south-asia", "sri lanka": "south-asia",
  // Southeast Asia
  singapore: "southeast-asia", sg: "southeast-asia", indonesia: "southeast-asia",
  malaysia: "southeast-asia", thailand: "southeast-asia", vietnam: "southeast-asia",
  // East Asia
  china: "east-asia", cn: "east-asia", japan: "east-asia", jp: "east-asia",
  "south korea": "east-asia", kr: "east-asia",
  // Middle East
  uae: "middle-east", "united arab emirates": "middle-east",
  israel: "middle-east", "saudi arabia": "middle-east",
  // Africa
  nigeria: "africa", ng: "africa", kenya: "africa", "south africa": "africa", za: "africa",
  // Latam
  brazil: "latam", br: "latam", argentina: "latam", ar: "latam", colombia: "latam",
  // Oceania
  australia: "oceania", au: "oceania", "new zealand": "oceania",
};

function normalizeGeo(loc: string): string {
  return loc.trim().toLowerCase();
}

export function scoreGeo(startupLocation: string, investorPreference: string | null): number {
  if (!investorPreference || investorPreference.toLowerCase() === "global") return 0.7;

  const sLoc = normalizeGeo(startupLocation);
  const iLoc = normalizeGeo(investorPreference);

  if (sLoc === iLoc) return 1.0;

  const sRegion = GEO_REGIONS[sLoc];
  const iRegion = GEO_REGIONS[iLoc];
  if (sRegion && iRegion && sRegion === iRegion) return 0.7;

  return 0.0;
}

// ── S_activity ────────────────────────────────────────────────────────────────
export function scoreActivity(lastActiveAt: string | null): number {
  if (!lastActiveAt) return Math.exp(-0.01 * 365); // assume inactive for ~1 year
  const lastDate = new Date(lastActiveAt);
  const now = new Date();
  const diffMs = now.getTime() - lastDate.getTime();
  const days = Math.max(0, diffMs / (1000 * 60 * 60 * 24));
  return Math.exp(-0.01 * days);
}

// ── Final composite score ─────────────────────────────────────────────────────
export function computeScore(
  sSemantic: number,
  sStage: number,
  sTicket: number,
  sGeo: number,
  sActivity: number
): number {
  return (
    0.35 * sSemantic +
    0.25 * sStage +
    0.20 * sTicket +
    0.10 * sGeo +
    0.10 * sActivity
  );
}

// Build investor embedding text
export function investorText(inv: InvestorData): string {
  const industries = Array.isArray(inv.preferred_industries)
    ? inv.preferred_industries.join(", ")
    : String(inv.preferred_industries ?? "");
  return [
    `Investor type: ${inv.investor_type}.`,
    `Preferred industries: ${industries || "any"}.`,
    `Location preference: ${inv.location_preference ?? "global"}.`,
    `Investment range: $${inv.investment_min} – $${inv.investment_max}.`,
  ].join(" ");
}

// Build startup embedding text
export function startupText(s: StartupData): string {
  return [
    `${s.startup_name} is a ${s.stage} startup in ${s.industry} (domain: ${s.domain}).`,
    `Located in ${s.location}.`,
    `Seeking $${s.funding_requirement} in funding.`,
    s.pitch_description,
  ].join(" ");
}
