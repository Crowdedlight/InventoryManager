<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Setup

### Izettle api

Be sure to add Izettle API Details in .env file aswell as add the scheduler to servers cron jobs:

```
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```

### Laravel-echo-server
Install echo server globally
```
npm install -G laravel-echo-server
```

Configure ``laravel-echo-server.json`` with needed settings

Make a supervisor configuration that always runs the laravel-echo-server.
```
[program:echo-server]
directory=/home/vagrant/path/to/your/project
command=/usr/bin/laravel-echo-server start
autostart=true
autorestart=true
user=vagrant
redirect_stderr=true
stdout_logfile=/home/vagrant/path/to/your/project/storage/logs/echoserver.log
```

### Redis
Configure Redis settings in ``.env``

### npm for laravel-echo-server
Run
```npm install```
for dependencies.

Run
```npm run production```
(Or ``npm run dev`` for development)

### Composer - Laravel
```composer install```

### Laravel
```php artisan migrate```
```php artisan db:seed```
