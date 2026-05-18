import "dotenv/config";
import express from "express";
import cors from "cors";
import recommendRouter from "./routes/recommend.route.js";

const PORT = parseInt(process.env.PORT ?? "3002", 10);
const LARAVEL_URL = process.env.LARAVEL_URL ?? "http://127.0.0.1:8000";

const app = express();

app.use(
  cors({
    origin: [LARAVEL_URL, "http://localhost:8000", "http://127.0.0.1:8000"],
    methods: ["GET", "POST", "OPTIONS"],
    allowedHeaders: ["Content-Type", "Authorization", "X-Requested-With"],
  })
);

app.use(express.json({ limit: "2mb" }));

// Health check
app.get("/health", (_req, res) => {
  res.json({ status: "ok", service: "recommender-service", port: PORT });
});

app.use("/recommend", recommendRouter);

// 404
app.use((_req, res) => {
  res.status(404).json({ error: "Route not found" });
});

// Global error handler
app.use((err: Error, _req: express.Request, res: express.Response, _next: express.NextFunction) => {
  console.error("[server] Unhandled error:", err);
  res.status(500).json({ error: err.message });
});

app.listen(PORT, () => {
  console.info(`[recommender-service] Running on http://localhost:${PORT}`);
  console.info(`  POST http://localhost:${PORT}/recommend/startup/:id`);
  console.info(`  POST http://localhost:${PORT}/recommend/opportunity/:id`);
});
