**Purpose**: AI's persistent knowledge base for project context and learnings - AR AIM v2 Inventory Management System
**Last Updated**: 2026-11-17

### [ENV] Shared Apache env collision with Hero (2026-11-17) ✅ COMPLETE

**Challenge**: Employee Hero search returned 502. Hero answered 401 (`Invalid or missing API key.`), then after the key rename answered 500 because it queried `arka_assets.administrations`. Laragon `mpm_winnt` + `mod_php` keeps `putenv` in one process, and Laravel's immutable dotenv will not replace a variable the other app already set.

**Solution**: Both apps load `.env` through `App\Bootstrap\LoadEnvironmentVariables` (mutable repository, registered on the HTTP kernel) so each request writes that app's file back over the process. Hero's API key is `HERO_API_KEY`. ARAIM's inbound key is `ARAIM_API_KEY`. `AppServiceProvider::reclaimOwnEnvironment()` still re-applies ARAIM's DB and API config after boot.

**Key Learning**: A key that works from CLI curl can still fail inside Apache until the other app reloads its own `.env`. Distinct variable names are not enough when `DB_*` is shared.

**Files**: `app/Bootstrap/LoadEnvironmentVariables.php`, `app/Http/Kernel.php`, Hero `app/Bootstrap/LoadEnvironmentVariables.php`, Hero `config/services.php`

## Memory Maintenance Guidelines

### Structure Standards

-   Entry Format: ### [ID] [Title (YYYY-MM-DD)] ✅ STATUS
-   Required Fields: Date, Challenge/Decision, Solution, Key Learning
-   Length Limit: 3-6 lines per entry (excluding sub-bullets)
-   Status Indicators: ✅ COMPLETE, ⚠️ PARTIAL, ❌ BLOCKED

### Content Guidelines

-   Focus: Architecture decisions, critical bugs, security fixes, major technical challenges
-   Exclude: Routine features, minor bug fixes, documentation updates
-   Learning: Each entry must include actionable learning or decision rationale
-   Redundancy: Remove duplicate information, consolidate similar issues

### File Management

-   Archive Trigger: When file exceeds 500 lines or 6 months old
-   Archive Format: `memory-YYYY-MM.md` (e.g., `memory-2026-01.md`)
-   New File: Start fresh with current date and carry forward only active decisions

---

## Project Memory Entries - AR AIM v2 Inventory Management System

### [016] Apache holdings calls lost the Sanctum bearer (2026-11-16) ✅ COMPLETE

**Challenge**: `GET /api/hero/employees/{id}/holdings` returned 401 from `http://localhost/araim.v2` even with a valid Sanctum token. PHPUnit, which does not use Apache, succeeded.

**Solution**: The site root `.htaccess` sends requests to `server.php` and never runs `public/.htaccess`. `server.php` now copies `Authorization` from `apache_request_headers()` or `REDIRECT_HTTP_AUTHORIZATION`.

**Key Learning**: A bearer check that passes in `artisan test` can still fail on Laragon until `server.php` sees the header. Hero's list stays empty or errors until that header arrives.

**Files**: `.htaccess`, `server.php`

---

### [015] Hero employee bind and holdings API (2026-11-16) ✅ COMPLETE

**Challenge**: Holdings had to follow Hero's person id, while Hero's administration search JSON often omits `employee.id`.

**Solution**: `employees.hero_employee_id` is set from the employee form or `employees:bind-hero` (exact active NIK, one person, no overwrite, NIK unchanged). `HeroEmployeeClient` uses `employee.id` when present, otherwise resolves it from `/employees/list` and `/employees/{id}`. Holdings are `GET /api/hero/employees/{uuid}/holdings`.

**Key Learning**: Current holding is `transfer_status = Available` and `is_active = 1`. History is every row with the same `inventory_no`. A partial transfer leaves the old row `Available`, so one number can have two current holders.

**Files**: `app/Services/HeroEmployeeClient.php`, `app/Http/Controllers/Api/HeroHoldingController.php`, `app/Console/Commands/BindHeroEmployees.php`, `app/Http/Controllers/EmployeeController.php`

---

### [014] Inventory email notify create/transfer + admin debug (2026-11-11) ✅ COMPLETE

**Challenge**: Notify assignee on inventory create and transfer with fixed CC; need browser preview and custom test send for admins without mixing into production TO rules.

