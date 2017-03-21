![Screenshot](/screenshot.jpg?raw=true "Screenshot")

# Stores database and API

The purpose of this application was to create a simple project based on Laravel framework (PHP). This project is using all the basic and well known features of Laravel framework as well as MVC principles including:

- Developed using [Homestead](https://laravel.com/docs/5.4/homestead) in Vagrant
- Blade template engine
- Eloquent ORM
- Laravel Validator
- All custom code contains comments
- Usage of Controllers
- Usage of Routes
- Usage of file storage
- Project structure was made using Laravel installer

### Frontend

The theme is built using elixir ans scss. It's using Twitter Bootstrap library. Blade templates are split into multiple files.

### Use Case

- Application provides 3 API endpoints that return JSON data from database
    - /stores - return all stores
    - /stores/storenumber - return random store
    - /stores/storenumber/810 - return store by ID
- Users can upload data files to database in various ways
- All the uploaded data is validated before save

### Installation

- Recommended development environment is [Homestead](https://laravel.com/docs/5.4/homestead)
- `.env` file is required. You can copy `.env.example` (the one used by me)
- `composer install` for required packages
- `npm install` for required dependencies
- `php artisan migrate` to create the database
- `php artisan storage:link` to setup storage folder

### Important custom files

