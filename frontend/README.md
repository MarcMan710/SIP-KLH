# Technical Test Programmer — Frontend

Frontend application for the **Document Eligibility Application Management System**.

The application is built with **Vue** and communicates with the Laravel backend through a **REST API**.

The system supports two main user roles:

* **Pemohon Dokumen** — Document Applicant
* **Penilai Dokumen** — Document Assessor

---

## 1. Project Overview

This application is part of a document application management system for a government institution.

The system manages the process of submitting document eligibility applications, reviewing applications, requesting revisions, approving applications, and rejecting applications.

The frontend provides role-based interfaces for applicants and assessors.

### Applicant Workflow

```text
Login / Register
      ↓
Applicant Dashboard
      ↓
Create Project / Application
      ↓
Fill Application Form
      ↓
Upload Documents
      ↓
Save as Draft
      ↓
Submit Application
      ↓
Under Review
      ↓
┌───────────────┬───────────────┬───────────────┐
│               │               │               │
▼               ▼               ▼
Revision       Approved       Rejected
Required
│
▼
Update Application
│
▼
Upload Revised Documents
│
▼
Resubmit
│
▼
Under Review
```

### Assessor Workflow

```text
Login
  ↓
Assessor Dashboard
  ↓
Application List
  ↓
Application Detail
  ↓
Review Application & Documents
  ↓
Add Assessment Notes
  ↓
┌───────────────┬───────────────┬───────────────┐
│               │               │
▼               ▼               ▼
Revision      Approved        Rejected
Required
```

---

## 2. Technology Stack

| Technology            | Purpose                            |
| --------------------- | ---------------------------------- |
| Vue                   | Frontend framework                 |
| JavaScript            | Application logic                  |
| REST API              | Communication with Laravel backend |
| Laravel               | Backend API                        |
| PostgreSQL            | Database                           |
| Git                   | Version control                    |
| Chart.js / ApexCharts | Dashboard visualization            |
| CSS                   | Application styling                |

The technical-test document specifies PHP 8.2+, Laravel 11/12, Vue, PostgreSQL, REST API, and Git as the technology stack.

---

## 3. Main Features

### Authentication

* User registration
* User login
* User logout
* Authenticated user information
* Role-based navigation
* Protected routes

### Applicant Features

* Applicant dashboard
* Create document application
* Edit draft application
* Upload required documents
* Submit application
* View application status
* View assessment history
* View revision history
* Resubmit revised application

### Assessor Features

* Assessor dashboard
* View submitted applications
* Search applications
* Filter applications
* View application details
* View uploaded documents
* Add assessment notes
* Request application revision
* Approve application
* Reject application
* View assessment history

### Dashboard

The dashboard provides summarized information such as:

* Total applications
* Draft applications
* Submitted applications
* Applications under review
* Revision-required applications
* Approved applications
* Rejected applications

Charts can be used to visualize application statistics.

The technical test identifies dashboard functionality and Chart.js/ApexCharts as relevant features.

---

## 4. User Roles

### Pemohon Dokumen

The applicant can:

* Login
* Access the applicant dashboard
* Create a project/application
* Upload documents
* Edit applications while they are still Draft
* Submit an application for assessment
* View assessment status/history
* View revision history

These permissions follow the requirements in the technical-test document.

### Penilai Dokumen

The assessor can:

* Login
* Access the assessor dashboard
* View submitted applications
* Add assessment notes
* Change the application assessment status
* Request revisions
* Approve applications
* Reject applications
* View assessment history

These permissions follow the requirements in the technical-test document.

---

## 5. Application Status

The frontend displays the application status returned by the backend.

Recommended statuses:

```text
DRAFT
   ↓
SUBMITTED
   ↓
UNDER_REVIEW
   ├── REVISION_REQUIRED
   │       ↓
   │   RESUBMITTED
   │       ↓
   │   UNDER_REVIEW
   │
   ├── APPROVED
   │
   └── REJECTED
```

The frontend must not independently modify application status.

Status transitions are controlled by the Laravel backend.

The Vue application only:

1. Displays the current status.
2. Displays actions available for the current status.
3. Sends workflow requests to the REST API.
4. Refreshes the application state after a successful operation.

---

## 6. Frontend Architecture

The application uses a layered Vue architecture.

