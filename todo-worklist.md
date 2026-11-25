# Milestora — Todo Worklist

Working checklist derived from `plan.md` §11 (Implementation Phases), plus §15 (Seed & Demo Data) and §16 (Definition of Done).
Tick items as they land. Each phase must end shippable with green tests.

**Legend:** `[x]` done · `[ ]` not started / in progress · 🔸 partially done · ⛔ blocked

**Current state (2026-11-25):** Phase 1 is complete — starter kit, user timezone + profile setting, language placeholder, domain schema/models/enums/factories, system node-type seeder, `RoadmapPolicy`, and roadmap list/create/show pages are in place. Next up: Phase 2 node tree helpers and the manual builder.

---

## Phase 1 — Foundation

- [x] Laravel 13 project on Sail (`composer.json` `laravel/framework ^13.17`, `compose.yaml`)
- [x] Inertia + React 19 + TypeScript + Tailwind + shadcn/ui wired
- [x] Pest + Pint + Larastan configured (`tests/Pest.php`, `pint.json`, `phpstan.neon`)
- [x] Frontend lint/typecheck scripts (`npm run check`, `npm run types:check`)
- [x] Auth (Fortify): register, login, password reset, email verification, 2FA, passkeys
- [x] App layout: sidebar, header, breadcrumbs, theme toggle (appearance settings)
- [x] Language placeholder (EN/BN switcher stub) in layout/nav
- [x] `users.timezone` column + profile timezone setting (default `Asia/Dhaka`)
- [x] Migrations & models: `roadmaps`, `node_types`, `nodes`, `resources`, `activity_logs`
    - [x] ULID/uuid public IDs for URL-facing models, integer PKs internally
    - [x] Soft deletes on `roadmaps`, `nodes`
    - [x] Indexes per §5 (`(roadmap_id, parent_id, position)`, `(roadmap_id, path)`, `(roadmap_id, status)`, `(roadmap_id, due_date)`, unique `(roadmap_id, import_key)`)
- [x] Seeder for system node types (phase, section, topic, task, project, checkpoint, milestone, note)
- [x] Factories for all Phase 1 models
- [x] `RoadmapPolicy` (viewAny/view/create/update/delete/restore/forceDelete) with owner checks; auto-discovered by Laravel
- [ ] `NodePolicy`, `ImportPolicy` (added with their Phase 2/3 models)
- [x] Empty roadmap CRUD: list + create + show page (Inertia)
- [x] **Done when:** a user can register, create an empty roadmap, and see it listed.

## Phase 2 — Node tree & manual builder

- [ ] `Node` tree helpers: `path`, `depth`, ordering (gap ints, step 1024), move with subtree path rewrite
- [ ] Tree editor UI: add child/sibling, inline rename, drag-and-drop reorder & re-parent
- [ ] Keyboard indent/outdent; undo/redo (client history, 50 steps)
- [ ] Node form: title, description (md), type, estimate, difficulty, weight, due date, resources, checklist
- [ ] Bulk add: paste an indented outline → nodes
- [ ] Outline view with checkboxes → `CompleteNode` action
- [ ] `ProgressCalculator` with §7.1 rollup formula (leaf modes, weights, skipped exclusion, auto status)
- [ ] `RecalculateProgressJob` walking only the ancestor chain via `path`, with roadmap row lock + 1 s debounce
- [ ] Unit tests for `ProgressCalculator` + incremental recalculation
- [ ] **Done when:** a manually built 3-level roadmap shows correct rolled-up percentages after ticking items.

## Phase 3 — Markdown import

