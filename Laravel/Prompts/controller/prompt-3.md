I already have a Laravel Customer model and migration created.

Now generate a complete production-ready Customer Resource Controller structure using the existing Customer model.

Requirements:

1. Use existing `Customer` model only.
2. Generate:

   * CustomerController
   * StoreCustomerRequest
   * UpdateCustomerRequest
   * CustomerService
   * Custom Validation Rules (if needed)
   * Resource Routes
3. Follow Laravel latest best practices.

---

## Controller Requirements

Create a lightweight `CustomerController` with methods:

* index
* create
* store
* show
* edit
* update
* destroy

Controller Rules:

* Do NOT write business logic inside controller.
* Use dedicated Service class for:

  * store
  * update
  * delete
  * file upload
  * reusable operations
* Use dependency injection.
* Use route model binding.
* Use try-catch block in every controller method.
* Use proper:

  * Log::info()
  * Log::warning()
  * Log::error()

Example logging cases:

* Customer created

* Customer updated

* Customer deleted

* Validation failed

* Exception occurred

* Return proper redirect responses with success/error flash messages.

* Use clean and scalable architecture.

---

## Validation Requirements

Generate:

* StoreCustomerRequest
* UpdateCustomerRequest

Validation Rules:

* All validation must stay inside Request classes only.
* Add custom validation messages.
* Add authorize() method.
* Add conditional validation if needed.
* Create reusable custom Rule classes if validation is complex.

---

## Service Class Requirements

Create `CustomerService` class.

Move all business logic into service methods:

* createCustomer()
* updateCustomer()
* deleteCustomer()
* uploadProfilePhoto()
* changeStatus()

Requirements:

* Use DB Transactions where required.
* Handle exceptions properly.
* Throw meaningful exceptions.
* Use reusable methods.
* Handle file uploads safely.
* Use clean code structure.

---

## Code Quality Requirements

* Follow:

  * SOLID principles
  * Clean Architecture
  * PSR standards
  * Laravel conventions
* Add PHPDoc comments.
* Use meaningful variable names.
* Make code enterprise-level and scalable.

---

## Response Format

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
* Then provide full production-ready code.

Use Bootstrap/web response style instead of API JSON response.
