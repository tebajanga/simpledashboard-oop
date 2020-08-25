# Simple Dashboard PHP - OOP

#### A PHP Project to create, list users and changing password.

#### By Timothy Anthony

## Description

The SimpleDashboard project allow users to be created and listed on the home page. The user password can be updated after viewing the specific user.

The user will have the following details:
* Firstname
* Lastname
* Age
* Email
* Password
* Avatar / Image
* City
* Region
* Country

## Technologies Used

* HTML
* CSS
* PHP
* MySQL

The process of creating, listing and updating the user details is done in **OOP - Object Oriented Programming** approach.

## Setup

1. Clone the project repository.
2. Copy the cloned project folder into your web server folder.
3. Rename the file `environement-sample.php` to `environment.php` and change database connection settings.
4. Open the project in your browser.
5. Run the migration to create the `users` table by running the file `migration/create_users_table.php` on terminal or browser.

## Sample Data
You can use the sample data which have few users to experience the flow of the application.

* Import the file `migration/data/simpledashboard.sql` in your database server to create a **users** table with sample data.

## Tests
You can use `PHPUnit` to run the tests which are found in `tests` directory.

For example: `phpunit tests/SimpleDashboardTest.php`