I already have a Laravel `Customer` model and migration created.

Now generate a complete production-ready Laravel Resource Controller structure using this existing `Customer` model.

Requirements:

1. Use the existing `Customer` model only.

2. Generate:

   * CustomerController
   * StoreCustomerRequest
   * UpdateCustomerRequest
   * CustomerService
   * Custom Validation Rules (if required)
   * Resource Routes

3. Controller must follow clean architecture and Laravel best practices.

4. Controller methods required:

   * index
   * create
   * store
   * show
   * edit
   * update
   * destroy

5. IMPORTANT:

   * No business logic inside controller.
   * All business logic must be inside `CustomerService`.
   * Validation must be handled only in Form Request classes.
   * Complex validation must use reusable Custom Rule classes.

6. Use:

   * try-catch block in every controller method
   * Log::info()
   * Log::warning()
   * Log::error()

7. Logging examples:

   * customer created
   * customer updated
   * customer deleted
   * validation failed
   * exception occurred

8. Service class responsibilities:

   * store customer
   * update customer
   * delete customer
   * reusable methods
   * DB transaction handling
   * file upload handling if needed

9. Validation Requirements:

   * proper validation rules
   * custom messages
   * authorize() method
   * unique validation for update
   * conditional validation if needed

10. Use:

* dependency injection
* route model binding
* meaningful variable names
* proper PHPDoc comments
* reusable methods
* scalable architecture

11. Response Handling:

* redirect responses for web routes
* success/error flash messages
* proper exception messages

12. Error Handling:

* handle exceptions professionally
* log complete exception details
* user-friendly error messages

13. Follow:

* SOLID principles
* PSR standards
* Laravel conventions
* Clean Code principles

14. Generate complete code file-by-file with:

* file path
* explanation
* full code

15. Make the code enterprise-level, scalable, reusable, and production-ready.

Use Bootstrap 5 compatible structure wherever needed.
