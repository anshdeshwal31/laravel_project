import { Router, Request, Response } from "express";
import { z } from "zod";
import { getEmbedding, getEmbeddingBatch, cosineSim } from "../lib/embeddings.js";
import {
  scoreStage,
  scoreTicket,
  scoreGeo,
  scoreActivity,
  computeScore,
  investorText,
  startupText,
  StartupData,
  InvestorData,
  InvestorRecommendation,
} from "../lib/scoring.js";

const router = Router();

// ── Schema validation ─────────────────────────────────────────────────────────
const InvestorSchema = z.object({
  id: z.number(),
  user_id: z.number(),
  investor_type: z.string(),
  investment_min: z.number(),
  investment_max: z.number(),
  preferred_industries: z.array(z.string()).default([]),
  location_preference: z.string().nullable().optional(),
  preferred_stage: z.string().nullable().optional(),
  last_active_at: z.string().nullable().optional(),
});

const StartupSchema = z.object({
  id: z.number(),
  startup_name: z.string(),
  domain: z.string().default(""),
  industry: z.string(),
  stage: z.string(),
  funding_requirement: z.number(),
  location: z.string(),
  pitch_description: z.string().default(""),
});

const RecommendStartupBodySchema = z.object({
  startup: StartupSchema,
  investors: z.array(InvestorSchema).min(1),
  top_n: z.number().int().min(1).max(100).default(10),
});

/**
 * POST /recommend/startup/:id
 * Returns top-N investors ranked by the 5-factor composite score.
 *
 * Body: { startup: StartupData, investors: InvestorData[], top_n?: number }
 */
router.post("/startup/:id", async (req: Request, res: Response): Promise<void> => {
  const parsed = RecommendStartupBodySchema.safeParse(req.body);
  if (!parsed.success) {
    res.status(422).json({ error: "Validation failed", details: parsed.error.flatten() });
    return;
  }

  const { startup, investors, top_n } = parsed.data;

  try {
    // 1. Build texts for embedding
    const sText = startupText(startup as StartupData);
    const investorTexts = investors.map((inv) => investorText(inv as InvestorData));

    // 2. Embed startup + all investors in parallel (batch the investors)
    const [startupVec, ...investorVecs] = await Promise.all([
      getEmbedding(sText),
      ...await getEmbeddingBatch(investorTexts).then((vecs) => vecs.map((v) => Promise.resolve(v))),
    ]);

    // Actually we need to separate startup embed from investor batch
    const sVec = startupVec;

    // Re-batch properly
    const iVecs = await getEmbeddingBatch(investorTexts);

    // 3. Score each investor
    const recommendations: InvestorRecommendation[] = investors.map((inv, idx) => {
      const iVec = iVecs[idx];

      const sSemantic = Math.max(0, Math.min(1, cosineSim(sVec, iVec)));

      const sStage = scoreStage(
        startup.stage,
        (inv as InvestorData & { preferred_stage?: string | null }).preferred_stage ?? null
      );

      const sTicket = scoreTicket(
        startup.funding_requirement,
        inv.investment_min,
        inv.investment_max
      );

      const sGeo = scoreGeo(startup.location, inv.location_preference ?? null);

      const sActivity = scoreActivity(inv.last_active_at ?? null);

      const total = computeScore(sSemantic, sStage, sTicket, sGeo, sActivity);

      return {
        investor_id: inv.id,
        user_id: inv.user_id,
        score: parseFloat(total.toFixed(4)),
        breakdown: {
          semantic: parseFloat(sSemantic.toFixed(4)),
          stage: parseFloat(sStage.toFixed(4)),
          ticket: parseFloat(sTicket.toFixed(4)),
          geo: parseFloat(sGeo.toFixed(4)),
          activity: parseFloat(sActivity.toFixed(4)),
          total: parseFloat(total.toFixed(4)),
        },
      };
    });

    // 4. Sort descending by score and return top_n
    recommendations.sort((a, b) => b.score - a.score);
    const topRecommendations = recommendations.slice(0, top_n);

    res.json({
      startup_id: startup.id,
      startup_name: startup.startup_name,
      total_investors_evaluated: investors.length,
      recommendations: topRecommendations,
    });
  } catch (err) {
    console.error("[recommend/startup] Error:", err);
    res.status(500).json({ error: (err as Error).message });
  }
});

/**
 * POST /recommend/opportunity/:id
 * Returns top-N investors ranked for a specific funding opportunity.
 */
const OpportunitySchema = z.object({
  opportunity: z.object({
    id: z.number(),
    title: z.string(),
    description: z.string().default(""),
    industry: z.string(),
    stage: z.string(),
    min_amount: z.number(),
    max_amount: z.number(),
    location: z.string(),
  }),
  investors: z.array(InvestorSchema).min(1),
  top_n: z.number().int().min(1).max(100).default(10),
});

router.post("/opportunity/:id", async (req: Request, res: Response): Promise<void> => {
  const parsed = OpportunitySchema.safeParse(req.body);
  if (!parsed.success) {
    res.status(422).json({ error: "Validation failed", details: parsed.error.flatten() });
    return;
  }

  const { opportunity, investors, top_n } = parsed.data;

  try {
    // Represent opportunity as a synthetic "startup" for scoring
    const synthetic: StartupData = {
      id: opportunity.id,
      startup_name: opportunity.title,
      domain: "",
      industry: opportunity.industry,
      stage: opportunity.stage,
      funding_requirement: (opportunity.min_amount + opportunity.max_amount) / 2,
      location: opportunity.location,
      pitch_description: opportunity.description,
    };

    const sText = startupText(synthetic);
    const investorTexts = investors.map((inv) => investorText(inv as InvestorData));

    const [sVec, iVecs] = await Promise.all([
      getEmbedding(sText),
      getEmbeddingBatch(investorTexts),
    ]);

    const recommendations: InvestorRecommendation[] = investors.map((inv, idx) => {
      const iVec = iVecs[idx];
      const sSemantic = Math.max(0, Math.min(1, cosineSim(sVec, iVec)));
      const sStage = scoreStage(opportunity.stage, (inv as any).preferred_stage ?? null);
      const sTicket = scoreTicket(synthetic.funding_requirement, inv.investment_min, inv.investment_max);
      const sGeo = scoreGeo(opportunity.location, inv.location_preference ?? null);
      const sActivity = scoreActivity(inv.last_active_at ?? null);
      const total = computeScore(sSemantic, sStage, sTicket, sGeo, sActivity);

      return {
        investor_id: inv.id,
        user_id: inv.user_id,
        score: parseFloat(total.toFixed(4)),
        breakdown: {
          semantic: parseFloat(sSemantic.toFixed(4)),
          stage: parseFloat(sStage.toFixed(4)),
          ticket: parseFloat(sTicket.toFixed(4)),
          geo: parseFloat(sGeo.toFixed(4)),
          activity: parseFloat(sActivity.toFixed(4)),
          total: parseFloat(total.toFixed(4)),
        },
      };
    });

    recommendations.sort((a, b) => b.score - a.score);

    res.json({
      opportunity_id: opportunity.id,
      opportunity_title: opportunity.title,
      total_investors_evaluated: investors.length,
      recommendations: recommendations.slice(0, top_n),
    });
  } catch (err) {
    console.error("[recommend/opportunity] Error:", err);
    res.status(500).json({ error: (err as Error).message });
  }
});

export default router;
