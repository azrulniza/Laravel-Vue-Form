# Laravel-Vue-Form

Using:

**** BE
Laravel Framework 11.30.0
PHP 8.2.18
MySQL 8.3.0


**** FE
PrimeVue : 4.0.0 
Vue : 3.4.34 
Template : Sakai Vue (4.1.0) https://github.com/primefaces/sakai-vue


Step:

➜ create a DB at local machine.
➜ rename .env_example to .env, change DB info that has been created at step above.

➜ cd be
➜ composer install
➜ php artisan migrate
➜ php artisan serve

➜ cd fe
➜ npm install
➜ npm run dev