**Solution**: `InventoryMailer` resolves TO from `employees.email`, CC from `INVENTORY_MAIL_CC`; hooks after `store`/`transferProcess` with try/catch (never fail save). Admin `InventoryEmailDebugController` at `/inventories/email-debug` previews same Blade views and sends test to custom TO/CC. Templates use table/inline CSS for Outlook/Thunderbird.

**Key Learning**: Keep production recipient rules separate from debug custom addresses. Register `inventories/email-debug*` routes before `inventories/{inventory}` so Laravel does not treat `email-debug` as an ID.

**Files**: `app/Services/InventoryMailer.php`, `app/Mail/InventoryCreatedMail.php`, `app/Mail/InventoryTransferredMail.php`, `app/Http/Controllers/InventoryEmailDebugController.php`, `config/mail.php`, `routes/web.php`

---

### [001] Documentation Synchronization Issue (2026-01-09) ✅ COMPLETE

**Challenge**: Project documentation files (architecture.md, todo.md, backlog.md, decisions.md, MEMORY.md) contained information about an HR Management System (ARKA HERO) while the actual codebase is an Inventory Management System (AR AIM v2). Complete mismatch between documentation and reality.

**Solution**: Completely rewrote all documentation files to reflect the actual inventory management system. Updated architecture.md with correct modules (Inventory, Asset, Employee, Project, Department, Location, Brand, Category, Component, Position, BAPB, BAST, Dashboard, Tracking, User Management). Updated todo.md, backlog.md, decisions.md, and MEMORY.md with relevant content for inventory system.

**Key Learning**: Always verify actual codebase state before updating documentation. Documentation should reflect CURRENT reality, not intended or past state. When documentation is completely out of sync, complete rewrite is more efficient than incremental updates. Cross-reference codebase (routes, controllers, models, migrations) to understand actual system.

**Files**: `docs/architecture.md`, `docs/todo.md`, `docs/backlog.md`, `docs/decisions.md`, `MEMORY.md`

---

### [002] Spatie Activity Log Implementation (2023-01-XX) ✅ COMPLETE

**Challenge**: Need comprehensive audit trail for inventory changes to track who did what and when, for compliance and troubleshooting purposes.

**Solution**: Implemented Spatie Laravel Activity Log package. Added `LogsActivity` trait to `Inventory` model. Configured comprehensive attribute logging including relationships (employee.fullname, asset.asset_name, project.project_code, etc.). Created activity log viewing in dashboard. Migration created `activity_log` table.

**Key Learning**: Spatie Activity Log provides excellent audit trail capabilities with minimal code. Log relationship attributes for better readability (e.g., `employee.fullname` instead of `employee_id`). Configure `logAttributes` carefully to include all relevant fields. Use `submitEmptyLogs = false` to avoid cluttering logs with unchanged records. Activity logs are essential for compliance and troubleshooting.

**Files**: `app/Models/Inventory.php`, `config/activitylog.php`, `database/migrations/2023_01_31_030647_create_activity_log_table.php`, `DashboardController::logs()`

---

### [003] QR Code Generation with Endroid QR Code (2022-XX-XX) ✅ COMPLETE

**Challenge**: Need to generate QR codes for inventory items to enable quick scanning and identification of assets in the field.

**Solution**: Implemented Endroid QR Code library for QR code generation. QR codes generated in `InventoryController::qrcode()` method. QR codes stored in `storage/app/qrcode/` directory. QR code path stored in `inventories.qrcode` column. Public JSON endpoint created: `/api/inventories/qrcode_json/{id}` for scanning. Print functionality available for physical asset tagging.

**Key Learning**: Endroid QR Code provides comprehensive QR code generation with high customization. Store QR code files in dedicated storage directory for organization. Provide public JSON endpoint for mobile scanning (no authentication required for read-only data). QR codes enable efficient asset tracking and identification. Consider error correction level for damaged QR codes in field use.

**Files**: `app/Http/Controllers/InventoryController.php`, `app/Http/Controllers/Api/InventoryApiController.php`, `composer.json` (endroid/qr-code)

---

### [004] Category-Based Access Control System (2024-12-XX) ✅ COMPLETE

**Challenge**: Need fine-grained access control where users can only access inventories assigned to specific categories, in addition to role-based access (admin, superuser, user).

**Solution**: Implemented many-to-many relationship between users and categories via `category_user` pivot table. Users can be assigned to multiple categories. Inventory access filtered by user's assigned categories. Works alongside role-based access control. Migration: `2024_12_16_060004_create_category_user_table.php`.

