I already have a Laravel Model and Migration created.

Now generate ONLY backend logic files for a production-ready Laravel Resource Controller structure using the existing model.

IMPORTANT:

* Do NOT generate any UI, Blade files, HTML, Bootstrap, or frontend code.
* Do NOT generate migration or model.
* Focus ONLY on backend architecture and clean code structure.

Generate:

1. Resource Controller
2. Store Request
3. Update Request
4. Dedicated Service Class
5. Custom Validation Rules (if required)
6. Resource Routes

Use the existing model name dynamically.

Example:
If model is Customer then generate:

* CustomerController
* StoreCustomerRequest
* UpdateCustomerRequest
* CustomerService

---

# Architecture Rules

* Controller must remain lightweight.
* All business logic must be moved into Service class.
* Validation logic must remain inside Request classes only.
* Use dependency injection properly.
* Use route model binding.
* Use reusable methods.
* Follow:

  * SOLID principles
  * Clean Architecture
  * PSR standards
  * Laravel conventions

---

# Controller Requirements

Create Resource Controller methods:

* index
* create
* store
* show
* edit
* update
* destroy

Controller Rules:

* Do NOT write business logic inside controller.
* Use try-catch block in EVERY method.
* Use proper exception handling.
* Use:

  * Log::info()
  * Log::warning()
  * Log::error()

Examples:

* Resource created

* Resource updated

* Resource deleted

* Validation failed

* Exception occurred

* Return proper redirect responses with flash messages.

* Add PHPDoc comments.

* Use meaningful variable names.

---

# Service Class Requirements

Create a dedicated Service class.

Move all business logic into service methods like:

* create()
* update()
* delete()

VERY IMPORTANT:

Use database transaction handling properly:

* DB::beginTransaction()
* DB::commit()
* DB::rollBack()

Requirements:

* If any query/process fails then automatically rollback all database changes.
* Ensure database consistency and data integrity.
* Wrap critical operations inside transactions.
* Throw meaningful exceptions after rollback.
* Log transaction failures properly.
* Handle nested operations safely.

Example use cases:

* Multiple model save operations
* File upload + database save
* Related table insert/update
* Status update operations

Also:

* Use reusable methods.
* Keep service scalable and production-ready.
* Use latest Laravel best practices.

---

# Validation Requirements

Generate:

* StoreRequest
* UpdateRequest

Validation Rules:

* All validation logic must stay inside Request classes only.
* Add custom validation messages.
* Add authorize() method.
* Use conditional validation if needed.
* Create reusable Custom Rule classes if validation becomes complex.

---

# Logging Requirements

Use proper logging everywhere:

* Log::info()
* Log::warning()
* Log::error()

Include meaningful context in logs such as:

* model id
* user id
* request data
* exception message

---

# Code Quality Requirements

* Add PHPDoc comments.
* Use strict clean coding standards.
* Keep code enterprise-level and scalable.
* Use reusable architecture.
* Use latest Laravel syntax.

---

# Response Format

Generate code file-by-file in this sequence:

1. Controller
2. Store Request
3. Update Request
4. Service
5. Custom Rule (if needed)
6. Routes

For every file:

* Mention file path
* Explain purpose briefly
* Then provide complete production-ready code.
