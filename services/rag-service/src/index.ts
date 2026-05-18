import "dotenv/config";
import express from "express";
import cors from "cors";
import { ensureIndex } from "./lib/pinecone.js";
import indexRouter from "./routes/index.route.js";
import searchRouter from "./routes/search.route.js";

const PORT = parseInt(process.env.PORT ?? "3001", 10);
const LARAVEL_URL = process.env.LARAVEL_URL ?? "http://127.0.0.1:8000";

const app = express();

// CORS — allow requests from the Laravel app
app.use(
  cors({
    origin: [LARAVEL_URL, "http://localhost:8000", "http://127.0.0.1:8000"],
    methods: ["GET", "POST", "OPTIONS"],
    allowedHeaders: ["Content-Type", "Authorization", "X-Requested-With"],
  })
);

app.use(express.json({ limit: "1mb" }));

// Health check
app.get("/health", (_req, res) => {
  res.json({ status: "ok", service: "rag-service", port: PORT });
});

// Routes
app.use("/index", indexRouter);
app.use("/search", searchRouter);

// 404 handler
app.use((_req, res) => {
  res.status(404).json({ error: "Route not found" });
});

// Global error handler
app.use(
  (
    err: Error,
    _req: express.Request,
    res: express.Response,
    _next: express.NextFunction
  ) => {
    console.error("[server] Unhandled error:", err);
    res.status(500).json({ error: err.message });
  }
);

async function main() {
  console.info("[rag-service] Checking Pinecone index...");
  await ensureIndex();

  app.listen(PORT, () => {
    console.info(`[rag-service] Running on http://localhost:${PORT}`);
    console.info(`  POST http://localhost:${PORT}/index/event  — index a model record`);
    console.info(`  POST http://localhost:${PORT}/search       — RAG query`);
  });
}

main().catch((err) => {
  console.error("[rag-service] Fatal startup error:", err);
  process.exit(1);
});