**Key Learning**: Category-based access provides fine-grained control beyond roles. Many-to-many relationship enables users to have multiple category assignments. Filter inventory queries by user's categories in controller or middleware. Combine with role-based access for comprehensive security (role determines what actions, category determines what data). Document access control logic clearly for future developers.

**Files**: `database/migrations/2024_12_16_060004_create_category_user_table.php`, `app/Models/User.php`, `app/Models/Category.php`

---

### [005] Transfer Status Field for Inventory Lifecycle (2022-05-XX) ✅ COMPLETE

**Challenge**: Need to track inventory item lifecycle, especially when items are transferred between employees or locations, without losing history or audit trail.

**Solution**: Added `transfer_status` field to `inventories` table. Values: "Normal" (standard inventory), "Mutated" (transferred/moved). Transfer functionality in `InventoryController::transfer()` and `transferProcess()`. Dashboard queries filter out "Mutated" items from active inventory counts. Migration: `2022_05_24_015024_add_transfer_status_column_to_inventories_table.php`.

**Key Learning**: Status fields enable lifecycle tracking without hard deletes. Filter queries by status to show only relevant items (e.g., exclude mutated items from active inventory). Maintains complete audit trail and history. Simple implementation with single field. Consider status values carefully - "Mutated" clearly indicates transferred state. Document status values and their meanings.

**Files**: `database/migrations/2022_05_24_015024_add_transfer_status_column_to_inventories_table.php`, `app/Http/Controllers/InventoryController.php`, `app/Http/Controllers/DashboardController.php`

---

### [006] Yajra DataTables Server-Side Processing (2022-XX-XX) ✅ COMPLETE

**Challenge**: Need efficient data tables for large inventory datasets with pagination, sorting, and filtering without loading all data at once (performance issue with client-side processing).

**Solution**: Implemented Yajra Laravel DataTables with server-side processing. Used in `InventoryController`, `EmployeeController`, `DashboardController`. AJAX-based data loading. Custom filtering and searching. Efficient for large datasets.

**Key Learning**: Server-side processing is essential for large datasets. Yajra DataTables provides excellent Laravel integration with minimal code. Use AJAX for dynamic data loading. Implement custom filters for complex queries. Test with realistic data volumes to identify performance issues early. Document DataTables implementation for consistency across modules.

**Files**: `app/Http/Controllers/InventoryController.php`, `app/Http/Controllers/EmployeeController.php`, `composer.json` (yajra/laravel-datatables-oracle)

---

### [007] Maatwebsite Excel for Import/Export (2022-XX-XX) ✅ COMPLETE

**Challenge**: Need to import/export inventory data to/from Excel for bulk operations and reporting.

**Solution**: Implemented Maatwebsite Laravel Excel package. Created `InventoryExport` class in `app/Exports/`. Created `InventoryImport` class in `app/Imports/`. Used in `InventoryController::export()` and `importProcess()`. Supports large datasets.

**Key Learning**: Maatwebsite Excel provides excellent Laravel integration for Excel operations. Separate export/import classes for organization. Test with large datasets to identify performance issues. Provide import templates with examples for users. Validate imported data thoroughly before saving. Handle errors gracefully with clear error messages.

**Files**: `app/Exports/InventoryExport.php`, `app/Imports/InventoryImport.php`, `app/Http/Controllers/InventoryController.php`

---

### [008] BAPB/BAST Document Management (2022-XX-XX) ✅ COMPLETE

**Challenge**: Need to manage goods receipt documents (BAPB - Berita Acara Penerimaan Barang) and handover documents (BAST - Berita Acara Serah Terima) with multiple inventory items per document.

**Solution**: Created separate tables for `bapbs` and `basts`. Each document can contain multiple inventory items. Document number auto-generation. Print functionality available. Item management (add/delete items) in document. Controllers: `BapbController`, `BastController`.

**Key Learning**: Document management requires separate tables for document headers and items. Auto-generate document numbers for consistency. Support multiple items per document for real-world use. Print functionality essential for physical documents. Item management (add/delete) should be intuitive. Maintain relationship between documents and inventory items.

**Files**: `app/Http/Controllers/BapbController.php`, `app/Http/Controllers/BastController.php`, `database/migrations/2022_05_09_011554_create_bapbs_table.php`, `database/migrations/2022_05_09_011459_create_basts_table.php`

---

### [009] Multiple Image Attachments per Inventory (2022-11-XX) ✅ COMPLETE

**Challenge**: Need to attach multiple images to inventory items for documentation and identification purposes.

