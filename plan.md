# Milestora — Universal Roadmap & Skill Tracker (Laravel)

 

This file is written as an implementation guide for a coding agent. Work phase by phase, tick the checkboxes, and keep every phase shippable.

---

## 0. Decisions & Assumptions

| Topic | Decision |
|---|---|
| Backend | Laravel 13, PHP 8.3+, MySQL 8, Redis (cache + queues), Horizon |
| Frontend | Inertia.js + React 19 + TypeScript, Tailwind, shadcn/ui |
| Tree visualization | `@xyflow/react` (React Flow) + `motion` (Framer Motion) + `dagre` layout |
| Charts | shadcn/ui charts (Recharts) |
| Auth | Laravel Fortify/Breeze-style starter (email + password, optional Google OAuth later) |
| Dev env | Docker / Laravel Sail on WSL2 |
| Testing | Pest (unit + feature), Playwright for 2–3 critical E2E flows |
| Languages | English UI first; **Bangla content must work everywhere** (parsing, rendering, search). Add `lang/bn` in a later phase |
| Timezone | Store UTC, display per-user timezone (default `Asia/Dhaka`) — "daily" stats depend on this |
| AI parsing | Optional. Core parsing is deterministic; LLM (Claude API) is a fallback/enhancer behind a feature flag |
| Tenancy | Single-user data ownership per account (`user_id`). Sharing = read-only public link / template clone |

> If any assumption is wrong (e.g. you want Livewire or Vue), only §11 and §12 change; the data model and parsing pipeline stay the same.

---

## 1. Product Goals

1. **Create** a roadmap manually (tree editor) or **import** from `.md`, `.pdf`, `.docx` (and `.json` for round-tripping).
2. **Parse automatically** into a normalized hierarchy: Roadmap → Phase → Section/Topic → Task, plus Projects, Checkpoints, Resources.
3. **Visualize** as an animated tree/graph (plus outline, timeline, and calendar views).
4. **Track** progress: overall %, per-phase %, today's %, weekly %, streaks, time spent.
5. **Score** on completion: marks, grade, effort calculation (planned vs actual), efficiency, consistency.
6. **Be versatile:** user-defined node types, units (minutes, pages, reps, ₹), and templates — not hard-coded to "learning".

### Non-goals (v1)
Social feed, team collaboration with roles, native mobile apps, payments. (Design so they can be added: keep policies/authorization in Policy classes.)

---

## 2. Core Concepts

| Concept | Meaning |
|---|---|
| **Roadmap** | Top-level container (e.g. "LLM/NLP Engineer – 9 months"). Has a `kind`, dates, daily goal. |
| **Node** | Any item in the tree. One table, self-referencing, with a `type`. |
| **Node types** | `phase`, `section`, `topic`, `task`, `project`, `checkpoint`, `milestone`, `note`. Users may define custom types (name, icon, color, counts-toward-progress flag). |
| **Resource** | Link / book / video / doc attached to a node. |
| **Log (Activity)** | A time or effort entry against a node ("studied 45 min on Transformer"). |
| **Daily Plan** | Nodes scheduled for a given date; basis for "daily completion %". |
| **Snapshot** | Nightly per-roadmap statistics row; powers charts without heavy recomputation. |
| **Template** | A roadmap stripped of progress, reusable/clonable. |
| **Import** | One upload + its parse result + a reviewable draft tree. |

---

## 3. Feature List (by module)

### 3.1 Roadmap management
- [ ] CRUD roadmaps; archive, duplicate, delete (soft delete).
- [ ] Roadmap `kind`: learning, fitness, project, habit, reading, career, custom (affects default node types and scoring weights).
- [ ] Start date, target end date, daily goal (minutes), active weekdays (e.g. 5 study days + 1 project + 1 rest — as in the sample roadmap).
- [ ] Cover emoji/color, tags, description (markdown).

### 3.2 Manual builder
- [ ] Tree editor: add child / sibling, rename inline, drag-and-drop reorder & re-parent, indent/outdent with keyboard.
- [ ] Per-node fields: title, description (md), type, estimate (minutes or custom unit), difficulty (1–5), weight, due date, resources, checklist.
- [ ] Bulk add: paste an indented outline and convert to nodes.
- [ ] Undo/redo (client-side history, 50 steps).

### 3.3 Import (Markdown / PDF / DOCX / JSON)
- [ ] Drag-and-drop upload, size limit (10 MB), MIME + extension validation.
- [ ] Queued parsing with live status (Pending → Parsing → Needs Review → Imported / Failed).
- [ ] **Review screen**: editable preview tree, confidence badges on guessed items, type override, merge/split nodes, "Import as-is" or "Fix & import".
- [ ] Re-import / "update from file": diff against existing roadmap and keep progress for unchanged nodes (match by stable `import_key`).
- [ ] Export roadmap to Markdown / JSON (round-trip safe).

