# Technical Test Programmer — Backend

Backend REST API for a **Document Eligibility Application Management System** built with Laravel and PostgreSQL.

The system manages the process of document eligibility applications submitted by applicants and reviewed by document assessors. The workflow covers application creation, document upload, submission, assessment, revision, approval, rejection, and application history.

The backend is designed with consideration for **clean code, security, database performance, scalability, API consistency, and maintainability**.

---

## 1. Project Overview

This project is a backend REST API for a government document-management application.

The application supports two main user roles:

### Pemohon Dokumen

Applicants who submit document eligibility applications.

Capabilities:

* Login
* View applicant dashboard
* Create document application/project
* Upload supporting documents
* Edit applications while they are still Draft
* Submit applications for assessment
* View application status
* View assessment history
* View revision history

### Penilai Dokumen

Assessors who review submitted applications.

Capabilities:

* Login
* View assessor dashboard
* View submitted applications
* Add assessment notes
* Change application status
* Request revisions
* Approve applications
* Reject applications
* View assessment history

These roles and capabilities are based on the requirements of the technical-test document.

---

## 2. Technology Stack

| Technology      | Version / Purpose     |
| --------------- | --------------------- |
| PHP             | 8.2+                  |
| Laravel         | 11/12                 |
| PostgreSQL      | Database              |
| REST API        | Backend communication |
| Laravel Sanctum | API authentication    |
| Vue             | Frontend consumer     |
| Git             | Version control       |

The technical-test document specifies PHP 8.2+, Laravel 11/12, Vue, PostgreSQL, REST API, and Git as the technology stack.

---

## 3. Main Features

### Authentication

* User registration
* User login
* User logout
* Authenticated user information
* Token-based authentication using Laravel Sanctum
* Password hashing
* Authentication middleware

### User Management

* Applicant users
* Assessor users
* Role-based authorization
* User information

### Project / Application Management

* Create application
* View application
* Update Draft application
* Delete Draft application
* Submit application
* View application status
* Search applications
* Filter applications
* Paginate application results

### Document Management

* Upload supporting documents
* Document metadata
* File type validation
* File size validation
* Document ownership validation
* Document deletion when permitted

### Assessment

* View submitted applications
* Review application
* Add assessment notes
* Request revision
* Approve application
* Reject application

### Revision

* Create revision request
* View revision history
* Update application after revision request
* Resubmit application
* Preserve previous revision records

### History / Audit Logs

The system records important activities, including:

* Application creation
* Application updates
* Document uploads
* Application submission
* Assessment
* Revision request
* Resubmission
* Approval
* Rejection

### Dashboard

Applicant dashboard:

* Total applications
* Draft applications
* Submitted applications
* Applications under review
* Revision-required applications
* Approved applications
* Rejected applications

Assessor dashboard:

* Total applications
* Pending assessments
* Applications requiring revision
* Approved applications
* Rejected applications

---

## 4. Application Workflow

The application follows a controlled status workflow.

```text
DRAFT
  |
  | Submit
  v
SUBMITTED
  |
  | Start Review
  v
UNDER_REVIEW
  |
  +----------------------+
  |                      |
  | Request Revision     | Approve
  v                      v
REVISION_REQUIRED     APPROVED
  |
  | Applicant Resubmits
  v
UNDER_REVIEW
  |
  +----------------------+
  |
  | Reject
  v
REJECTED
```

### Draft

The applicant can create and modify an application while it remains in Draft status.

### Submitted

The applicant has submitted the application for assessment.

### Under Review

The application is being reviewed by an assessor.

### Revision Required

The assessor has requested corrections or additional information.

### Approved

The application has successfully passed assessment.

### Rejected

The assessor has rejected the application.

Status changes are controlled by backend business logic and are not intended to be changed arbitrarily through normal project update requests.

---

## 5. Backend Architecture

The backend follows a layered Laravel architecture.