**Solution**: Created `images` table with relationship to `inventories`. Multiple images per inventory item. Image upload functionality in `InventoryController::addImages()`. Image deletion functionality. Images stored in `storage/app/images/` or similar. Migration: `2022_11_17_015357_create_images_table.php`.

**Key Learning**: Separate images table enables multiple images per inventory. Store images in dedicated storage directory. Provide image upload and deletion functionality. Consider image optimization and storage limits. Display images in inventory detail view. Maintain image-inventory relationship for proper cleanup.

**Files**: `app/Models/Image.php`, `app/Http/Controllers/InventoryController.php`, `database/migrations/2022_11_17_015357_create_images_table.php`

---

### [010] Custom Role-Based Access Control (2022-XX-XX) ✅ COMPLETE

**Challenge**: Need role-based access control (admin, superuser, user) with different permission levels for different resources.

**Solution**: Implemented custom `check_role` middleware. Roles: admin (full access), superuser (most resources except users/components), user (limited access). Middleware applied to routes. Category-based access works alongside role-based access.

**Key Learning**: Custom middleware provides flexible role-based access control. Document role permissions clearly. Combine with category-based access for fine-grained control. Apply middleware at route level for clarity. Test role-based access thoroughly. Consider using Spatie Laravel Permission package if needs become more complex.

**Files**: `app/Http/Middleware/CheckRole.php` (if exists), route middleware application in `routes/web.php`

---

## Active Technical Debt

### TD-001: Limited Test Coverage ⚠️ PARTIAL

**Issue**: Current PHPUnit test coverage is minimal. Critical workflows (inventory CRUD, transfer, QR code generation, BAPB/BAST creation) are untested.

**Impact**: High risk of regressions during refactoring. Difficult to validate business logic. Slower development due to manual testing.

**Recommendation**: Prioritize test coverage for critical paths: inventory lifecycle end-to-end, QR code generation, transfer workflow, BAPB/BAST creation, role-based access control. Target: 80% coverage on business logic. Effort: 3-4 weeks.

**Status**: Documented in backlog as high priority

---

### TD-002: Performance Optimization Needed ⚠️ PARTIAL

**Issue**: Some queries may show N+1 patterns. Dashboard loads may be slow with large datasets. No query result caching. Excel exports may timeout with very large datasets.

**Impact**: Degraded user experience with growing data. Potential timeout errors. Server resource inefficiency.

**Recommendation**: Add composite indexes on frequently queried columns. Implement eager loading for relationships. Add Redis caching for dashboard statistics. Queue large Excel exports. Optimize DataTables queries. Effort: 1-2 weeks.

**Status**: Documented in backlog

---

### TD-003: Missing Production Infrastructure ❌ BLOCKED

**Issue**: Application runs only on local Laragon development environment. No production deployment plan, no CI/CD pipeline, no monitoring/alerting, no automated backups.

**Impact**: Cannot deploy to production safely. No disaster recovery plan. Manual deployment errors likely.

**Recommendation**: Set up production server with proper configuration. Implement automated daily database backups. Configure monitoring/alerting. Setup CI/CD pipeline with GitHub Actions. Effort: 2 weeks.

**Status**: Critical blocker for production launch

---

### TD-004: API Endpoints Limited ⚠️ PARTIAL

**Issue**: Only one public API endpoint exists (`/api/inventories/qrcode_json/{id}`). No comprehensive API for mobile app or third-party integrations.

**Impact**: Limited integration capabilities. Cannot build mobile app easily. No API for external systems.

**Recommendation**: Expand API endpoints for all major resources (inventories, employees, assets, etc.). Implement consistent API response format. Add API versioning. Document API endpoints. Effort: 2-3 weeks.

**Status**: Documented in backlog

---

## Lessons Learned

### Architecture Lessons

1. **Status Fields for Lifecycle Tracking**: Use status fields (e.g., `transfer_status`, `inventory_status`) to track item lifecycle without hard deletes. Maintains history and audit trail.

2. **Many-to-Many for Flexible Access**: Many-to-many relationships (user-category) enable flexible access control beyond simple roles.

3. **Separate Tables for Documents**: Document management (BAPB/BAST) requires separate tables for document headers and items. Enables multiple items per document.

4. **Activity Logging is Essential**: Comprehensive activity logging (Spatie Activity Log) provides audit trail for compliance and troubleshooting.

5. **Server-Side Processing for Large Datasets**: Use server-side processing (Yajra DataTables) for large datasets to avoid performance issues.