### 3.4 Visualization
- [ ] **Tree/graph view** (default): animated node-link diagram, collapsible branches, zoom/pan, minimap.
- [ ] Node states with distinct style: `locked`, `available`, `in_progress`, `done`, `skipped`.
- [ ] Animations: branch expand/collapse, progress-ring fill, edge "flow" along completed path, confetti + pulse on phase completion, count-up numbers.
- [ ] Layout modes: vertical, horizontal, radial.
- [ ] **Outline view** (accordion list with checkboxes — fastest for daily use).
- [ ] **Timeline view** (phase bars by week; today marker; planned vs actual).
- [ ] **Calendar heatmap** (GitHub-style, by minutes or tasks per day).
- [ ] Respect `prefers-reduced-motion`.

### 3.5 Progress & daily tracking
- [ ] Overall %, per-phase %, per-node %.
- [ ] **Today's completion %** = completed planned items today ÷ planned items today (and a second ring for minutes vs daily goal).
- [ ] Weekly %, streak (current/longest), missed-day handling ("recover, don't binge" — no penalty beyond streak reset).
- [ ] Quick actions: tick a node, start/stop timer, log time manually, add a note, mark skipped.
- [ ] Auto-scheduler: spread unscheduled nodes across active days by estimate and daily goal; "reschedule overdue" button.
- [ ] Dashboard: today, this week, upcoming, overdue, streak, burn-up chart (planned vs actual).

### 3.6 Completion scoring & effort report
- [ ] Triggered when a node/phase/roadmap reaches 100%.
- [ ] Per-phase report and a final roadmap report (see §8).
- [ ] Self-assessment on checkpoints (0–5 confidence or pass/fail) and project evidence (repo/demo URL).
- [ ] Shareable report page + PDF export.

### 3.7 Versatility layer
- [ ] Custom node types and custom units (minutes, pages, reps, km, items).
- [ ] Templates gallery: "Publish as template", clone template to own account. Seed with the sample LLM/NLP roadmap.
- [ ] Tags, global search (Bangla + English), filters.
- [ ] Tracking modes per node: `checkbox` (done/not), `numeric` (target value + current), `timed` (minutes), `checklist` (sub-items).
- [ ] Recurring nodes (habit): daily/weekly recurrence generates daily-plan entries.

### 3.8 Notifications (later)
- [ ] Daily reminder (mail / web push), weekly summary, overdue digest.

---

## 4. Architecture

```
┌─────────────┐   Inertia    ┌──────────────────────────────┐
│ React 19 UI │ ───────────► │ Laravel (Controllers, Actions) │
└─────────────┘              └──────┬───────────────────────┘
                                    │
          ┌─────────────────────────┼─────────────────────────┐
          ▼                         ▼                         ▼
   MySQL (nodes, logs)     Redis (cache, queues)       Storage (uploads)
                                    │
                         Horizon workers
                  ┌────────────┼──────────────┐
                  ▼            ▼              ▼
            ParseImportJob  RecalcProgressJob  NightlySnapshotJob
```

**Principles**
- Thin controllers → **Action classes** (`app/Actions/...`) → Models/Services.
- Domain logic lives in `app/Domain/{Roadmap,Import,Progress,Scoring}`.
- Parsers are interchangeable strategies behind one interface.
- Progress is **denormalized and cached** on nodes, recomputed incrementally (never full-tree on each click).

### Folder structure

```
app/
  Actions/
    Roadmap/        CreateRoadmap, DuplicateRoadmap, ReorderNodes, ...
    Node/           CompleteNode, SkipNode, MoveNode, LogActivity, ...
    Import/         StartImport, CommitImport
  Domain/
    Import/
      Contracts/    RoadmapParser.php
      Parsers/      MarkdownParser.php, DocxParser.php, PdfParser.php, JsonParser.php, LlmNormalizer.php
      Dto/          ParsedRoadmap.php, ParsedNode.php
      Heuristics/   NodeTypeClassifier.php, DurationExtractor.php, KeywordDictionary.php
      ImportCommitter.php
    Progress/       ProgressCalculator.php, StreakCalculator.php, DailyPlanService.php, Scheduler.php
    Scoring/        ScoreCalculator.php, EffortCalculator.php, GradeBands.php
  Jobs/             ParseImportJob, RecalculateProgressJob, NightlySnapshotJob
  Models/           Roadmap, Node, NodeType, Resource, ActivityLog, DailyPlanItem, DailySnapshot, Import, Template, Tag
  Policies/         RoadmapPolicy, NodePolicy, ImportPolicy
  Http/Controllers/ RoadmapController, NodeController, ImportController, DashboardController, ReportController
resources/js/
  pages/            dashboard, roadmaps/{index,show,edit,import}, reports/show, templates/index
  components/
    tree/           RoadmapGraph.tsx, NodeCard.tsx, ProgressRing.tsx, EdgeFlow.tsx, layouts.ts
    outline/        OutlineView.tsx
    charts/         Heatmap.tsx, BurnUp.tsx, DailyRing.tsx
    editor/         TreeEditor.tsx, NodeForm.tsx
  hooks/            useRoadmapProgress.ts, useTimer.ts, useReducedMotion.ts
tests/
  Fixtures/imports/ llm-nlp-roadmap-bn.md (+ pdf/docx exports of it), simple-en.md, messy.docx
```

