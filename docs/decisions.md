**Purpose**: Record technical decisions and rationale for future reference
**Last Updated**: 2026-01-09

# Technical Decision Records - AR AIM v2 Inventory Management System

## Decision Template

Decision: [Title] - [YYYY-MM-DD]

**Context**: [What situation led to this decision?]

**Options Considered**:

1. **Option A**: [Description]
    - ✅ Pros: [Benefits]
    - ❌ Cons: [Drawbacks]
2. **Option B**: [Description]
    - ✅ Pros: [Benefits]
    - ❌ Cons: [Drawbacks]

**Decision**: [What we chose]

**Rationale**: [Why we chose this option]

**Implementation**: [How this affects the codebase]

**Review Date**: [When to revisit this decision]

---

## Recent Decisions

### Decision: Spatie Activity Log for Audit Trail - 2023-01-XX

**Context**: Need comprehensive audit trail for inventory changes to track who did what and when, for compliance and troubleshooting purposes.

**Options Considered**:

1. **Custom Logging Implementation**
    - ✅ Pros: Full control, tailored to needs, no dependencies
    - ❌ Cons: Time-consuming, need to handle all edge cases, maintenance burden
2. **Spatie Laravel Activity Log**
    - ✅ Pros: Battle-tested, flexible, comprehensive features, active maintenance, Laravel-first, easy to use
    - ❌ Cons: Some features may not be needed, learning curve
3. **Database Triggers for Logging**
    - ✅ Pros: Automatic, no code changes needed
    - ❌ Cons: Hard to maintain, difficult to debug, limited flexibility

**Decision**: Spatie Laravel Activity Log

**Rationale**:

-   Industry-standard package for Laravel audit logging
-   Supports model-level logging with automatic tracking
-   Flexible: log specific attributes, relationships, changes
-   Easy to query and display logs
-   Database-backed (easy to manage and query)
-   Widely used and well-documented
-   Supports activity log viewing in dashboard

**Implementation**:

-   Package: `spatie/laravel-activitylog: ^3`
-   Table: `activity_log` (created via migration)
-   Model: `Inventory` uses `LogsActivity` trait
-   Logged attributes: comprehensive list of inventory fields
-   Dashboard: Activity log viewing functionality
-   Configuration: `config/activitylog.php`

**Review Date**: 2026-06-01

---

### Decision: Endroid QR Code for QR Code Generation - 2022-XX-XX

**Context**: Need to generate QR codes for inventory items to enable quick scanning and identification of assets.

**Options Considered**:

1. **SimpleSoftwareIO/simple-qrcode**
    - ✅ Pros: Simple, Laravel-focused, easy to use
    - ❌ Cons: Less features, smaller community
2. **Endroid QR Code**
    - ✅ Pros: Comprehensive features, multiple formats, high customization, active maintenance
    - ❌ Cons: More complex API, larger package
3. **Google Charts API**
    - ✅ Pros: No library needed, simple HTTP request
    - ❌ Cons: Requires internet, external dependency, rate limits

**Decision**: Endroid QR Code

**Rationale**:

-   Comprehensive QR code generation library
-   Supports multiple output formats (PNG, SVG, etc.)
-   High customization options (size, error correction, etc.)
-   Active maintenance and updates
-   Works offline (no external API dependency)
-   Good documentation and examples

**Implementation**:

-   Package: `endroid/qr-code: ^4.4`
-   QR codes generated in `InventoryController::qrcode()`
-   QR codes stored in `storage/app/qrcode/` directory
-   QR code data stored in `inventories.qrcode` column
-   Public JSON endpoint: `/api/inventories/qrcode_json/{id}`
-   Print functionality available

**Review Date**: 2026-06-01

---

### Decision: Laravel Sanctum for API Authentication - 2022-XX-XX

**Context**: Need to provide RESTful API access for potential mobile app and third-party integrations while maintaining session-based authentication for web interface.

**Options Considered**:

1. **Laravel Passport (OAuth2)**
    - ✅ Pros: Full OAuth2 implementation, supports client credentials, industry standard
    - ❌ Cons: Overkill for internal API, complex setup, more overhead