### Development Lessons

1. **Verify Before Documenting**: Always verify actual codebase state before updating documentation. Documentation should reflect CURRENT reality.

2. **Package Selection**: Choose well-maintained Laravel-first packages (Spatie, Yajra, Maatwebsite) for better integration and support.

3. **Storage Organization**: Organize file storage in dedicated directories (qrcode/, images/) for better management.

4. **Public Endpoints for Scanning**: Provide public JSON endpoints for QR code scanning (read-only, no authentication required).

5. **Test with Realistic Data**: Test features (Excel exports, DataTables, queries) with realistic data volumes to identify performance issues early.

### Business Logic Lessons

1. **Document Status Values**: Document all status field values and their meanings clearly. Used in queries and business logic.

2. **Transfer Workflow**: Inventory transfer requires status tracking to maintain history. Don't hard delete on transfer.

3. **Multiple Images**: Support multiple images per inventory item for better documentation and identification.

4. **Document Management**: BAPB/BAST documents need item management (add/delete) for real-world use.

5. **Category-Based Access**: Fine-grained access control via categories enables flexible permission management.

---

## Quick Reference Patterns

### Controller Patterns

-   `index()` - List with DataTables
-   `create()` - Show form
-   `store(Request $request)` - Save with validation
-   `show($id)` - Display single record
-   `edit($id)` - Show edit form
-   `update(Request $request, $id)` - Update with validation
-   `destroy($id)` - Soft delete or status change
-   Return with redirect and session flash messages

### QR Code Generation Pattern

-   Generate QR code using Endroid QR Code
-   Store QR code file in `storage/app/qrcode/`
-   Update inventory record with QR code path
-   Provide public JSON endpoint for scanning
-   Print QR code for physical asset tagging

### Import/Export Pattern

-   Export classes in `app/Exports/`
-   Import classes in `app/Imports/`
-   Use Maatwebsite Excel package
-   Validate imported data thoroughly
-   Handle errors gracefully

### Activity Logging Pattern

-   Use Spatie Activity Log `LogsActivity` trait
-   Configure `logAttributes` with relevant fields
-   Include relationship attributes for readability
-   Set `submitEmptyLogs = false`
-   View logs in dashboard

---

### [012] Local PHP 8.1 starter script (2026-11-05) ✅ COMPLETE

**Challenge**: Need to run araim.v2 (Laravel 8) on a compatible PHP without switching Laragon global PHP used by other apps; PHP 8.5 emits many deprecations on this stack.

**Solution**: Added `start-php81.bat` modeled on `it-wo/start-php74.bat`, pinning `C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe` and serving via `artisan serve` on `127.0.0.1:8078`.

**Key Learning**: For multi-app Laragon setups, prefer project-local PHP binaries over changing the global Laragon PHP. Match sibling project bat patterns; pick the highest PHP the framework actually supports cleanly (8.1 for Laravel 8 here, not 8.5).

**Files**: `start-php81.bat`, `docs/architecture.md`, `docs/decisions.md`

---

### [011] JavaScript Variable Scope in DataTables Filter (2026-01-13) ✅ COMPLETE

**Challenge**: Filter functionality for "IT Equipment Without BAST" DataTable was not working. JavaScript error: `Uncaught TypeError: bastTable.draw is not a function`. Event handlers (Select2 dropdowns, text inputs, date inputs) could not access the `bastTable` DataTable instance due to incorrect variable scope.

**Solution**: Refactored JavaScript using IIFE (Immediately Invoked Function Expression) to create proper closure scope. Changed from `var bastTable` inside conditional block to `const bastTable` inside IIFE. Created helper function `redrawTable()` for cleaner code. Consolidated Select2 selectors into array for DRY code. Removed unnecessary `setTimeout` and conditional checks that complicated the code.

**Key Learning**: JavaScript variable scope is critical for event handlers that fire after initialization. Use IIFE to create proper closure when variables need to be accessible to async/deferred event handlers (Select2, click events, keyup). Prefer `const` over `var` for DataTable instances. Create helper functions to avoid repetition and improve readability. Simpler code is often more reliable - avoid unnecessary `setTimeout` and defensive checks. Test filter functionality immediately after DataTable initialization.

**Files**: `resources/views/dashboard/dashboard.blade.php` (lines 428-527)

---

**Last Memory Review**: 2026-01-13
**Next Memory Archive**: When file exceeds 500 lines (currently ~360 lines)
**Archive To**: `memory-2026-01.md`