---

## 5. Database Schema

> Use `ulid`/`uuid` public IDs for URLs; integer PKs internally. All timestamps UTC. Soft deletes on `roadmaps`, `nodes`.

### `roadmaps`
| Column | Type | Notes |
|---|---|---|
| id, user_id | | |
| title, slug, description | | |
| kind | string | learning, fitness, project, habit, reading, career, custom |
| status | enum | draft, active, paused, completed, archived |
| start_date, target_end_date, completed_at | date/datetime | |
| daily_goal_minutes | uint | default 120 |
| active_weekdays | json | e.g. `[1,2,3,4,5,6]` |
| timezone | string | default from user |
| progress_cached | decimal(5,2) | 0–100, root rollup |
| total_estimate_minutes, total_logged_minutes | uint | cached |
| scoring_config | json | weights override (see §8) |
| source_import_id | nullable FK | |
| is_template, is_public, share_token | | |
| meta | json | cover emoji/color, etc. |

### `node_types`
`id, user_id (null = system), key, label, icon, color, counts_toward_progress (bool), default_tracking_mode`
System seeds: phase, section, topic, task, project, checkpoint, milestone, note.

### `nodes`
| Column | Type | Notes |
|---|---|---|
| id, roadmap_id | | |
| parent_id | nullable FK self | adjacency list |
| path | string(500) | materialized path e.g. `/12/48/103/` for fast subtree queries |
| depth, position | | sibling ordering (gap ints, e.g. step 1024) |
| node_type_id | FK | |
| title, description | | |
| tracking_mode | enum | checkbox, numeric, timed, checklist |
| status | enum | todo, in_progress, done, skipped |
| target_value, current_value, unit | decimal/string | numeric mode |
| estimate_minutes | uint null | |
| difficulty | tinyint 1–5 | default 3 |
| weight | decimal(8,2) | default 1; used in rollup |
| due_date, start_date, completed_at | | |
| progress_cached | decimal(5,2) | |
| logged_minutes_cached | uint | |
| score_self | tinyint null | 0–5 self-rating (checkpoints/projects) |
| evidence_url | string null | repo/demo link |
| import_key | string null | stable hash of normalized path+title for re-import matching |
| confidence | tinyint null | parser confidence 0–100 (draft review only) |
| recurrence | json null | `{freq:'daily'|'weekly', days:[...]}` |
| meta | json | extra parsed fields (week ranges, etc.) |

Indexes: `(roadmap_id, parent_id, position)`, `(roadmap_id, path)`, `(roadmap_id, status)`, `(roadmap_id, due_date)`, unique `(roadmap_id, import_key)` nullable.

### Others
- `node_checklist_items`: `id, node_id, text, done, position`
- `resources`: `id, node_id, title, url, kind (video|doc|book|course|repo|other), done`
- `activity_logs`: `id, user_id, roadmap_id, node_id null, type (time|note|complete|skip|reopen), minutes, value, note, logged_at, local_date` — **`local_date`** is the user-timezone date; all daily stats group by it.
- `daily_plan_items`: `id, roadmap_id, node_id, plan_date, planned_minutes, status (planned|done|missed|moved), completed_at, position`
- `daily_snapshots`: `id, roadmap_id, date, progress, planned_count, done_count, daily_completion_pct, minutes_logged, minutes_planned, streak` — unique `(roadmap_id, date)`.
- `imports`: `id, user_id, original_name, path, mime, size, status, parser (md|docx|pdf|json|llm), parsed_json (longText), warnings json, error, roadmap_id null, started_at, finished_at`
- `tags`, `roadmap_tag` (pivot), `completion_reports` (see §8: `roadmap_id, node_id null, scope, score, grade, breakdown json, generated_at`).

---

## 6. Import & Parsing Pipeline (the heart of the app)

### 6.1 Flow

```
Upload → validate → store → Import(status=pending)
      → ParseImportJob
           1. extract (per format) → intermediate "document model"
           2. structure detection → ParsedRoadmap (canonical DTO)
           3. heuristics enrich (types, durations, resources, checkpoints)
           4. optional LLM normalization (flag-gated, only if confidence low)
           5. validate + warnings
      → status=needs_review → user reviews/edits draft tree
      → CommitImport → insert nodes in one transaction → RecalculateProgressJob
```