- [ ] `imports` migration/model + upload UI (drag & drop, 10 MB, MIME + extension validation)
- [ ] `ParseImportJob` with status flow (pending → parsing → needs_review → imported / failed) + status polling
- [ ] `ParsedRoadmap` / `ParsedNode` DTOs + JSON-schema validation
- [ ] `MarkdownParser` (league/commonmark) per §6.3 element rules
- [ ] `KeywordDictionary` (bilingual EN/BN, config-editable)
- [ ] `DurationExtractor` with Bangla digit normalization (০–৯ → ASCII)
- [ ] `NodeTypeClassifier` + confidence scoring (§6.7)
- [ ] Review screen: editable draft tree, confidence badges, type override, merge/split, "Import as-is"
- [ ] `CommitImport`: single transaction, path/depth/position, `import_key`, resources/checklists, dispatch recalc
- [ ] Upload security: storage outside `public/`, random filenames, 60 s timeout, 5,000 node cap, rate limit, markdown sanitization
- [ ] Golden test fixture: sample `llm-nlp-roadmap-for-laravel-developer.md` → expected tree (8 phases, weekly sections, 4+ projects, checkpoints, resource table, notes excluded from progress)
- [ ] **Done when:** the sample roadmap imports with ≥ 90% correctly classified nodes without manual edits.

## Phase 4 — Animated tree visualization

- [ ] `RoadmapGraph` with `@xyflow/react` custom node/edge components
- [ ] `dagre` layouts: vertical + horizontal
- [ ] Collapse/expand with staggered child animation (`motion`), memoized node components
- [ ] `ProgressRing` (animated `stroke-dashoffset`) + node state styles (locked/available/in_progress/done/skipped)
- [ ] Node side drawer: details, checklist, resources, log time, notes
- [ ] Radial layout, minimap, search-to-focus, phase filter, keyboard navigation
- [ ] `prefers-reduced-motion` support + ARIA labels on rings (color never sole indicator)
- [ ] Phase-complete celebration (confetti + toast); roadmap 100% summary → report link
- [ ] Deep-level collapse by default; auto-switch to Outline above ~2,000 nodes
- [ ] **Done when:** the imported sample renders smoothly and ticking a task animates the ring + ancestors instantly.

## Phase 5 — Daily plan, logging & stats

- [ ] `activity_logs` with `local_date` (user timezone), manual time log + start/stop timer
- [ ] `daily_plan_items` + `Scheduler` (greedy fill by estimate/goal, active weekdays, project/revision days)
- [ ] "Today" page: daily completion ring + minutes ring, rest-day state
- [ ] Reschedule overdue action
- [ ] `StreakCalculator` (rest days neither extend nor break; no binge penalty)
- [ ] `NightlySnapshotJob` scheduled hourly for roadmaps crossing local midnight; writes `daily_snapshots`, marks missed items
- [ ] Dashboard: today, this week, upcoming, overdue, streak, burn-up chart, calendar heatmap
- [ ] Charts read `daily_snapshots` (not live recomputation)
- [ ] Feature tests with factory-generated week of logs vs hand-calculated expectations
- [ ] **Done when:** all daily/weekly numbers match hand-calculated expectations in tests.

## Phase 6 — Scoring & reports

- [ ] `EffortCalculator` (planned vs actual, ratio, avg per active day, EP with difficulty multipliers, time by type)
- [ ] `ScoreCalculator` with §8.2 components + graceful dropping/renormalizing of missing data
- [ ] Configurable `scoring_config` weights per roadmap
- [ ] `GradeBands` (S/A/B/C/D/Needs work) + generated summary and 3 insights
- [ ] Trigger report generation on phase/roadmap completion → `completion_reports` (immutable snapshots)
- [ ] Report page: score ring, grade, component bars, effort table, planned vs actual per phase, best/worst phase, streak, timeline
- [ ] Checkpoint self-assessment (0–5) + project evidence URL capture
- [ ] Share link (read-only, revocable token) + PDF export with Noto Sans Bengali embedded + JSON export
- [ ] Scoring unit tests: no checkpoints, no due dates, late finish, 2× overtime
- [ ] **Done when:** scoring edge cases are covered by tests and a completed roadmap produces a shareable report.

## Phase 7 — DOCX, PDF, JSON import & export

