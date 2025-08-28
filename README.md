# MDM Project

## Overview

This is a robust and modern Master Data Management system built with Laravel and Livewire for managing master items, master brands, master categories, and users.

## Technologies

* **Livewire 3**: The latest version of Livewire, a powerful tool for building dynamic, reactive, frontend UIs using PHP.
* **Laravel Volt**: An optional feature that allows for the use of Volt, a templating engine for Laravel.
* **TypeScript**: A statically typed language that helps catch errors early and improve code maintainability.
* **Tailwind**: A popular utility-first CSS framework for building custom user interfaces.
* **Flux UI**: A component library for building consistent and reusable UI components.
* **Mary UI**: A livewire component library for building consistent and reusable UI components.

## Project Structure

The project is structured into the following main directories:

* **app**: The main application directory, containing the Livewire components, models, and controllers.
* **config**: The configuration directory, containing files for setting up the application's environment, logging, and other settings.
* **database**: The database directory, containing the migration files and seeders.
* **public**: The public directory, containing the application's entry point and public assets.
* **resources**: The resources directory, containing the application's views, languages, and other resources.
* **routes**: The routes directory, containing the application's route definitions.

## Key Components

* **MasterItemComponent**: A Livewire component for managing master items.
* **MasterBrandComponent**: A Livewire component for managing master brands.
* **MasterCategoryComponent**: A Livewire component for managing master categories.
* **UserComponent**: A Livewire component for managing users.
* **DashboardComponent**: A Livewire component for the dashboard.

## Authentication

The application uses the default authentication system provided by Laravel.

## Dependencies

The application depends on the following packages:

* **laravel/framework**: The Laravel framework.
* **livewire/livewire**: The Livewire package.
* **maatwebsite/excel**: The Excel package for exporting data.
* **robsontenorio/mary**: The Mary package for toast notifications.

## License

The Laravel + Livewire starter kit is open-sourced software licensed under the MIT license.

---

**Requirements:**  
- PHP >= 8.1  
- Composer  
- Node.js & npm  
- SQLite (or configure another database in `.env` as per your needs)


## Getting Started

### 1. Clone the Repository

git clone https://github.com/mohamedzumry/mdm.git

cd mdm


### 2. Install PHP Dependencies
Make sure you have [Composer](https://getcomposer.org/) installed.

composer install

### 3. Install Node.js Dependencies
Make sure you have [Node.js](https://nodejs.org/) and [npm](https://www.npmjs.com/) installed.

npm install

### 4. Copy and Configure Environment File

cp .env.example .env

Edit `.env` and set your database and other environment variables.

### 5. Generate Application Key

php artisan key:generate

### 6. Run Migrations and Seeders

php artisan migrate


### 7. Build Frontend Assets

npm run build (already built in the project, you can rebuilt it by this command)

Or for development:

npm run dev


### 8. Start the Local Development Server

php artisan serve

Visit [http://localhost:8000](http://localhost:8000) in your browser.

---

## Setup Application

1. After all the above steps, you can start the application and navigate to the [http://localhost:8000](http://localhost:8000) in your browser.

2. You can start by adding a user(first admin must be set by making is_admin = 1 in the database).

3. Then you can start adding master items, master brands, master categories, and users.

4. Create some normal users and start using the application.

5. Now all data can be managed by admins while users can only manage their own data.

6. You can serach, filter and also export items data in excel, CSV or PDF format.

---

I hope this documentation provides a clear and concise overview of the project and its features for the reviewing company.