### 6.2 Canonical intermediate schema (`ParsedRoadmap`)

```json
{
  "title": "Laravel Developer → LLM/NLP Engineer",
  "description": "Goal text from blockquote…",
  "meta": { "total_weeks": 36, "daily_minutes": 120, "language": "bn" },
  "nodes": [
    {
      "title": "Phase 0: Python (PHP → Python)",
      "type": "phase",
      "estimate": { "weeks": 3 },
      "confidence": 95,
      "children": [
        { "title": "Syntax, list/dict/set, comprehension…", "type": "task", "children": [] },
        { "title": "Week 1: Python basics + OOP", "type": "section" },
        { "title": "FastAPI Todo/Notes API", "type": "project" },
        { "title": "Can you build a CRUD API in Python and run it in Docker?", "type": "checkpoint" }
      ],
      "resources": [{ "title": "Kaggle Learn", "url": null }]
    }
  ],
  "warnings": ["Table in section 1 imported as resources"]
}
```

Everything downstream (review UI, committer, tests) depends only on this schema. JSON import = this schema directly.

### 6.3 Markdown parser (primary — build first)
Library: `league/commonmark` (AST access). Rules:

| Markdown element | Becomes |
|---|---|
| `# H1` (first) | Roadmap title |
| Blockquote right after H1 | Roadmap description + meta (extract durations) |
| `## H2` | **Top-level node** (`phase` if title matches phase keywords/numbering, else `section`) |
| `### H3`, `#### H4` | Child sections/topics (depth = heading level − 2) |
| Bullet / ordered list items | `task` nodes; nested lists → nested children |
| `- [ ]` / `- [x]` | `task` with status todo/done |
| Paragraph with bold label (`**শিখবেন:**`, `**Resources:**`) | Label decides how the following list is treated (tasks vs resources) |
| Tables | If header looks like resource/link table → `resources`; if 2 columns with "week/topic" → child nodes; else attach as markdown to `description` |
| Fenced code block | Attach to parent `description` (never create nodes from diagrams like the ASCII phase chart) |
| `---` | Ignored |

**Heuristic dictionary (`KeywordDictionary`) — bilingual, editable in config:**

| Detect | Keywords (EN / BN examples) | Result |
|---|---|---|
| Phase | `phase`, `stage`, `module`, `ফেজ`, `পর্ব` + numeral | type `phase` |
| Project | `project`, `capstone`, `✅ প্রজেক্ট`, `ক্যাপস্টোন` | type `project` |
| Checkpoint | `checkpoint`, `quiz`, `চেকপয়েন্ট` | type `checkpoint` |
| Resources | `resources`, `রিসোর্স`, `courses`, `কোর্স ও রিসোর্স` | child list → `resources` |
| Weekly split | `সপ্তাহ ১:`, `Week 1:` | type `section` with `week_index` in meta |
| Learn list | `শিখবেন`, `Learn`, `Topics` | list → `task` nodes |
| Routine / tracker blocks | `রুটিন`, `ট্র্যাকার`, `tracker`, `routine` | stored as `note` or roadmap meta, **not** as progress tasks |

**Duration extraction (`DurationExtractor`):** regex on titles/blockquotes — `(\d+|[০-৯]+)\s*(weeks?|সপ্তাহ|days?|দিন|months?|মাস|hours?|ঘণ্টা|min|মিনিট)`. **Normalize Bangla digits (০–৯) → ASCII first.** Convert to `estimate_minutes` using the roadmap's daily goal and active days.

**Noise handling:** the sample roadmap contains non-actionable sections (advantages table, success rules, portfolio guide, interview prep). Mark top-level sections without actionable children as `note` with `counts_toward_progress = false` and show them in review so the user can change them.

### 6.4 DOCX parser
- Library: `phpoffice/phpword` (read `w:pStyle`) — map `Heading 1–6` → heading levels, `ListParagraph`/numbering → list items with `ilvl` as nesting, tables → same table rules.
- Convert to the same *document model* (sequence of `{kind: heading|list_item|paragraph|table, level, text}`) then reuse the markdown structure detector. **One structure detector, many extractors.**
- Fallback when no heading styles: detect "fake headings" by bold + larger font + short line.

