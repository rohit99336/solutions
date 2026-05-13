नीचे एक प्रोफेशनल और reusable prompt दिया गया है जिसे आप किसी भी AI tool में use करके Laravel का clean architecture वाला controller generate करवा सकते हैं।
इस prompt में:

* Dedicated `Form Request` validation
* Custom validation rules
* Separate `Service` class for business logic
* `try-catch`
* `Log::error()` / `Log::info()`
* Proper JSON response
* Transaction handling
* Clean code structure
* SOLID principles

सब शामिल हैं।

---

Create a professional Laravel controller using clean architecture and best practices.

Requirements:

1. Use Laravel latest stable version syntax.
2. Create dedicated:

   * Controller
   * Form Request classes for Store and Update
   * Service class for business logic
   * Custom Validation Rule classes if needed
3. Controller should contain only request handling and response logic.
4. All database/business logic must be moved into Service classes.
5. Use dependency injection properly.
6. Use try-catch block in every controller method.
7. Use:

   * Log::info()
   * Log::warning()
   * Log::error()
     with meaningful messages.
8. Return proper JSON responses with:

   * success
   * message
   * data
   * errors
9. Use DB Transactions in service methods where required.
10. Follow:

* SOLID principles
* Clean Code
* PSR standards
* Laravel conventions

11. Add proper PHPDoc comments.
12. Validation rules must be handled inside Form Request classes only.
13. If custom validation is required, create reusable custom Rule classes.
14. Use route model binding wherever possible.
15. Add proper exception handling.
16. Use meaningful variable and method names.
17. Make code production-ready and scalable.
18. Avoid writing business logic directly inside controller.
19. Include complete file code separately for:

* Controller
* StoreRequest
* UpdateRequest
* Service
* Custom Rule (if required)

20. Add example API routes as well.
21. Use Repository pattern only if truly needed; otherwise keep architecture simple.
22. Add comments explaining why specific architecture decisions are used.

Example module name:
"Product"

Generate:

* ProductController
* StoreProductRequest
* UpdateProductRequest
* ProductService
* Custom Rules if necessary

Methods required:

* index
* store
* show
* update
* destroy

Response format must be clean, modern, and enterprise-level.