```text
Client
  |
  v
REST API Route
  |
  v
Authentication
  |
  v
Authorization / Policy
  |
  v
Form Request Validation
  |
  v
Controller
  |
  v
Service Layer
  |
  +---- Model / Database
  |
  +---- File Storage
  |
  +---- Workflow
  |
  +---- Activity Log
  |
  v
API Resource
  |
  v
JSON Response
```

### Controller

Controllers are responsible for handling HTTP requests and returning API responses.

### Form Request

Form Requests handle input validation.

### Service

Services contain business logic and workflow operations.

### Model

Models represent database entities and relationships.

### Policy

Policies handle authorization and ownership rules.

### Resource

API Resources define the structure of JSON responses.

---

## 6. Project Structure

```text
backend/
│
├── app/
│   ├── Enums/
│   │   ├── UserRole.php
│   │   ├── ProjectStatus.php
│   │   └── AssessmentAction.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── ProjectController.php
│   │   │   ├── DocumentController.php
│   │   │   ├── AssessmentController.php
│   │   │   ├── RevisionController.php
│   │   │   └── HistoryController.php
│   │   │
│   │   ├── Requests/
│   │   └── Resources/
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── Project.php
│   │   ├── Document.php
│   │   ├── Assessment.php
│   │   ├── Revision.php
│   │   └── ActivityLog.php
│   │
│   ├── Policies/
│   │   ├── ProjectPolicy.php
│   │   ├── DocumentPolicy.php
│   │   └── AssessmentPolicy.php
│   │
│   └── Services/
│       ├── AuthService.php
│       ├── ProjectService.php
│       ├── DocumentService.php
│       ├── AssessmentService.php
│       ├── RevisionService.php
│       ├── HistoryService.php
│       └── DashboardService.php
│
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── routes/
│   └── api.php
│
├── tests/
│   ├── Feature/
│   └── Unit/
│
├── storage/
│
├── .env.example
├── composer.json
└── README.md
```

---

## 7. Database

The application uses PostgreSQL.

Main database entities:

```text
users
projects
documents
assessments
revisions
activity_logs
```

### Entity Relationships

```text
User
 |
 +----< Projects
 |
 +----< Assessments
 |
 +----< Documents
 |
 +----< ActivityLogs


Project
 |
 +----< Documents
 |
 +----< Assessments
 |
 +----< Revisions
 |
 +----< ActivityLogs


Assessment
 |
 +----< Revisions
```

### Database Design Considerations

The database should use:

* Foreign keys
* Unique constraints
* Appropriate indexes
* Normalized tables
* Timestamp columns
* Proper relationship definitions
* Referential integrity

Indexes should be added to frequently queried fields such as:

* `users.email`
* `projects.user_id`
* `projects.status`
* `projects.project_number`
* `projects.created_at`
* `documents.project_id`
* `assessments.project_id`
* `assessments.assessor_id`
* `activity_logs.project_id`
* `activity_logs.created_at`

Database design is an important part of the technical assessment, with specific consideration for normalization, relationships, indexes, and constraints.

---

## 8. Requirements

Before running the backend, install:

* PHP 8.2 or newer
* Composer
* PostgreSQL
* Laravel-compatible PHP extensions
* Git

Optional development tools:

* Laravel Sail
* Docker
* Redis
* Postman / Insomnia

---

## 9. Installation

### 1. Clone Repository

```bash
git clone <repository-url>
```

Move into the backend directory:

```bash
cd backend
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Create Environment File

```bash
cp .env.example .env
```

For Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Configure PostgreSQL

Update `.env`:

```env
APP_NAME="Document Eligibility System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=document_eligibility
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

Replace the database credentials with the local PostgreSQL configuration.

### 6. Run Migrations

```bash
php artisan migrate
```

### 7. Seed Development Data

```bash
php artisan db:seed
```

Or:

```bash
php artisan migrate:fresh --seed
```

Use `migrate:fresh --seed` only when resetting a development/test database.

### 8. Create Storage Link

```bash
php artisan storage:link
```

### 9. Start Laravel Server

```bash
php artisan serve
```

The API should then be available at:

```text
http://localhost:8000
```