### 6.5 PDF parser (hardest — set expectations)
- Text-based PDFs: `smalot/pdfparser` (pure PHP) or `pdftotext -layout` via `spatie/pdf-to-text` (better layout; requires poppler in the Docker image).
- Heading detection by font-size clustering when available (pdftotext `-bbox-layout` / smalot font data), else by regex: numbered headings (`1.`, `1.1`, `Phase 2`), ALL-CAPS short lines, lines ending with `:`.
- Bullet detection: `•`, `-`, `–`, `▪`, `1)`, `(a)`.
- Scanned PDFs (no text layer): detect → show "scanned PDF, OCR needed"; OCR (Tesseract) is a **later** phase.
- PDF results get lower default `confidence`; always route through the review screen and, if enabled, the LLM normalizer.

### 6.6 LLM normalizer (optional, flag `IMPORT_LLM_ENABLED`)
- Input: extracted plain text (chunked, ≤ ~20k tokens per call), instruction + the canonical JSON schema; Output: strict JSON validated against the schema (retry once on invalid JSON).
- Use only when: PDF source, parser confidence < 60, or user clicks "Improve with AI".
- Run server-side with the project's API key; never send files the user marked private if feature disabled; show a clear notice that text is sent to a third-party API.
- Cache by file hash.

### 6.7 Node-type classifier & confidence
Score each node 0–100: structural certainty (heading level, list nesting) + keyword hits − ambiguity penalties. `< 60` shows an amber badge in review.

### 6.8 Review & commit
- Draft stored in `imports.parsed_json`; UI edits mutate this JSON client-side, saved on commit.
- `CommitImport`: single DB transaction; computes `path`, `depth`, `position`, `import_key = sha1(normalized_ancestor_titles + title)`; creates resources/checklists; sets estimates; dispatches progress recalculation.

### 6.9 Security for uploads
- Validate by MIME (finfo) **and** extension; max size 10 MB; max 5,000 nodes per import.
- Store outside `public/`; random filenames; delete originals after N days (configurable).
- Parse in queue with timeout (60 s) and memory cap; catch and report parser exceptions as `failed` with a friendly message.
- Sanitize all imported text; render markdown with a sanitizer (no raw HTML); never `eval` or follow links during parse.
- Rate limit uploads per user.

---

## 7. Progress, Daily Tracking & Statistics

### 7.1 Rollup formula
```
leaf progress:
  checkbox  → done=100, otherwise 0
  numeric   → min(100, current/target × 100)
  timed     → min(100, logged/estimate × 100)  (done flag forces 100)
  checklist → done_items / total_items × 100
  skipped   → excluded from numerator AND denominator

parent progress = Σ(child.weight × child.progress) / Σ(child.weight)   (over non-skipped children
                  whose type counts_toward_progress)
default weight = estimate_minutes ?? 1   (so a 10-hour topic matters more than a 10-minute one)
```
`status` auto-derives: all done → `done`; any progress > 0 → `in_progress`.

### 7.2 Incremental recalculation
- On node change → `RecalculateProgressJob(node)` walks **only the ancestor chain** (via `path`), recomputing each ancestor from its direct children's cached values. O(depth), not O(n).
- Use DB transaction + row lock on the roadmap root to avoid races; debounce rapid ticks (Redis lock 1 s).
- Full-tree recalc command: `php artisan roadmap:recalc {id}` (used after import or bulk edit).

### 7.3 Daily completion %
```
daily_completion_pct = done_planned_items(today) / planned_items(today) × 100
minutes_pct          = logged_minutes(today) / daily_goal_minutes × 100
```
- "Today" uses roadmap timezone → `local_date`.
- Unplanned work done today counts toward minutes but not toward the planned ratio (shown as "bonus").
- If nothing planned and it's a rest day → show "Rest day", never 0%.

### 7.4 Scheduler (`Scheduler` service)
- Input: unscheduled leaf nodes in tree order, active weekdays, daily goal.
- Greedy fill: assign nodes to days until `planned_minutes` reaches the goal; respect phase order and `due_date`; honor "project day" / "revision day" weekday settings.
- "Reschedule overdue": move `missed` items to the next free slots, keeping order.

### 7.5 Streaks & snapshots
- Streak: consecutive *active* days with ≥ 1 completed item or ≥ 50% of daily minutes (configurable). Rest days neither extend nor break it.
- `NightlySnapshotJob` (scheduled hourly, processes roadmaps whose local midnight just passed): marks unfinished planned items `missed`, writes `daily_snapshots`.
- Charts read `daily_snapshots` (cheap) — burn-up, heatmap, weekly bars.

### 7.6 Real-time feel
- Optimistic UI on tick; server returns updated ancestor progress map `{node_id: progress}` so rings animate immediately.
- Laravel Reverb/broadcast optional later (multi-tab sync).

---

## 8. Completion Marking & Effort Calculation

Triggered per **phase** completion and **roadmap** completion (and viewable anytime as "projected score").