```text
┌─────────────────────────────────────┐
│               Views                 │
│ Login / Dashboard / Projects / etc.│
└─────────────────┬───────────────────┘
                  │
                  ▼
┌─────────────────────────────────────┐
│            Components               │
│ Forms / Tables / Modal / Cards      │
└─────────────────┬───────────────────┘
                  │
                  ▼
┌─────────────────────────────────────┐
│              Stores                 │
│ Auth / Project / Dashboard / etc.   │
└─────────────────┬───────────────────┘
                  │
                  ▼
┌─────────────────────────────────────┐
│             Services                │
│ Auth / Project / Document / etc.   │
└─────────────────┬───────────────────┘
                  │
                  ▼
┌─────────────────────────────────────┐
│            REST API                 │
│          Laravel Backend            │
└─────────────────────────────────────┘
```

### Views

Views represent complete application pages.

Examples:

```text
LoginView
RegisterView
ApplicantDashboardView
ProjectListView
CreateProjectView
ProjectDetailView
AssessorDashboardView
ApplicationListView
ApplicationDetailView
```

### Components

Components represent reusable UI elements.

Examples:

```text
AppButton
AppInput
AppModal
AppTable
AppPagination
StatusBadge
ProjectForm
DocumentUploader
AssessmentForm
```

### Stores

Stores maintain shared frontend state.

Examples:

```text
authStore
projectStore
dashboardStore
assessmentStore
```

### Services

Services are responsible for communication with the Laravel API.

Examples:

```text
authService
projectService
documentService
assessmentService
revisionService
historyService
dashboardService
```

---

## 7. Directory Structure

```text
frontend/
│
├── public/
│
├── src/
│   │
│   ├── assets/
│   │   ├── images/
│   │   └── styles/
│   │
│   ├── components/
│   │   ├── common/
│   │   ├── layout/
│   │   ├── dashboard/
│   │   ├── project/
│   │   ├── document/
│   │   ├── assessment/
│   │   └── revision/
│   │
│   ├── layouts/
│   │   ├── AuthLayout.vue
│   │   └── DashboardLayout.vue
│   │
│   ├── views/
│   │   ├── auth/
│   │   ├── applicant/
│   │   └── assessor/
│   │
│   ├── stores/
│   │   ├── authStore.js
│   │   ├── projectStore.js
│   │   ├── dashboardStore.js
│   │   └── assessmentStore.js
│   │
│   ├── services/
│   │   ├── api.js
│   │   ├── authService.js
│   │   ├── projectService.js
│   │   ├── documentService.js
│   │   ├── assessmentService.js
│   │   ├── revisionService.js
│   │   ├── historyService.js
│   │   └── dashboardService.js
│   │
│   ├── router/
│   │   ├── index.js
│   │   └── guards.js
│   │
│   ├── composables/
│   │   ├── useAuth.js
│   │   ├── usePagination.js
│   │   ├── useProject.js
│   │   ├── useDocument.js
│   │   └── useNotification.js
│   │
│   ├── utils/
│   │   ├── constants.js
│   │   ├── formatters.js
│   │   ├── validators.js
│   │   └── permissions.js
│   │
│   ├── App.vue
│   └── main.js
│
├── .env
├── package.json
└── README.md
```

---

## 8. Route Structure

### Public Routes

```text
/login
/register
```

### Applicant Routes

```text
/applicant/dashboard
/applicant/projects
/applicant/projects/create
/applicant/projects/:id
/applicant/projects/:id/edit
/applicant/projects/:id/revision
/applicant/projects/:id/history
```

### Assessor Routes

```text
/assessor/dashboard
/assessor/applications
/assessor/applications/:id
/assessor/history
```

---

## 9. API Integration

The frontend communicates with the Laravel backend through REST API endpoints.

### Authentication

```text
POST /api/v1/auth/register
POST /api/v1/auth/login
POST /api/v1/auth/logout
GET  /api/v1/auth/me
```

### Projects

```text
GET    /api/v1/projects
POST   /api/v1/projects
GET    /api/v1/projects/{id}
PUT    /api/v1/projects/{id}
DELETE /api/v1/projects/{id}
POST   /api/v1/projects/{id}/submit
```

### Documents

```text
GET    /api/v1/projects/{id}/documents
POST   /api/v1/projects/{id}/documents
DELETE /api/v1/documents/{id}
```

### Assessment

```text
GET  /api/v1/assessments
GET  /api/v1/projects/{id}/assessment
POST /api/v1/projects/{id}/assessment
POST /api/v1/projects/{id}/revision
POST /api/v1/projects/{id}/approve
POST /api/v1/projects/{id}/reject
```

### History

```text
GET /api/v1/projects/{id}/history
GET /api/v1/assessment-history
```