---

## 10. Authentication

Authentication uses Laravel Sanctum.

Protected API requests require an authentication token.

Example header:

```text
Authorization: Bearer <token>
Accept: application/json
```

### Authentication Endpoints

```text
POST /api/v1/auth/register
POST /api/v1/auth/login
POST /api/v1/auth/logout
GET  /api/v1/auth/me
```

Authentication endpoints should never return passwords or other sensitive credentials.

---

## 11. API Endpoints

Base URL:

```text
/api/v1
```

### Authentication

```text
POST /auth/register
POST /auth/login
POST /auth/logout
GET  /auth/me
```

### Projects

```text
GET    /projects
POST   /projects
GET    /projects/{id}
PUT    /projects/{id}
DELETE /projects/{id}
POST   /projects/{id}/submit
```

### Documents

```text
GET    /projects/{id}/documents
POST   /projects/{id}/documents
DELETE /documents/{id}
```

### Assessments

```text
GET  /assessments
GET  /projects/{id}/assessment
POST /projects/{id}/assessment
POST /projects/{id}/approve
POST /projects/{id}/reject
```

### Revisions

```text
GET  /projects/{id}/revisions
POST /projects/{id}/revision
POST /projects/{id}/resubmit
```

### History

```text
GET /projects/{id}/history
GET /assessment-history
```

### Dashboard

```text
GET /dashboard/pemohon
GET /dashboard/penilai
```

---

## 12. API Response Format

Successful responses should use a consistent JSON structure.

Example:

```json
{
    "success": true,
    "message": "Project retrieved successfully",
    "data": {}
}
```

Validation or business errors should provide meaningful messages.

Example:

```json
{
    "success": false,
    "message": "The project cannot be updated because it has already been submitted.",
    "errors": {}
}
```

HTTP status codes should represent the result of the operation appropriately.

Examples:

```text
200 OK
201 Created
400 Bad Request
401 Unauthorized
403 Forbidden
404 Not Found
422 Unprocessable Entity
500 Internal Server Error
```

---

## 13. Authorization

The system separates access between:

```text
PEMOHON
PENILAI
```

### Pemohon

Can:

```text
Create project
Update Draft project
Upload documents
Submit project
View own projects
View own history
View revision history
Resubmit revision-required projects
```

Cannot:

```text
Approve projects
Reject projects
Request revisions
Access unrelated applicant projects
```

### Penilai

Can:

```text
View submitted applications
Review applications
Add notes
Request revisions
Approve applications
Reject applications
View assessment history
```

Authorization should be implemented using Laravel authentication middleware and policies.

---

## 14. File Upload

Supporting documents can be uploaded through the project application.

The backend validates:

* File existence
* File type
* MIME type
* File extension
* File size
* User authorization
* Project status

Recommended supported formats:

```text
PDF
DOC
DOCX
JPG
JPEG
PNG
```

Maximum file size should be configured according to the application's requirements.

Files should be stored through Laravel's filesystem abstraction rather than directly manipulating filesystem paths.

---

## 15. Pagination

Because the system is expected to handle a large number of applications, API endpoints that return collections must use pagination.

Example:

```text
GET /api/v1/projects?page=1&per_page=20
```

Optional filtering:

```text
GET /api/v1/projects
    ?status=UNDER_REVIEW
    &search=project-name
    &page=1
    &per_page=20
```

The backend should avoid returning thousands of database records in a single API response.

---

## 16. Performance Optimization

The technical-test scenario expects the system to potentially handle hundreds of thousands to millions of application records and history records.

The backend therefore considers:

### Database Indexing

Indexes are added to frequently searched, filtered, joined, and sorted columns.

### Pagination

Large datasets are returned in pages.

### Eager Loading

Laravel relationships should be eager loaded where necessary to prevent N+1 queries.

### Query Optimization

Queries should:

* Select only required columns
* Use appropriate indexes
* Avoid unnecessary joins
* Avoid loading entire datasets into memory
* Use database-level aggregation where possible

### Dashboard Optimization

