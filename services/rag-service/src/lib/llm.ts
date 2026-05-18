import { ChatGroq } from "@langchain/groq";
import { HumanMessage, SystemMessage } from "@langchain/core/messages";
import { SearchMatch } from "./pinecone.js";

const PRIMARY_MODEL = "llama-3.3-70b-versatile";
const FALLBACK_MODEL = "llama-3.1-8b-instant";

function buildGroq(model: string): ChatGroq {
  const apiKey = process.env.GROQ_API_KEY;
  if (!apiKey) throw new Error("GROQ_API_KEY is not set");
  return new ChatGroq({ apiKey, model, temperature: 0.3, maxTokens: 1024 });
}

export interface LLMAnswer {
  answer: string;
  model_used: string;
}

export async function generateAnswer(
  query: string,
  matches: SearchMatch[]
): Promise<LLMAnswer> {
  if (matches.length === 0) {
    return {
      answer:
        "I could not find relevant information in the platform database to answer your query. Try rephrasing or asking about specific startups, investors, or funding opportunities.",
      model_used: "none",
    };
  }

  // Build context block from Pinecone matches
  const context = matches
    .map(
      (m, i) =>
        `[${i + 1}] (${m.model_type.replace(/_/g, " ")}) ${m.title}\n${m.text}`
    )
    .join("\n\n---\n\n");

  const systemPrompt = `You are an AI assistant for a startup financing platform called Larawell.
Your job is to answer user queries using ONLY the platform data provided in the context below.
Be concise, factual, and helpful. When referencing a record, cite it as [1], [2], etc.
If the context does not contain enough information to answer, say so clearly.

CONTEXT FROM PLATFORM DATABASE:
${context}`;

  const userPrompt = `User query: ${query}

Please answer based strictly on the platform data above. Include citations like [1], [2] where relevant.`;

  // Try primary model
  try {
    const llm = buildGroq(PRIMARY_MODEL);
    const response = await llm.invoke([
      new SystemMessage(systemPrompt),
      new HumanMessage(userPrompt),
    ]);
    return {
      answer: String(response.content),
      model_used: PRIMARY_MODEL,
    };
  } catch (err) {
    console.warn(
      `[llm] Primary model (${PRIMARY_MODEL}) failed:`,
      (err as Error).message
    );
  }

  // Fallback model
  try {
    const llm = buildGroq(FALLBACK_MODEL);
    const response = await llm.invoke([
      new SystemMessage(systemPrompt),
      new HumanMessage(userPrompt),
    ]);
    console.info(`[llm] Used fallback model: ${FALLBACK_MODEL}`);
    return {
      answer: String(response.content),
      model_used: FALLBACK_MODEL,
    };
  } catch (err) {
    throw new Error(
      `All LLM models failed. Last error: ${(err as Error).message}`
    );
  }
}
