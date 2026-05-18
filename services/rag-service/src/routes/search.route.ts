import { Router, Request, Response } from "express";
import { z } from "zod";
import { getEmbedding } from "../lib/embeddings.js";
import { searchVectors } from "../lib/pinecone.js";
import { generateAnswer } from "../lib/llm.js";

const router = Router();

const SearchSchema = z.object({
  query: z.string().min(3).max(500),
  top_k: z.number().int().min(1).max(20).optional().default(6),
});

/**
 * POST /search
 * Accepts a natural-language query, retrieves relevant documents from Pinecone,
 * generates a grounded LLM answer, and returns it with citations.
 */
router.post("/", async (req: Request, res: Response): Promise<void> => {
  const parsed = SearchSchema.safeParse(req.body);
  if (!parsed.success) {
    res.status(422).json({ error: "Validation failed", details: parsed.error.flatten() });
    return;
  }

  const { query, top_k } = parsed.data;

  try {
    // 1. Embed the query
    const queryVector = await getEmbedding(query);

    // 2. Retrieve top-k most similar documents from Pinecone
    const matches = await searchVectors(queryVector, top_k);

    // 3. Generate a grounded LLM answer with citations
    const { answer, model_used } = await generateAnswer(query, matches);

    // 4. Return answer + structured citations
    res.json({
      query,
      answer,
      model_used,
      citations: matches.map((m, i) => ({
        ref: i + 1,
        id: m.id,
        model_type: m.model_type,
        model_id: m.model_id,
        title: m.title,
        excerpt: m.text.slice(0, 200),
        relevance_score: parseFloat(m.score.toFixed(4)),
      })),
    });
  } catch (err) {
    console.error("[search] Error:", err);
    res.status(500).json({ error: (err as Error).message });
  }
});

export default router;