Dashboard statistics should use efficient database aggregation.

Frequently requested statistics can be cached using Laravel Cache or Redis.

Performance optimization is explicitly weighted in the technical assessment, including query optimization, pagination, eager loading, indexing, dashboard performance, and API response time.

---

## 17. Activity Logging

Important workflow actions are recorded.

Example:

```text
Project Created
Project Updated
Document Uploaded
Project Submitted
Assessment Started
Revision Requested
Project Resubmitted
Project Approved
Project Rejected
```

Each log may contain:

```text
User
Project
Action
Old Status
New Status
Description
Timestamp
```

This allows the system to maintain a complete history of the application process.

---

## 18. Testing

Run the test suite with:

```bash
php artisan test
```

Or:

```bash
vendor/bin/phpunit
```

Tests should cover:

### Authentication

* Registration
* Login
* Logout
* Invalid credentials
* Unauthorized requests

### Authorization

* Applicant access
* Assessor access
* Ownership validation
* Restricted operations

### Project

* Create
* Update
* Delete
* Submit
* Status transitions

### Documents

* Valid upload
* Invalid format
* Invalid file size
* Unauthorized upload

### Assessment

* Review
* Revision
* Approval
* Rejection

### History

* Activity creation
* History retrieval
* Pagination

The technical-test document explicitly identifies Unit Test or Feature Test as an additional-value implementation.

---

## 19. Development Database

The project includes seeders and factories for development.

Recommended test data:

```text
Users
├── 1000 Pemohon
└── 1000 Penilai

Projects
└── 10,000 Project / Application records
```

The technical-test document specifies these quantities for the expected project data.

Factories should also be capable of generating larger datasets for performance testing.

---

## 20. Environment Variables

Important environment variables include:

```env
APP_NAME=
APP_ENV=
APP_KEY=
APP_DEBUG=
APP_URL=

DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

FILESYSTEM_DISK=

CACHE_STORE=

QUEUE_CONNECTION=
```

Do not commit `.env` or production credentials to Git.

Use `.env.example` as the configuration template.

---

## 21. Git Workflow

The project uses Git for version control.

Recommended branches:

```text
main
└── develop
    ├── feature/authentication
    ├── feature/project-management
    ├── feature/document-upload
    ├── feature/assessment
    ├── feature/revision
    ├── feature/dashboard
    └── feature/testing
```

Recommended commit style:

```text
chore: initialize Laravel backend
feat: implement authentication
feat: add project management
feat: add document upload
feat: implement project submission
feat: implement assessment workflow
feat: implement revision workflow
feat: add activity history
feat: add dashboard endpoints
perf: optimize project queries
perf: add database indexes
test: add project feature tests
docs: update API documentation
```

The technical-test requirements specifically ask for clear and gradual Git commits and branches.

---

## 22. Code Quality

The backend follows Laravel conventions and clean-code principles.

Guidelines:

* Keep controllers lightweight.
* Put business logic in services.
* Use Form Requests for validation.
* Use Policies for authorization.
* Use API Resources for response formatting.
* Use meaningful class and method names.
* Avoid duplicated business logic.
* Use database transactions for multi-step workflows.
* Keep database queries efficient.
* Add comments only where they provide useful context.
* Follow PSR-12 coding standards.

The technical test specifically requires code to be structured, readable, and follow clean-code principles.

---

## 23. Optional Features

The backend can be extended with:

### Role & Permission

Implement more granular permissions using a permission-management package.

### Redis / Cache

Cache frequently requested dashboard statistics and other suitable data.

### Queue

Move long-running tasks to queues, such as:

* Document processing
* Notifications
* Email
* Other background operations

### Export

Provide:

```text
Excel
PDF
```

exports for relevant application and assessment data.

### Docker

Provide Docker configuration for:

```text
Laravel
PostgreSQL
Redis
```

### CI/CD

Configure GitLab CI or another CI/CD pipeline to:

```text
Install dependencies
Run code checks
Run tests
Build/deploy application
```