### 8.1 Effort metrics
| Metric | Formula |
|---|---|
| Planned effort | Σ `estimate_minutes` of done nodes |
| Actual effort | Σ `activity_logs.minutes` |
| Effort ratio | actual ÷ planned |
| Avg per active day | actual ÷ active days |
| Effort Points (EP) | Σ(minutes × difficulty multiplier) — multipliers `1:0.8, 2:0.9, 3:1.0, 4:1.2, 5:1.4` |
| Time by type | minutes grouped by node type (theory vs practice vs projects) |
| Theory : practice ratio | compare against roadmap's intended ratio (sample: 40 min theory / 60 min coding) |

### 8.2 Score (0–100), weights configurable per roadmap (`scoring_config`)

| Component | Default weight | How calculated |
|---|---|---|
| Completion | 30 | % of non-skipped nodes done (skipped counts as 50% done-equivalent only if user gave a reason) |
| Efficiency | 20 | `100` if ratio ∈ [0.8, 1.2]; linear decay to `40` at ratio 2.0 or 0.4; minimum 0 |
| Timeliness | 15 | finished by `target_end_date` = 100; −2 points per day late, floor 0; per-node `due_date` hits counted too |
| Consistency | 20 | `active_days ÷ scheduled_active_days` × 100, small bonus for longest streak (+ up to 10, capped at 100) |
| Mastery | 15 | avg of checkpoint self-scores (0–5 → 0–100) and project evidence present (URL provided = full credit) |

```
final = Σ(component × weight) / Σ(weights)
```
Graceful degradation: if a component has no data (no checkpoints, no due dates), drop it and renormalize the remaining weights.

**Grade bands:** `S ≥ 95`, `A ≥ 85`, `B ≥ 70`, `C ≥ 55`, `D ≥ 40`, else `Needs work`. Each band has a short generated summary and **3 insights** (e.g. "You studied 22% longer than planned in Phase 2 — consider raising its estimate").

### 8.3 Report page
- Score ring + grade, component breakdown bars, effort summary table, per-phase comparison chart (planned vs actual), best/worst phase, longest streak, total hours, timeline of completion dates.
- Actions: share link (read-only), export PDF (`barryvdh/laravel-dompdf` — ensure Bangla font **Noto Sans Bengali** is embedded), export JSON.
- Reports are stored in `completion_reports` (immutable snapshot); "Regenerate" creates a new row.

---

## 9. Animated Roadmap Tree — UX Spec

**Library:** `@xyflow/react` with custom node/edge components; `dagre` for layout; `motion` for animation.

**Node card** (custom node):
- Left: type icon + color. Center: title (2-line clamp), small meta (estimate, due). Right: **progress ring** (SVG `stroke-dashoffset` animated).
- States:
  - `locked` — dimmed, lock icon (optional "strict order" setting: next phase unlocks when previous is ≥ 80%).
  - `available` — subtle pulse outline.
  - `in_progress` — animated gradient border + ring.
  - `done` — green fill, check draws itself (stroke animation), tiny scale bounce.
  - `skipped` — dashed border, muted.
- Click → side drawer (details, checklist, resources, log time, notes). Double-click → focus/zoom on subtree.

**Edges:** bezier; completed edges solid & animated "flow" dashes; pending edges faint.

**Interactions:** collapse/expand with staggered child animation (`motion` `layout` + stagger 30 ms), search-to-focus (camera animates to the node), phase filter, "Jump to today's tasks", keyboard navigation (arrow keys, space to toggle done).

**Celebrations:** phase 100% → confetti burst + toast with phase score; roadmap 100% → full-screen summary then link to report. Honor `prefers-reduced-motion` (replace with instant state change + subtle fade).

**Performance:** virtualize via collapsed-by-default deep levels (only expand to depth 2 initially), memoized node components, batch layout recompute (< 16 ms budget for ≤ 500 visible nodes). For > 2,000 nodes, auto-switch to Outline view with a notice.

**Other views:** Outline (accordion + checkboxes + keyboard), Timeline (week columns, phase bars, today line), Heatmap (365-day grid, tooltip with minutes/tasks).

**Accessibility:** every graph action also reachable from Outline view; ARIA labels on rings (`aria-valuenow`); color is never the only state indicator (icons + text).

---

## 10. Routes & API Surface (Inertia pages + JSON endpoints)

