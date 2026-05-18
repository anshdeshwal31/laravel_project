import { Pinecone, PineconeRecord } from "@pinecone-database/pinecone";

let _client: Pinecone | null = null;

function getClient(): Pinecone {
  if (!_client) {
    const apiKey = process.env.PINECONE_API_KEY;
    if (!apiKey) throw new Error("PINECONE_API_KEY is not set");
    _client = new Pinecone({ apiKey });
  }
  return _client;
}

export function getIndex() {
  const indexName = process.env.PINECONE_INDEX_NAME ?? "startup-platform";
  return getClient().index(indexName);
}

/** Ensure the Pinecone index exists (serverless, cosine, 384 dims). Safe to call on startup. */
export async function ensureIndex(): Promise<void> {
  const client = getClient();
  const indexName = process.env.PINECONE_INDEX_NAME ?? "startup-platform";

  const { indexes } = await client.listIndexes();
  const exists = indexes?.some((i) => i.name === indexName);

  if (!exists) {
    console.info(`[pinecone] Creating index "${indexName}" (384 dims, cosine)...`);
    await client.createIndex({
      name: indexName,
      dimension: 384,
      metric: "cosine",
      spec: { serverless: { cloud: "aws", region: "us-east-1" } },
    });
    // Wait for index to be ready
    let ready = false;
    for (let attempt = 0; attempt < 20; attempt++) {
      await new Promise((r) => setTimeout(r, 3000));
      const desc = await client.describeIndex(indexName);
      if (desc.status?.ready) { ready = true; break; }
    }
    if (!ready) throw new Error("Pinecone index not ready after 60s");
    console.info(`[pinecone] Index "${indexName}" is ready.`);
  } else {
    console.info(`[pinecone] Index "${indexName}" already exists.`);
  }
}

export interface DocMetadata {
  model_type: string;
  model_id: number;
  text: string;
  title: string;
}

export async function upsertVector(
  id: string,
  vector: number[],
  metadata: DocMetadata
): Promise<void> {
  const index = getIndex();
  const record: PineconeRecord = {
    id,
    values: vector,
    metadata: {
      model_type: metadata.model_type,
      model_id: metadata.model_id,
      text: metadata.text.slice(0, 2000),
      title: metadata.title.slice(0, 300),
    },
  };
  await index.upsert([record]);
}

export async function deleteVector(id: string): Promise<void> {
  const index = getIndex();
  await index.deleteOne(id);
}

export interface SearchMatch {
  id: string;
  score: number;
  model_type: string;
  model_id: number;
  text: string;
  title: string;
}

export async function searchVectors(
  queryVector: number[],
  topK: number = 5
): Promise<SearchMatch[]> {
  const index = getIndex();
  const result = await index.query({
    vector: queryVector,
    topK,
    includeMetadata: true,
  });

  return (result.matches ?? []).map((m) => ({
    id: m.id,
    score: m.score ?? 0,
    model_type: String(m.metadata?.model_type ?? ""),
    model_id: Number(m.metadata?.model_id ?? 0),
    text: String(m.metadata?.text ?? ""),
    title: String(m.metadata?.title ?? ""),
  }));
}