2. **Laravel Sanctum (Token-based)**
    - ✅ Pros: Lightweight, simple token management, works with SPA and mobile, built for Laravel
    - ❌ Cons: No OAuth2 flows, simpler than Passport
3. **JWT (tymon/jwt-auth)**
    - ✅ Pros: Stateless, standard JWT implementation
    - ❌ Cons: Third-party package, more complex to integrate with Laravel ecosystem

**Decision**: Laravel Sanctum

**Rationale**:

-   Lightweight solution perfect for first-party API authentication
-   Supports both SPA authentication and mobile app tokens
-   Easy integration with existing session-based authentication
-   Built and maintained by Laravel team
-   Sufficient for current and foreseeable future needs
-   Simple token management (issue, revoke, expiry)

**Implementation**:

-   Installed `laravel/sanctum` package
-   API routes protected with `auth:sanctum` middleware
-   Authentication endpoint: `/api/user` (requires Sanctum token)
-   Token stored in `personal_access_tokens` table
-   Public QR code endpoint: `/api/inventories/qrcode_json/{id}` (no auth required)

**Review Date**: 2027-01-01

---

### Decision: Yajra DataTables for Server-Side Processing - 2022-XX-XX

**Context**: Need efficient data tables for large inventory datasets with pagination, sorting, and filtering without loading all data at once.

**Options Considered**:

1. **Client-Side DataTables**
    - ✅ Pros: Simple, no server-side code needed
    - ❌ Cons: Loads all data, slow for large datasets, memory issues
2. **Yajra Laravel DataTables (Server-Side)**
    - ✅ Pros: Server-side processing, efficient for large datasets, Laravel integration, comprehensive features
    - ❌ Cons: Learning curve, requires server-side implementation
3. **Custom AJAX Pagination**
    - ✅ Pros: Full control, tailored to needs
    - ❌ Cons: Time-consuming, need to implement all features

**Decision**: Yajra Laravel DataTables (Server-Side)

**Rationale**:

-   Efficient server-side processing for large datasets
-   Laravel-first package with excellent integration
-   Comprehensive features (pagination, sorting, filtering, searching)
-   Easy to implement with minimal code
-   Supports complex queries and relationships
-   Active maintenance and community support

**Implementation**:

-   Package: `yajra/laravel-datatables-oracle: ~9.0`
-   Used in: `InventoryController`, `EmployeeController`, `DashboardController`
-   Server-side processing for inventory listing
-   AJAX-based data loading
-   Custom filtering and searching

**Review Date**: 2026-06-01

---

### Decision: Maatwebsite Excel for Import/Export - 2022-XX-XX

**Context**: Need to import/export inventory data to/from Excel for bulk operations and reporting.

**Options Considered**:

1. **PhpSpreadsheet Direct**
    - ✅ Pros: Full control, no wrapper
    - ❌ Cons: More code, manual implementation
2. **Maatwebsite Laravel Excel**
    - ✅ Pros: Laravel-focused, easy to use, comprehensive features, active maintenance
    - ❌ Cons: Wrapper around PhpSpreadsheet
3. **CSV Import/Export Only**
    - ✅ Pros: Simple, no library needed
    - ❌ Cons: Limited features, no formatting

**Decision**: Maatwebsite Laravel Excel

**Rationale**:

-   Laravel-first package with excellent integration
-   Easy to use with minimal code
-   Supports both import and export
-   Comprehensive features (formatting, styling, etc.)
-   Active maintenance and updates
-   Widely used in Laravel community

**Implementation**:

-   Package: `maatwebsite/excel: ^3.1`
-   Export class: `InventoryExport` in `app/Exports/`
-   Import class: `InventoryImport` in `app/Imports/`
-   Used in: `InventoryController::export()`, `InventoryController::importProcess()`
-   Supports large datasets

**Review Date**: 2026-06-01

---

### Decision: Category-Based Access Control - 2024-12-XX

**Context**: Need fine-grained access control where users can only access inventories assigned to specific categories, in addition to role-based access.

**Options Considered**:

1. **Role-Based Only**
    - ✅ Pros: Simple, standard approach
    - ❌ Cons: Not flexible enough, can't restrict by category
