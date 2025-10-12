# MaazounCRM Architecture

## Overview
MaazounCRM is a Laravel 12 application with Livewire 3 and Volt used for auth views. It manages clients, services, appointments, execution workflows, tasks, invoicing, and messaging integrations (WhatsApp Business, Facebook Messenger).

## Tech Stack
- Laravel 12, PHP ^8.2
- Livewire ^3.6, Volt ^1.7 (auth pages)
- Spatie: activitylog, medialibrary, permission
- Maatwebsite/Excel, Intervention/Image
- Tailwind/Vite frontend

## Routing and Entry Points
- Web routes in `routes/web.php` with `auth, verified` middleware for app pages.
- Auth routes via Volt in `routes/auth.php`.
- Pricing API: POST `pricing.calculate` for dynamic price computation.
- Webhooks: `webhooks/whatsapp`, `webhooks/facebook` for verification + message ingest.

## High-level Modules
- Clients: CRUD, documents (Spatie Media Library), status and call results, kanban view.
- Services: CRUD, required documents, execution steps, rate policies for pricing.
- Appointments: scheduling, conflict checks, confirmation/cancel, execution lifecycle.
- Execution Workflow: per-service steps → `ExecutionProgress` + `Task` generation/updates.
- Tasks: operational execution with priorities, phases, allocations, attachments.
- Orders & Invoices: client orders (optional) and invoices with totals and status transitions.
- Integrations: WhatsApp and Facebook messaging, AI-assisted auto replies and flows.

## Domain Models (selected)
- `Client`: belongsTo `Service`, `ClientSource`; hasMany `Appointment`, `ClientOrder`, `Invoice`, `Conversation`. Rich attributes for contract workflow; media attachments under `documents`.
- `Service`: hasMany `Appointment`, `ClientOrder`, `Invoice`, `RequiredDocument`, `ServiceExecutionStep`, `ServiceRatePolicy`.
- `Appointment`: belongsTo `Client`, `Service`, `User`; hasMany `Task`, `ExecutionProgress`, `Invoice`.
- `ServiceExecutionStep`: ordered, typed steps (preparation, execution, verification, delivery); generates tasks.
- `ExecutionProgress`: tracks step status per appointment; links to generated `Task`.
- `Task`: execution unit with priority, status, phase, allocations, media.
- `ClientOrder`: optional commercial wrapper around an appointment/service.
- `Invoice`: financial document with totals, currency, statuses.
- `Area`: pricing modifiers (transportation fee, mahr percentage).
- `ServiceRatePolicy`: pricing fixed-fee ranges by mahr.
- `WhatsAppSetting` / `FacebookMessengerSetting`: integration credentials, templates, flags.

## Services
- `PricingService`: total = area.transportation_fee + mahr * area.mahr_percentage% + fixed fee by matching `ServiceRatePolicy`.
- `ServiceExecutionService`:
  - initializeExecution: creates `ExecutionProgress` per active step, generates `Task`s, sets appointment execution status.
  - startExecution/startExecutionStep/completeExecutionStep: transitions steps and related tasks; releases resources; completes appointment when all steps done.
  - autoAssignResources: basic matching for partners/suppliers; writes `ResourceAllocation`.
- `WhatsAppBusinessService` / `FacebookMessengerService`:
  - send text/messages; reminders; (basic) webhook processing; template/quick replies for FB; signature verify helpers.
- `AIConversationService`: intent heuristics in Arabic, generates responses for greetings, services, pricing, documents, location, appointment intake; persists conversation turns.

## Key Flows
- Client Creation (view `resources/views/clients/create.blade.php`)
  - Dynamic pricing via `pricing.calculate` using `service_id`, `area_id`, `mahr` and optional discounts in UI.
  - Documents upload to `Client` media collection.
- Appointment Scheduling
  - Combine date/time to `appointment_date`/`end_time`; detect overlaps; statuses: scheduled → confirmed/cancelled.
- Execution Workflow
  - On initialize, create progress entries and tasks per `ServiceExecutionStep`.
  - Start/complete steps update both `ExecutionProgress` and linked `Task`; auto-release resources; complete appointment when all steps done.
- Invoicing
  - Create/edit with calculated totals; mark sent/paid/cancelled; can generate from an appointment.
- Integrations
  - WhatsApp: send reminders, daily admin notifications, simple webhook processing that loops messages through AI and replies.
  - Facebook: similar send/receive; placeholders for mapping client page IDs.

## UI (Livewire/Blade)
- Admin pages use Blade under `resources/views/**`.
- Livewire components:
  - `ServiceRatePoliciesManager`: interactive CRUD for mahr-based fixed fee rows.
  - `Clients`, `AreasIndex`, `Appointments`, `Tasks`, `Products`, `Partners`, `Suppliers`, `Reports`, `Dashboard` (thin wrappers around views).
- Auth pages use Volt mounts per `VoltServiceProvider`.

## Configuration & Setup
- Env: database (SQLite present), queues optional, external tokens for integrations in `whatsapp_settings`/`facebook_messenger_settings` tables.
- Composer scripts include `post-create-project-cmd` that runs key:generate, creates SQLite, and migrates.
- Spatie packages configured via default providers; media stored under `storage/app/public` and served via custom file routes.

## Security & Permissions
- Auth with Breeze/Volt; `auth, verified` guard around app routes.
- Roles via `spatie/permission` using `User::role` field and `HasRoles` trait.

## Testing
- Feature tests include pricing (`tests/Feature/PricingTest.php`) and rate policy UI (`tests/Feature/ServiceRatePoliciesUiTest.php`).

## Notes & Gaps
- Some TODOs: sending real emails/WhatsApp in `InvoiceController@send`, PDF generation for invoices, mapping FB page IDs to clients.
- Webhook processing is basic; production should validate payloads and idempotency.

