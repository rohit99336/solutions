Create a complete Laravel Resource module for "Customer" using clean architecture, scalable structure, and Laravel best practices.

Generate the complete production-ready code step-by-step for the following:

1. Migration
2. Model
3. Resource Controller
4. Store Request
5. Update Request
6. Service Class
7. Custom Validation Rules (if required)
8. API/Web Routes
9. Resource Views (if web based)
10. Form UI using Bootstrap 5
11. Proper Logging and Exception Handling
12. Clean JSON/Web Responses

Requirements:

## General Rules

* Use latest Laravel stable version syntax.
* Follow:

  * SOLID principles
  * Clean Architecture
  * PSR standards
  * Laravel conventions
* Keep controller lightweight.
* Move all business logic to Service class.
* Use dependency injection everywhere possible.
* Use route model binding.
* Add proper PHPDoc comments.
* Use meaningful method and variable names.
* Make code scalable and enterprise-ready.

---

## Migration Requirements

Generate migration for `customers` table with fields like:

* id
* name
* email
* phone
* gender
* date_of_birth
* address
* city
* state
* pincode
* status
* profile_photo
* created_by
* timestamps
* softDeletes

Requirements:

* Add proper indexes.
* Add unique constraints where required.
* Use enum/status constants properly.

---

## Model Requirements

Create `Customer` model with:

* fillable fields
* casts
* accessors/mutators if required
* relationships
* scopes
* status constants
* soft delete support

Add:

* Active scope
* Search scope

---

## Controller Requirements

Create `CustomerController` as Resource Controller with methods:

* index
* create
* store
* show
* edit
* update
* destroy

Controller Rules:

* No business logic inside controller.
* Use try-catch in every method.
* Use:

  * Log::info()
  * Log::warning()
  * Log::error()
* Return proper responses.
* Use redirect with flash messages for web routes.
* Handle exceptions professionally.

---

## Validation Requirements

Create:

* StoreCustomerRequest
* UpdateCustomerRequest

Validation must include:

* custom messages
* authorize() method
* reusable validation rules
* conditional validations if needed

If complex validation is required:
Create reusable Custom Rule classes.

---

## Service Class Requirements

Create `CustomerService` class.

Move all logic here:

* create customer
* update customer
* delete customer
* upload profile image
* status handling

Requirements:

* Use DB Transactions
* Throw meaningful exceptions
* Handle file uploads properly
* Reusable methods
* Clean architecture

---

## View Requirements

Generate Bootstrap 5 based Blade files:

* index.blade.php
* create.blade.php
* edit.blade.php
* show.blade.php
* form.blade.php

UI Requirements:

* Responsive design
* Proper validation error display
* Bootstrap 5 components
* Feather/Remix icons
* Clean admin panel design
* Reusable form partials

---

## Route Requirements

Generate proper:

* web.php routes
* resource routes
* middleware usage

---

## Logging & Error Handling

Every important action should use:

* Log::info()
* Log::warning()
* Log::error()

Examples:

* Customer created
* Customer updated
* Validation failed
* File upload failed
* Exception occurred

---

## Response Format

Generate complete code file-by-file in proper sequence.

For every file:

* Mention file path
* Mention purpose
* Then provide full code

Keep code clean, readable, and production-ready.
