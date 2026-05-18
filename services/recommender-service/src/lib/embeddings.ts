const MODEL = "sentence-transformers/all-MiniLM-L6-v2";

/**
 * Queries Hugging Face Serverless Inference API for free cloud-based embeddings.
 */
async function queryHF(inputs: string[]): Promise<any> {
  const apiKey = process.env.HF_API_KEY;
  if (!apiKey) {
    throw new Error("HF_API_KEY is not defined in your environment variables.");
  }

  const response = await fetch(
    `https://router.huggingface.co/hf-inference/models/${MODEL}/pipeline/feature-extraction`,
    {
      method: "POST",
      headers: {
        Authorization: `Bearer ${apiKey}`,
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ inputs }),
    }
  );

  if (!response.ok) {
    const errText = await response.text();
    throw new Error(
      `Hugging Face API returned HTTP ${response.status}: ${errText}`
    );
  }

  return await response.json();
}

export async function getEmbedding(text: string): Promise<number[]> {
  try {
    const res = await queryHF([text]);
    if (Array.isArray(res) && Array.isArray(res[0])) {
      return res[0] as number[];
    }
    if (Array.isArray(res) && typeof res[0] === "number") {
      return res as number[];
    }
    throw new Error("Unexpected response structure from Hugging Face.");
  } catch (err) {
    throw new Error(`Cloud embedding failed: ${(err as Error).message}`);
  }
}

export async function getEmbeddingBatch(texts: string[]): Promise<number[][]> {
  try {
    const res = await queryHF(texts);
    if (Array.isArray(res) && Array.isArray(res[0])) {
      return res as number[][];
    }
    throw new Error("Unexpected batch response structure from Hugging Face.");
  } catch (err) {
    throw new Error(`Cloud batch embedding failed: ${(err as Error).message}`);
  }
}

/** Cosine similarity between two equal-length vectors */
export function cosineSim(a: number[], b: number[]): number {
  let dot = 0, normA = 0, normB = 0;
  for (let i = 0; i < a.length; i++) {
    dot += a[i] * b[i];
    normA += a[i] * a[i];
    normB += b[i] * b[i];
  }
  const denom = Math.sqrt(normA) * Math.sqrt(normB);
  return denom === 0 ? 0 : dot / denom;
}
