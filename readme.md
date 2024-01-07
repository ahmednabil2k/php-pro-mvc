# PHP-PRO-MVC

This is a tiny php framework inspired from laravel, That is for learning reasons only, 
The purpose of this framework is just to apply the concepts and techniques related to OOP, Clean code and abstraction.
The idea was not reinvent the wheel but have the ability know how can we build wheels, How even wheels work.


# Features and Functionalities

- Middlewares
- Service Providers
- Dependency Injection
- Routing
- Database
  - Database Builder
    - Supports (Mysql) for now
  - ORM
- Cache
  - Supports(InMemory, FileCache)
- Config
- Console Commands
  - ```php command.php {command name} {args if any}```
- FileSystem
  - Supports(Local Filesystem)
- Sessions
  - Supports (Native Sessions)
- Queues
  - Supports(Database)
- Validations
- Views
  - supporting (Caching views, Directives, Macros)
- Helpers
- Exception Handling


To run the application:

Install Composer dependency
```
composer install
```

Run migration if needed
```
php command.php artisan migrate
```

Serve the app
```
php -S 127.0.0.1:8000 -t public
```

# (Hint) This code might have some errors as it is not fully tested