```
GET    /dashboard
GET    /roadmaps                       index
POST   /roadmaps                       create (manual)
GET    /roadmaps/{r}                   show (tree/outline/timeline/heatmap tabs)
PATCH  /roadmaps/{r}                   update settings
POST   /roadmaps/{r}/duplicate
GET    /roadmaps/{r}/export?format=md|json

POST   /roadmaps/{r}/nodes             add node
PATCH  /nodes/{n}                      update fields
POST   /nodes/{n}/move                 {parent_id, position}
POST   /nodes/{n}/complete | /skip | /reopen
POST   /nodes/{n}/log                  {minutes, note}
POST   /nodes/{n}/timer/start | /stop

GET    /roadmaps/{r}/today             today's plan + rings
POST   /roadmaps/{r}/schedule          run auto-scheduler
POST   /plan-items/{i}/move

GET    /imports/create
POST   /imports                        upload → queued
GET    /imports/{i}                    status / review draft
PATCH  /imports/{i}                    save edited draft
POST   /imports/{i}/commit             → roadmap
POST   /imports/{i}/ai-improve         optional LLM pass

GET    /roadmaps/{r}/report            latest report
POST   /roadmaps/{r}/report/regenerate
GET    /share/{token}                  public read-only view

GET    /templates ; POST /templates/{t}/clone
```
Authorization via Policies on every route; route-model binding by ULID.

---

## 11. Implementation Phases

Each phase ends with passing tests and a demo-able feature.

### Phase 1 — Foundation  
- [ ] New Laravel 13 project on Sail; Inertia + React 19 + TS + shadcn/ui; Pest; Pint; ESLint.
- [ ] Auth, layout (sidebar, theme toggle, language placeholder), timezone setting.
- [ ] Migrations & models for `roadmaps`, `node_types`, `nodes`, `resources`, `activity_logs`; seeders for system node types.
- [ ] Factories for all models; base Policy setup.
- **Done when:** user can register, create an empty roadmap, see it listed.

### Phase 2 — Node tree & manual builder  
- [ ] `Node` tree helpers (path, depth, ordering, move with subtree path rewrite).
- [ ] Tree editor UI (add/rename/reorder/re-parent, node form, keyboard indent/outdent).
- [ ] Outline view with checkboxes → `CompleteNode` action.
- [ ] `ProgressCalculator` + `RecalculateProgressJob` (ancestor-chain) with thorough unit tests.
- **Done when:** a manually built 3-level roadmap shows correct rolled-up percentages after ticking items.

### Phase 3 — Markdown import  
- [ ] `Import` model/upload UI/queue job/status polling.
- [ ] `ParsedRoadmap` DTOs + JSON schema validation.
- [ ] `MarkdownParser` + `KeywordDictionary` + `DurationExtractor` (**with Bangla digit normalization**) + `NodeTypeClassifier`.
- [ ] Review screen (editable draft tree, confidence badges) + `CommitImport`.
- [ ] **Golden test:** the sample file `llm-nlp-roadmap-for-laravel-developer.md` → expected tree (8 phases, weekly sections, 4+ projects, checkpoints, resource table, notes excluded from progress).
- **Done when:** the sample roadmap imports with ≥ 90% correctly classified nodes without manual edits.

### Phase 4 — Animated tree visualization  
- [ ] `RoadmapGraph` with custom node/edge, dagre layouts (vertical/horizontal), collapse/expand animation.
- [ ] Progress ring, node state styles, side drawer.
- [ ] Radial layout, minimap, search-to-focus, reduced-motion support.
- [ ] Phase-complete celebration.
- **Done when:** the imported sample renders smoothly, ticking a task animates ring + ancestors instantly.

### Phase 5 — Daily plan, logging & stats (≈ 5–6 days)
- [ ] `activity_logs` with `local_date`; manual time log + start/stop timer.
- [ ] `daily_plan_items`, `Scheduler`, "Today" page with daily completion ring + minutes ring.
- [ ] Streaks, `NightlySnapshotJob` (scheduler + Horizon), `daily_snapshots`.
- [ ] Dashboard: today, week, overdue, streak, burn-up chart, calendar heatmap.
- **Done when:** after a simulated week (factory-generated logs) all daily/weekly numbers match hand-calculated expectations in tests.

### Phase 6 — Scoring & reports  
- [ ] `EffortCalculator`, `ScoreCalculator` (with graceful component dropping), `GradeBands`.
- [ ] Triggers on phase/roadmap completion → `completion_reports`.
- [ ] Report page, share link, PDF export with Bangla font.
- **Done when:** scoring unit tests cover all edge cases (no checkpoints, no due dates, late finish, 2× overtime).

### Phase 7 — DOCX, PDF, JSON import & export  
- [ ] Shared *document model* + `DocxParser` (PhpWord).
- [ ] `PdfParser` (pdftotext in Docker image) with heading/bullet heuristics, scanned-PDF detection.
- [ ] `JsonParser` + Markdown/JSON export (round-trip test: export → import → identical tree).
- [ ] Re-import with progress preservation via `import_key`.
- **Done when:** the sample roadmap exported to DOCX and PDF imports with acceptable accuracy (PDF ≥ 75% before review).