These are among the additional features identified in the technical-test document.

---

## 24. API Documentation

API documentation should describe:

* Endpoint
* HTTP method
* Authentication requirement
* User role
* Request parameters
* Request body
* Validation rules
* Response format
* HTTP status codes
* Error responses

Recommended documentation tools:

```text
Postman Collection
```

or:

```text
OpenAPI / Swagger
```

The completed API documentation should be included with the project submission.

---

## 25. Security Considerations

The backend should implement:

* Password hashing
* Authentication middleware
* Role-based authorization
* Ownership checks
* Request validation
* File validation
* Mass-assignment protection
* Rate limiting where appropriate
* Secure file storage
* Sensitive-data protection
* Proper HTTP status codes
* Database constraints

Applicants must not be able to access or modify another applicant's projects by manipulating an ID in the API request.

Similarly, applicants must not be able to call assessor-only workflow endpoints.

---

## 26. Production Considerations

Before production deployment:

```text
APP_ENV=production
APP_DEBUG=false
```

Additional production configuration should include:

* Secure database credentials
* HTTPS
* Proper filesystem configuration
* Cache configuration
* Queue workers
* Log monitoring
* Database backups
* Appropriate PHP configuration
* Web server configuration
* Environment secret management

---

## 27. Useful Artisan Commands

Start development server:

```bash
php artisan serve
```

Run migrations:

```bash
php artisan migrate
```

Reset database and seed:

```bash
php artisan migrate:fresh --seed
```

Create migration:

```bash
php artisan make:migration create_example_table
```

Create model:

```bash
php artisan make:model Example
```

Create controller:

```bash
php artisan make:controller ExampleController
```

Create Form Request:

```bash
php artisan make:request ExampleRequest
```

Create policy:

```bash
php artisan make:policy ExamplePolicy
```

Run tests:

```bash
php artisan test
```

Clear application cache:

```bash
php artisan optimize:clear
```

Create storage link:

```bash
php artisan storage:link
```

---

## 28. Development Checklist

### Setup

* [ ] Install PHP 8.2+
* [ ] Install Composer
* [ ] Install PostgreSQL
* [ ] Clone repository
* [ ] Configure `.env`
* [ ] Install Composer dependencies
* [ ] Generate application key
* [ ] Run migrations
* [ ] Run seeders
* [ ] Create storage link

### Backend

* [ ] Authentication
* [ ] Sanctum
* [ ] User roles
* [ ] Project CRUD
* [ ] Document upload
* [ ] File validation
* [ ] Project submission
* [ ] Assessment
* [ ] Revision
* [ ] Approval
* [ ] Rejection
* [ ] Activity history
* [ ] Dashboard APIs

### Performance

* [ ] Database indexes
* [ ] Pagination
* [ ] Eager loading
* [ ] Query optimization
* [ ] Dashboard aggregation optimization
* [ ] Cache where appropriate

### Quality

* [ ] Feature tests
* [ ] Unit tests
* [ ] API documentation
* [ ] Clean code
* [ ] Git history
* [ ] README verification

---

## 29. Final Project Workflow

```text
Applicant
    |
    v
Register / Login
    |
    v
Create Project
    |
    v
Upload Documents
    |
    v
Save Draft
    |
    v
Submit Application
    |
    v
Assessor Reviews
    |
    +--------------------+
    |                    |
    v                    v
Revision Required     Assessment
    |                    |
    v                +---+---+
Applicant Fixes      |       |
    |              Approve  Reject
    v                |       |
Resubmit             v       v
    |             APPROVED REJECTED
    |
    +----> Under Review
```

Every significant transition should be validated, authorized, persisted, and recorded in the application history.

---

## 30. Technical Test Submission

The technical-test requirements state that the completed project should be uploaded to GitLab and that the submission should include the repository URL, project demo video, database/project information, and other required materials in a PDF named:

```text
Technical Test_Nama Kandidat.pdf
```

The project should also be runnable according to the instructions provided in this README.

---

## License

This project was created for a technical assessment and demonstration purpose.
