# laravel-blog-api
blog api using laravel

### How to Run:
1. Clone Project - 

```bash
git clone https://github.com/syawqy/laravel-blog-api.git
```
1. Go to the project drectory by `cd laravel-blog-api` & Run the
2. Create `.env` file & Copy `.env.example` file to `.env` file
3. Create a database called - `laravel`.
4. Install composer packages - `composer install`.
5. Now migrate and seed database to complete whole project setup by running this-
``` bash
php artisan migrate:refresh 
```
6. Install passport
``` bash
php artisan passport:install
```
7. Run the server -
``` bash
php artisan serve
```
8. Open Browser -
http://127.0.0.1:8000