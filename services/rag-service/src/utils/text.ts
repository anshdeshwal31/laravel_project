/** Convert a raw model payload (from Laravel webhook) into an embeddable text string */

export interface ModelPayload {
  model_type: "startup_profile" | "investor_profile" | "funding_opportunity";
  model_id: number;
  data: Record<string, unknown>;
}

export function extractText(payload: ModelPayload): string {
  const { model_type, data } = payload;

  if (model_type === "startup_profile") {
    return [
      `Startup: ${data.startup_name ?? "Unknown"}.`,
      `Domain: ${data.domain ?? ""}.`,
      `Industry: ${data.industry ?? ""}.`,
      `Stage: ${data.stage ?? ""}.`,
      `Location: ${data.location ?? ""}.`,
      `Funding requirement: $${data.funding_requirement ?? 0}.`,
      `Description: ${data.pitch_description ?? ""}`,
    ]
      .filter(Boolean)
      .join(" ");
  }

  if (model_type === "investor_profile") {
    const industries = Array.isArray(data.preferred_industries)
      ? data.preferred_industries.join(", ")
      : String(data.preferred_industries ?? "");
    return [
      `Investor type: ${data.investor_type ?? "Unknown"}.`,
      `Preferred industries: ${industries}.`,
      `Location preference: ${data.location_preference ?? "any"}.`,
      `Investment range: $${data.investment_min ?? 0} – $${data.investment_max ?? 0}.`,
    ]
      .filter(Boolean)
      .join(" ");
  }

  if (model_type === "funding_opportunity") {
    return [
      `Funding opportunity: ${data.title ?? "Untitled"}.`,
      `${data.description ?? ""}.`,
      `Industry: ${data.industry ?? ""}.`,
      `Stage: ${data.stage ?? ""}.`,
      `Location: ${data.location ?? ""}.`,
      `Amount: $${data.min_amount ?? 0} – $${data.max_amount ?? 0}.`,
      data.is_active === false ? "Status: closed." : "Status: active.",
    ]
      .filter(Boolean)
      .join(" ");
  }

  throw new Error(`Unknown model_type: ${model_type}`);
}

export function extractTitle(payload: ModelPayload): string {
  const { model_type, data } = payload;
  if (model_type === "startup_profile") return String(data.startup_name ?? `Startup #${payload.model_id}`);
  if (model_type === "investor_profile") return String(data.investor_type ?? `Investor #${payload.model_id}`);
  if (model_type === "funding_opportunity") return String(data.title ?? `Opportunity #${payload.model_id}`);
  return `Record #${payload.model_id}`;
}

export function buildVectorId(model_type: string, model_id: number): string {
  return `${model_type}_${model_id}`;
}