### Dashboard

```text
GET /api/v1/dashboard/pemohon
GET /api/v1/dashboard/penilai
```

> The exact API paths should remain synchronized with the Laravel backend implementation.

---

## 10. Environment Configuration

Create a `.env` file in the frontend project.

Example:

```text
VITE_API_BASE_URL=http://localhost:8000/api/v1
```

The API URL should point to the Laravel backend.

Do not place sensitive backend credentials in the frontend environment configuration.

---

## 11. Installation

### 1. Clone the repository

```bash
git clone <repository-url>
```

### 2. Enter the frontend directory

```bash
cd frontend
```

### 3. Install dependencies

```bash
npm install
```

### 4. Configure environment variables

Create:

```text
.env
```

Configure the Laravel API URL:

```text
VITE_API_BASE_URL=http://localhost:8000/api/v1
```

### 5. Start the development server

```bash
npm run dev
```

The frontend will be available at the development URL displayed by Vite.

---

## 12. Production Build

Create a production build with:

```bash
npm run build
```

Preview the production build locally with:

```bash
npm run preview
```

Before deployment, verify that:

* The API URL is correct.
* Authentication works.
* Protected routes work.
* Documents can be uploaded.
* Dashboard statistics load correctly.
* Applicant workflow works.
* Assessor workflow works.

---

## 13. Authentication Flow

The authentication flow is:

```text
User
 ↓
Login Form
 ↓
Laravel Authentication API
 ↓
Authentication Success
 ↓
Store Authentication State
 ↓
Retrieve Current User
 ↓
Check User Role
 ↓
Redirect to Dashboard
```

### Applicant

```text
Login
 ↓
Applicant Dashboard
```

### Assessor

```text
Login
 ↓
Assessor Dashboard
```

Authentication and authorization must ultimately be enforced by the backend.

Frontend route guards are primarily responsible for preventing unauthorized navigation in the user interface.

---

## 14. Project Creation Flow

```text
Applicant Dashboard
        ↓
Create Project
        ↓
Project Form
        ↓
Save
        ↓
Project Created
        ↓
DRAFT
        ↓
Upload Documents
        ↓
Review Information
        ↓
Submit Application
```

The applicant can modify the project while it remains in Draft.

Once submitted, the frontend should no longer display normal editing controls.

---

## 15. Document Upload

The document upload interface supports:

* Selecting a document.
* Selecting the document type.
* Validating file format.
* Validating file size.
* Uploading the document.
* Showing upload progress where available.
* Displaying upload errors.
* Displaying uploaded documents.

The backend remains responsible for the final validation and authorization.

## The technical-test requirements explicitly include document upload and identify file format/size validation as an additional feature.

## 16. Assessment Flow

The assessor opens an application:

```text
Application List
      ↓
Application Detail
      ↓
Review Application
      ↓
Review Documents
      ↓
Add Notes
      ↓
Select Action
```

Available actions:

```text
Request Revision
Approve
Reject
```

The frontend should show a confirmation dialog before actions that change the application's workflow state.

---

## 17. Revision Flow

When an assessor requests a revision:

```text
Under Review
     ↓
Revision Required
     ↓
Applicant receives status
     ↓
Applicant opens Revision page
     ↓
Reads assessor notes
     ↓
Updates application
     ↓
Uploads revised documents
     ↓
Resubmits
     ↓
Under Review
```

The previous revision history must remain visible.

---

## 18. Dashboard

### Applicant Dashboard

Display:

```text
┌─────────────────┐
│ Total Projects  │
└─────────────────┘

┌────────┐ ┌───────────┐ ┌──────────────┐
│ Draft  │ │ Submitted │ │ Under Review │
└────────┘ └───────────┘ └──────────────┘

┌───────────────────┐ ┌──────────┐ ┌──────────┐
│ Revision Required │ │ Approved │ │ Rejected │
└───────────────────┘ └──────────┘ └──────────┘
```

### Assessor Dashboard

Display:

```text
┌──────────────────────┐
│ Total Applications   │
└──────────────────────┘

┌──────────────────┐ ┌───────────────────┐
│ Pending Review   │ │ Revision Requests │
└──────────────────┘ └───────────────────┘

┌──────────┐ ┌──────────┐
│ Approved │ │ Rejected │
└──────────┘ └──────────┘
```

Charts can visualize status distribution and application trends.

---

## 19. Pagination

The application is expected to handle significant amounts of data.

The technical-test scenario specifies:

```text
Project Applications: 10,000
Pemohon: 1,000
Penilai: 1,000
```

