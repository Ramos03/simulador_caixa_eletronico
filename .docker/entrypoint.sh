#!/bin/bash

cd /app  

php artisan config:clear
php artisan cache:clear 

php artisan migrate --env=production --force -n
php artisan serve --host=0.0.0.0 --port=8000