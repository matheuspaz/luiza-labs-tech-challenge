#!/bin/bash
composer install
php artisan migrate
php artisan l5-swagger:generate
