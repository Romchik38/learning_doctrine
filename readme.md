# Symfony app example

The app demonstrates:

- Doctrine entities, repositories
- Translates
- Locale routing
- Message dispatch
- Message in a browser
- Email Notifocation
- Chat (Telegram) Notifocation
- Tests
- Email
- Security
- Session
- Assets mapper

## Message dispatch

Sync message handling:

- message - `src/Message/Homepage.php`
- handler - `src/MessageHandler/HomepageHandler.php`
- dispatch - `src/Infrastructure/Controller/Home/HomeController.php`

## Message in a browser

- dispatched from `src/Infrastructure/Controller/Admin/Category/CategoryController.php`
- rendered in `templates/admin.html.twig`

## Email Notifocation

- dispatched from `src/Infrastructure/Controller/Admin/Category/CategoryController.php` func `delete`
- configured default sender `config/packages/mailer.yaml`

## Chat (Telegram) Notifocation

- config `config/packages/notifier.yaml`
- installed `composer require symfony/telegram-notifier`
- added a DSN to env.dev.local `TELEGRAM_DSN=telegram://TOKEN@default?channel=CHAT_ID`
- dispathecd from `src/Infrastructure/Controller/Admin/Article/ArticleController.php`

## Tests

- Doctrine
- Service
- Application

## Security

- /register
- /login
- /logout
- access control to */admin* area
- csrf manually
  - Create new category form `/admin/category/new` is protected with tocken
    - template - `templates/admin/category/new.html.twig`
    - controller - `src/Infrastructure/Controller/Admin/Category/CategoryController.php`

## Session

- last visited article on the Home page

## Assets mapper

- Stimulus
