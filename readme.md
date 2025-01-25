# Symfony app example

The app demonstrates:

- Doctrine entities, repositories
- Translates
- Locale routing
- Message dispatch
- Tests
  - Doctrine
  - Service
  - Application
- Email
- Security
  - /register
  - /login
  - /logout
  - access control to */admin* area
  - csrf manually
    - Create new category form `/admin/category/new` is protected with tocken
      - template - `templates/admin/category/new.html.twig`
      - controller - `src/Infrastructure/Controller/Admin/Category/CategoryController.php`
- Session
  - last visited article on the Home page
- Assets mapper
  - [-] Stimulus
