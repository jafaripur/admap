Advertisement over map (Admap)
===================================

Application used Yii 2 Advanced Application Template. This structure is best for
developing complex Web applications with multiple tiers.

The template includes three tiers: front end, back end, and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.


DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
tests                    contains various tests for the advanced application
    codeception/         contains tests developed with Codeception PHP Testing Framework
```


REQUIREMENTS
------------

The minimum requirement by this application template that your Web server supports PHP 5.4.0.


INSTALLATION
------------

### Update composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

Before updating the composer of the project run this command to install assept-plugin

~~~
php composer.phar global require "fxp/composer-asset-plugin:*"
~~~

You also need to add the following code to your project's composer.json file:

```
"extra": {
    "asset-installer-paths": {
        "npm-asset-library": "vendor/npm",
        "bower-asset-library": "vendor/bower"
    }
}
```

And in the last run composer update in root of the project:

~~~
php composer.phar update
~~~

### Migration

For migrate database just use this command and all needed table and data generate:

~~~
php yii migrate
~~~

### Translation

For extract the translation run this command each time to generate related files to translate:

~~~
php yii message /path/to/admap-application/common/messages/config.php
~~~

generated translation files exist over here `/path/to/admap-application/common/messages/{locale}`


GETTING STARTED
---------------

THis project is good startup project to improve your knowledge in Yii2.

1. User registration, login to website. Also use social login and registration.
2. Adding, editing the advertisement. Gallery, attachment.
3. Show the advertisement in both of grid and map. Map section use server side clustering marker.
4. Advanced filter to search.
5. Use RBAC for user controlling.
6. Project most section use AJAX.

- for frontend `/path/to/admap-application/frontend/web/`
- for backend `/path/to/admap-application/backend/web/`

To login into the application, you need to first sign up, with any of your email address, username and password.
Then, you can login into the application with same username and password at any time.

For administrator access use this credential information:

~~~
Username: admin
Password: 123456
~~~

For checking demo visit [admap.ir](http://admap.ir)