and notes that the system may eventually contain hundreds of thousands to millions of application and history records.
Therefore, frontend lists should use server-side pagination.

Example:

```text
GET /projects?page=1&per_page=20
```

The frontend should not request every project record at once.

Pagination should be implemented for:

* Project list
* Application list
* Assessment history
* Activity history
* Revision history where necessary

---

## 20. Search and Filtering

The project and application lists should support appropriate filters.

Possible filters:

```text
Search
├── Project Number
└── Project Name

Status
├── Draft
├── Submitted
├── Under Review
├── Revision Required
├── Approved
└── Rejected

Date
├── Start Date
└── End Date
```

Filtering should preferably be performed by the Laravel API rather than downloading all records to the browser.

---

## 21. UI/UX Guidelines

The frontend should provide:

* Consistent navigation.
* Clear status indicators.
* Responsive forms.
* Clear validation messages.
* Loading indicators.
* Empty states.
* Error states.
* Confirmation dialogs for important actions.
* Consistent buttons and form controls.
* Responsive tables.
* Clear distinction between applicant and assessor interfaces.

The technical-test assessment specifically assigns **25% to the Vue frontend**, including UI/UX, component structure, and state management.

---

## 22. Error Handling

The frontend should handle common API responses.

### Success

```text
200 OK
201 Created
204 No Content
```

Display appropriate success feedback.

### Validation Error

```text
422 Unprocessable Entity
```

Display field-level validation errors.

### Unauthorized

```text
401 Unauthorized
```

Clear invalid authentication state and redirect to login.

### Forbidden

```text
403 Forbidden
```

Display an appropriate authorization message.

### Not Found

```text
404 Not Found
```

Display a resource-not-found page/message.

### Server Error

```text
500 Internal Server Error
```

Display a generic error message without exposing backend details.

---

## 23. State Management

Global state should be used only where necessary.

### Auth Store

Responsible for:

```text
Current User
Authentication State
User Role
```

### Project Store

Responsible for:

```text
Projects
Selected Project
Pagination
Filters
Loading State
```

### Dashboard Store

Responsible for:

```text
Dashboard Statistics
Chart Data
```

### Assessment Store

Responsible for:

```text
Applications
Selected Application
Assessment Information
Assessment History
```

Avoid putting every component's local state into global stores.

---

## 24. Component Guidelines

Create reusable components for repeated UI patterns.

For example:

```text
AppButton
AppInput
AppSelect
AppModal
AppTable
AppPagination
StatusBadge
AppAlert
AppLoading
```

Domain-specific components should be separated:

```text
ProjectForm
DocumentUploader
AssessmentForm
RevisionForm
```

This keeps the project easier to maintain and reduces duplicated UI logic.

---

## 25. Performance Considerations

The frontend should support the large data volume described in the technical-test case.

Important practices:

* Server-side pagination.
* Server-side filtering.
* Avoid loading unnecessary records.
* Avoid rendering thousands of rows simultaneously.
* Load dashboard data separately from detailed data.
* Avoid unnecessary API requests.
* Reuse cached state where appropriate.
* Use lazy-loaded routes where appropriate.
* Use optimized document previews.
* Avoid unnecessarily large JavaScript bundles.
* Display loading states while data is being retrieved.

The technical test explicitly evaluates query performance, pagination, eager loading, indexing, dashboard performance, and API response time.

---

## 26. Security Considerations

The frontend should:

* Never store backend secrets.
* Never trust client-side authorization alone.
* Validate user input for better UX.
* Avoid rendering untrusted HTML.
* Handle authentication state securely.
* Avoid exposing internal server paths.
* Use HTTPS in production.
* Handle expired authentication gracefully.

The Laravel backend remains responsible for enforcing authorization and validating requests.

---

## 27. Git Workflow

Use clear and incremental commits.

Example branches:

```text
main
│
└── develop
     │
     ├── feature/authentication
     ├── feature/project-management
     ├── feature/document-upload
     ├── feature/assessment
     ├── feature/revision
     ├── feature/dashboard
     └── feature/history
```

Example commits:

```text
chore: initialize Vue frontend

feat: add authentication pages
feat: add authentication state management
feat: add protected routes

feat: add applicant dashboard
feat: add project management interface
feat: add document upload interface

feat: add assessor dashboard
feat: add application review interface
feat: add assessment workflow

feat: add revision workflow
feat: add application history

feat: add dashboard charts
perf: optimize project list rendering

docs: add frontend README
```