### Phase 8 — Versatility layer  
- [ ] Custom node types, units, tracking modes (numeric / timed / checklist), recurring habit nodes.
- [ ] Templates (publish, gallery, clone); seed with the sample LLM/NLP roadmap and 2–3 non-learning examples (fitness 12-week plan, book reading list, side-project launch).
- [ ] Tags, global search (MySQL FULLTEXT with ngram parser for Bangla, or Laravel Scout + Meilisearch).

### Phase 9 — Optional AI normalizer  
- [ ] `LlmNormalizer` behind feature flag, chunking, schema-validated output, caching, privacy notice, "Improve with AI" button.

### Phase 10 — Polish & hardening  
- [ ] Bangla UI translations (`lang/bn`), font loading (Noto Sans Bengali), RTL not required.
- [ ] Empty states, onboarding tour, keyboard shortcuts cheat-sheet.
- [ ] Notifications (daily reminder, weekly summary).
- [ ] Performance pass (N+1 audit, indexes, cache hot dashboards 30 s), Playwright E2E, accessibility audit.
- [ ] Docker production image, CI (GitHub Actions: Pint, Pest, ESLint/tsc, build), backups, error tracking.

**Rough total:** 8–10 weeks part-time; an MVP (Phases 1–6) in ≈ 4–5 weeks.

---

## 12. Testing Strategy

| Layer | What | Tools |
|---|---|---|
| Unit | `ProgressCalculator`, `DurationExtractor` (EN + BN digits), `NodeTypeClassifier`, `Scheduler`, `ScoreCalculator`, `StreakCalculator` | Pest |
| Parser golden tests | Fixtures in `tests/Fixtures/imports/` → compare to expected JSON snapshot; add a new fixture for every parse bug | Pest + snapshot |
| Feature | Upload flow (valid/invalid mime, oversize), commit, node move path rewrite, authorization on every route | Pest HTTP tests |
| Time | Timezone/day boundary (Dhaka vs UTC), DST-free but test midnight rollover | `Carbon::setTestNow` |
| Concurrency | Rapid double-tick, parallel recalcs | Pest + DB locks |
| E2E | Import sample → tick tasks → see ring change → complete phase → view report | Playwright |
| Performance | Roadmap with 3,000 nodes: recalc < 150 ms, tree page query count ≤ 10 | Pest + query log |

---

## 13. Non-Functional Requirements

- **Performance:** tree page loads nodes in one query (+ eager-loaded resources); progress recalculation O(depth); snapshots precomputed.
- **Reliability:** all multi-row writes in transactions; imports idempotent per `imports.id`; failed jobs visible in Horizon with retry.
- **Security:** Policies everywhere, CSRF via Inertia, upload validation (§6.9), markdown sanitization, signed share tokens with revoke, rate limiting, no PII in logs.
- **Privacy:** user data export (JSON) and account deletion; LLM feature opt-in with clear disclosure.
- **i18n:** Unicode-safe everywhere (`utf8mb4_unicode_ci`), Bangla digit normalization in parsing, Noto Sans Bengali in UI and PDF.
- **Observability:** Laravel Telescope (dev), Sentry/Flare (prod), job metrics via Horizon.

---

## 14. Risks & Mitigations

| Risk | Mitigation |
|---|---|
| PDF structure extraction is unreliable | Mandatory review screen, confidence badges, optional AI pass, clear "best with Markdown/DOCX" hint |
| Bangla text breaking in PDF export/search | Embed Noto Sans Bengali; ngram FULLTEXT or Meilisearch; fixtures with Bangla in CI |
| Progress drift between cached and real values | `roadmap:recalc` command + nightly integrity check job that logs mismatches |
| Over-scoped "track everything" | Ship learning-roadmap flow first; versatility via node types/units, not special-case code |
| Large trees slow the graph | Collapsed by default, auto-switch to Outline above threshold |
| Gamified scoring feels unfair | Show breakdown + allow weight customization; drop components lacking data instead of scoring zero |

---

## 15. Seed & Demo Data

- [ ] Seeder imports `llm-nlp-roadmap-for-laravel-developer.md` as a public template ("LLM/NLP Engineer for Laravel Developers").
- [ ] Factory command `php artisan demo:simulate {roadmap} --days=21` generates realistic logs/plan items to demo charts and scoring.
- [ ] Additional templates: *12-Week Strength Plan* (numeric tracking), *Read 24 Books* (numeric: pages), *Ship a SaaS in 60 Days* (milestones), *Daily Habits* (recurring).

---

## 16. Definition of Done (MVP)

- [ ] Create a roadmap manually **and** by uploading Markdown; the sample file imports correctly.
- [ ] Animated tree renders with live per-node/phase/overall progress.
- [ ] Today page shows daily completion % and minutes %; streak and heatmap work.
- [ ] Completing a phase/roadmap generates a score, grade, and effort report.
- [ ] All Phase 1–6 tests green in CI; no N+1 on main pages; mobile-responsive layout.

---


