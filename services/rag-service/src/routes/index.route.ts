import { Router, Request, Response } from "express";
import { z } from "zod";
import { getEmbedding } from "../lib/embeddings.js";
import { upsertVector, deleteVector } from "../lib/pinecone.js";
import { extractText, extractTitle, buildVectorId } from "../utils/text.js";

const router = Router();

const IndexEventSchema = z.object({
  model_type: z.enum(["startup_profile", "investor_profile", "funding_opportunity"]),
  event: z.enum(["created", "updated", "deleted"]),
  id: z.number().int().positive(),
  data: z.record(z.unknown()).optional(),
});

/**
 * POST /index/event
 * Receives a model lifecycle event from Laravel, embeds the document,
 * and upserts (or deletes) the vector in Pinecone.
 */
router.post("/event", async (req: Request, res: Response): Promise<void> => {
  const parsed = IndexEventSchema.safeParse(req.body);
  if (!parsed.success) {
    res.status(422).json({ error: "Validation failed", details: parsed.error.flatten() });
    return;
  }

  const { model_type, event, id, data } = parsed.data;
  const vectorId = buildVectorId(model_type, id);

  try {
    if (event === "deleted") {
      await deleteVector(vectorId);
      res.json({ ok: true, action: "deleted", id: vectorId });
      return;
    }

    if (!data || Object.keys(data).length === 0) {
      res.status(422).json({ error: "data field is required for created/updated events" });
      return;
    }

    const payload = { model_type, model_id: id, data };
    const text = extractText(payload);
    const title = extractTitle(payload);
    const vector = await getEmbedding(text);

    await upsertVector(vectorId, vector, { model_type, model_id: id, text, title });

    res.json({ ok: true, action: event === "created" ? "indexed" : "reindexed", id: vectorId });
  } catch (err) {
    console.error("[index/event] Error:", err);
    res.status(500).json({ error: (err as Error).message });
  }
});

export default router;