2. **Category-Based Access Control**
    - ✅ Pros: Flexible, fine-grained control, category-based inventory access
    - ❌ Cons: More complex, requires many-to-many relationship
3. **Permission-Based System (Spatie)**
    - ✅ Pros: Comprehensive, flexible
    - ❌ Cons: Overkill for current needs, more complex setup

**Decision**: Category-Based Access Control

**Rationale**:

-   Provides fine-grained access control at category level
-   Users can be assigned to multiple categories
-   Inventory access restricted by user's categories
-   Many-to-many relationship: `category_user` pivot table
-   Works alongside role-based access (admin, superuser, user)
-   Simple implementation with existing category system

**Implementation**:

-   Migration: `2024_12_16_060004_create_category_user_table.php`
-   Pivot table: `category_user` (user_id, category_id)
-   Relationship: User model has many-to-many with Category
-   Access control: Middleware or controller logic checks user categories
-   Used in: Inventory access filtering

**Review Date**: 2026-06-01

---

### Decision: Custom Bootstrap Layout Over Admin Template - 2022-XX-XX

**Context**: Need professional admin interface. Considered using AdminLTE or similar template vs custom Bootstrap implementation.

**Options Considered**:

1. **AdminLTE 3**
    - ✅ Pros: Comprehensive components, professional design, pre-built widgets
    - ❌ Cons: Specific design constraints, larger footprint
2. **Custom Bootstrap Implementation**
    - ✅ Pros: Full control, lightweight, tailored design
    - ❌ Cons: More development time, need to build components
3. **CoreUI**
    - ✅ Pros: Modern design, good documentation
    - ❌ Cons: Less Laravel-focused, smaller community

**Decision**: Custom Bootstrap Implementation

**Rationale**:

-   Full control over design and layout
-   Lightweight (no unused template features)
-   Tailored to specific inventory management needs
-   Easier to maintain and customize
-   Bootstrap provides solid foundation
-   Custom layout in `resources/views/layouts/main.blade.php`

**Implementation**:

-   Custom layout: `resources/views/layouts/main.blade.php`
-   Bootstrap-based styling
-   Custom CSS in `resources/css/app.css`
-   Partial views in `resources/views/layouts/partials/`
-   Responsive design

**Review Date**: 2026-12-01

---

### Decision: Transfer Status Field for Inventory Lifecycle - 2022-05-XX

**Context**: Need to track inventory item lifecycle, especially when items are transferred between employees or locations, without deleting records.

**Options Considered**:

1. **Hard Delete on Transfer**
    - ✅ Pros: Simple, no status field needed
    - ❌ Cons: Loses history, no audit trail, can't track transfers
2. **Transfer Status Field**
    - ✅ Pros: Maintains history, audit trail, can track lifecycle
    - ❌ Cons: Additional field, need to filter in queries
3. **Separate Transfer Table**
    - ✅ Pros: Complete transfer history, separate from inventory
    - ❌ Cons: More complex, need to join tables

**Decision**: Transfer Status Field

**Rationale**:

-   Maintains complete inventory history
-   Simple implementation with single field
-   Easy to filter (e.g., exclude "Mutated" items from active inventory)
-   Audit trail preserved
-   Can track item lifecycle (Normal → Mutated)
-   Used in dashboard queries to exclude mutated items

**Implementation**:

-   Field: `inventories.transfer_status`
-   Values: "Normal", "Mutated" (and possibly others)
-   Migration: `2022_05_24_015024_add_transfer_status_column_to_inventories_table.php`
-   Used in: Dashboard queries, inventory filtering
-   Transfer functionality: `InventoryController::transfer()`, `InventoryController::transferProcess()`

**Review Date**: 2026-06-01

---

## Future Decisions to Document

-   Testing strategy (when implemented)
-   CI/CD pipeline (when implemented)
-   Production deployment strategy
-   Backup and disaster recovery plan
-   Performance optimization strategies
-   Mobile app architecture (if developed)
-   Third-party integration patterns
-   Notification system implementation
-   Asset maintenance module architecture

---

**Next Review**: Review all decisions quarterly to ensure they remain valid and update as needed.