The technical-test instructions require the project to be uploaded to GitLab and Git commits/branches to be clear and incremental.

---

## 28. Recommended Development Order

```text
1. Initialize Vue Project
        ↓
2. Configure API Client
        ↓
3. Create Global Styles
        ↓
4. Implement Authentication
        ↓
5. Implement Auth Store
        ↓
6. Implement Router & Guards
        ↓
7. Implement Dashboard Layout
        ↓
8. Implement Applicant Dashboard
        ↓
9. Implement Project Management
        ↓
10. Implement Document Upload
        ↓
11. Implement Project Submission
        ↓
12. Implement Assessor Dashboard
        ↓
13. Implement Application Review
        ↓
14. Implement Assessment Actions
        ↓
15. Implement Revision Workflow
        ↓
16. Implement History
        ↓
17. Implement Dashboard Charts
        ↓
18. Implement Loading/Error/Empty States
        ↓
19. Optimize Frontend
        ↓
20. Test Complete Workflow
        ↓
21. Update README
```

---

## 29. Testing Checklist

Before considering the frontend complete, verify:

### Authentication

* [ ] Register works.
* [ ] Login works.
* [ ] Logout works.
* [ ] Invalid login displays an error.
* [ ] Protected pages require authentication.
* [ ] Applicant cannot access assessor pages.
* [ ] Assessor cannot access applicant-only pages.

### Applicant

* [ ] Applicant dashboard loads.
* [ ] Project list loads.
* [ ] Project can be created.
* [ ] Draft can be edited.
* [ ] Documents can be uploaded.
* [ ] Project can be submitted.
* [ ] Status is displayed correctly.
* [ ] Assessment history is displayed.
* [ ] Revision history is displayed.
* [ ] Revised application can be resubmitted.

### Assessor

* [ ] Assessor dashboard loads.
* [ ] Application list loads.
* [ ] Application search works.
* [ ] Application filtering works.
* [ ] Application details load.
* [ ] Documents can be reviewed.
* [ ] Assessment notes can be submitted.
* [ ] Revision can be requested.
* [ ] Application can be approved.
* [ ] Application can be rejected.
* [ ] Assessment history loads.

### General

* [ ] Loading states work.
* [ ] Empty states work.
* [ ] API errors are handled.
* [ ] Validation errors are displayed.
* [ ] Pagination works.
* [ ] Responsive layout works.
* [ ] Browser console has no unnecessary errors.

---

## 30. Optional Enhancements

The technical-test document lists several additional features that can be implemented after the core functionality:

* Dashboard charts using Chart.js or ApexCharts.
* Excel/PDF export.
* Backend caching.
* Queue-based processing.
* Unit tests.
* Feature tests.
* Docker.
* CI/CD using GitHub Actions or GitLab CI.

For the frontend specifically, possible enhancements include:

```text
Chart.js / ApexCharts
        ↓
Lazy-loaded routes
        ↓
Reusable component library
        ↓
Advanced filtering
        ↓
Responsive mobile UI
        ↓
Improved loading skeletons
        ↓
Frontend automated tests
```

---

## 31. Backend Dependency

The frontend requires the Laravel backend to be running.

Example local architecture:

```text
┌──────────────────────┐
│    Vue Frontend      │
│   localhost:5173     │
└──────────┬───────────┘
           │
           │ REST API
           ▼
┌──────────────────────┐
│   Laravel Backend    │
│   localhost:8000     │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│      PostgreSQL      │
└──────────────────────┘
```

Start the backend before testing frontend features that require API access.

---

## 32. Project Completion Criteria

The frontend is considered ready when:

* Authentication works.
* Role-based navigation works.
* Applicant workflow works from creation through submission.
* Document upload works.
* Assessor workflow works from review through final decision.
* Revision workflow works.
* History is accessible.
* Dashboard statistics are displayed.
* API errors are handled.
* Pagination works.
* UI is responsive.
* Components are reusable.
* State management is organized.
* README instructions are sufficient to run the project.
* Frontend and backend communicate correctly through REST API.

## The technical test emphasizes a runnable project, clean structure, code quality, frontend UI/UX, component structure, state management, Git, README, and API documentation.

## 33. Author

```text
Project : Technical Test Programmer
Frontend : Vue
Backend  : Laravel
Database : PostgreSQL
API      : REST API
```

**Repository structure:**

```text
project/
├── backend/
│   └── Laravel REST API
│
└── frontend/
    └── Vue Application
```

The frontend and backend should be maintained as separate applications while communicating through the documented REST API.