- [ ] Shared document model (`heading|list_item|paragraph|table`) + one structure detector reused by all extractors
- [ ] `DocxParser` (PhpWord, `w:pStyle`, `ilvl` nesting, table rules, fake-heading fallback)
- [ ] `PdfParser` (pdftotext in Docker image) with heading/bullet heuristics + scanned-PDF detection
- [ ] `JsonParser` + Markdown/JSON export (round-trip safe)
- [ ] Re-import / "update from file" diff with progress preservation via `import_key`
- [ ] Round-trip test: export → import → identical tree
- [ ] **Done when:** sample roadmap exported to DOCX and PDF imports with acceptable accuracy (PDF ≥ 75% before review).

## Phase 8 — Versatility layer

- [ ] Custom node types (name, icon, color, counts-toward-progress)
- [ ] Custom units + tracking modes (numeric, timed, checklist) end-to-end
- [ ] Recurring habit nodes (daily/weekly) generating daily-plan entries
- [ ] Templates: publish as template, gallery, clone; seed with sample LLM/NLP roadmap + 2–3 non-learning examples
- [ ] Tags + pivot, filters
- [ ] Global search (Bangla + English) via MySQL FULLTEXT ngram or Scout + Meilisearch
- [ ] **Done when:** a non-learning roadmap (e.g. fitness with numeric tracking) works without special-case code.

## Phase 9 — Optional AI normalizer

- [ ] `LlmNormalizer` behind `IMPORT_LLM_ENABLED` flag
- [ ] Chunking (≤ ~20k tokens), strict JSON schema validation + one retry
- [ ] Only triggered for PDFs / confidence < 60 / explicit "Improve with AI"
- [ ] Cache by file hash; privacy notice; opt-in disclosure
- [ ] **Done when:** flag-off imports are unaffected and flag-on output validates against the schema.

## Phase 10 — Polish & hardening

- [ ] Bangla UI translations (`lang/bn`) + Noto Sans Bengali font loading
- [ ] Empty states, onboarding tour, keyboard shortcuts cheat-sheet
- [ ] Notifications: daily reminder, weekly summary, overdue digest
- [ ] Performance pass: N+1 audit, indexes, 30 s dashboard cache, 3,000-node recalc < 150 ms, tree page ≤ 10 queries
- [ ] Playwright E2E: import sample → tick tasks → ring changes → complete phase → view report
- [ ] Accessibility audit (every graph action reachable from Outline)
- [ ] Docker production image + GitHub Actions CI (Pint, Pest, ESLint/tsc, build)
- [ ] Backups + error tracking (Sentry/Flare), Telescope in dev, Horizon job metrics
- [ ] Nightly integrity check job for cached vs real progress drift

---

## Seed & Demo Data (§15)

- [ ] Seeder imports the sample roadmap as a public template ("LLM/NLP Engineer for Laravel Developers")
- [ ] `php artisan demo:simulate {roadmap} --days=21` generating realistic logs/plan items
- [ ] Templates: _12-Week Strength Plan_, _Read 24 Books_, _Ship a SaaS in 60 Days_, _Daily Habits_

## Definition of Done — MVP (§16)

- [ ] Create a roadmap manually **and** by uploading Markdown; the sample file imports correctly
- [ ] Animated tree renders with live per-node/phase/overall progress
- [ ] Today page shows daily completion % and minutes %; streak and heatmap work
- [ ] Completing a phase/roadmap generates a score, grade, and effort report
- [ ] All Phase 1–6 tests green in CI; no N+1 on main pages; mobile-responsive layout

---

## Work Log

Record progress here as items are ticked (newest first).

| Date       | Phase | Notes                                                                                                                                                                 |
| ---------- | ----- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| 2026-11-25 | 1     | User timezone column + profile setting (validated) and EN/BN language switcher placeholder added; profile and roadmap tests green.                                    |
| 2026-11-25 | 1     | Roadmap policy, `CreateRoadmap` action, form request, controller, routes, sidebar link, and index/create/show pages added; 6 roadmap tests, Pint, PHPStan, tsc green. |
| 2026-11-25 | 1     | Domain migrations, models, enums, factories, and `NodeTypeSeeder` added; 7 feature tests, Pint, and PHPStan level 7 green.                                            |
| 2026-11-25 | —     | Worklist created; starter kit (Phase 1 scaffolding/auth/layout) already in place.                                                                                     |
