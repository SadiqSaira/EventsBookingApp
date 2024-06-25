# Event Booking App
* [About Event Booking App](#about-event-booking-app)
* [Current Features](#current-features)
* [Technologies Used](#technologies-used)
* [Installation](#Installation)
* [Testing](#testing)
* [Issues With this Feature](#issues-with-this-feature)
## About Event Booking App
The application allows customers to book tickets for events, providing details such as event name, start and end date and time, city, country, ticket allocation, and maximum tickets per customer. Customers can filter events by country and start date, then select an event and enter their first name, last name, email address, and desired number of tickets. Upon booking, a confirmation screen is displayed.

## Current Features
As of now, the application provides the following functionalities:
### 1: Listing Events
Customers can visit the application via http://127.0.0.1:8000/events to see a list of all available events.

### 2: Filtering Events

##### 1: By Country
Customers can filter events based on the country in which the events are being held.
##### 2: By Date
* **Date Range:** 
  Customers can select a range of dates to see all events whose start date falls within that range.
* **Single Date:** 
  Customers can select a single date to see all events that occur on or after the selected date.
### 3: Event Booking Feature

##### Overview
Customers can now click on an event to view its details.
A new screen opens where they can enter their first name, last name, email, and the number of tickets they wish to book.

##### Booking Process
1. **Form Data Entry**:
    Customers enter their first name, last name, email, and the number of tickets they wish to book.
2. **Form Data Validation**:
    Upon clicking "Book Event Tickets", several checks are performed on the form data:
        1. Ensuring the requested number of tickets is within the allowed limit.
        2. Verifying that the requested number of tickets does not exceed the remaining tickets available for the event.

3. **Customer Data Handling**:
    If both conditions are met, and if the customer is new, their details are added; otherwise, existing details are updated.

4. **Booking and Ticket Allocation**:
    1: Booking details are saved.
    2: The booked number of tickets is deducted from the total event ticket count.

##### Post-Booking

After a successful booking, customers are redirected to the main events page with a confirmation message of their successful booking.


## Technologies Used
Event Booking App uses a number of open source projects to work properly
| Technologies | Version |
| ------ | ------ |
| PHP | PHP 8.3.6 |
| MySQL | MySQL 8.0.37 |
| Composer | Composer 2.7.3 |
| Visual Studio Code | Version 1.90|
| Laravel | Laravel Framework 11.10.0 |
| Inertia.js Vue Adapter (Vue 3) | @inertiajs/vue3 ^1.0.0 |
| Vue | Vue.js 3.4.0 |

 

## Installation
Follow these steps to get the Laravel project up and running on your local machine.
### Prerequisites
Install [PHP 8.3.6](https://www.php.net/downloads.php), [MySQL 8.0.37](https://dev.mysql.com/downloads/installer/), [Composer](https://getcomposer.org/), [Visual Studio Code](https://code.visualstudio.com/) and  [Laravel Framework 11.10.0](https://laravel.com/) according to Windows/Mac..

### Installation Instructions / Project Setup
##### 1: Clone the Repository
Clone the repository from GitHub to your local machine:

```sh
git clone https://github.com/SadiqSaira/EventsBookingApp.git
cd your-laravel-project
```
##### 2: Install Dependencies
Use Composer to install the PHP dependencies and npm to install JavaScript dependencies.
```sh
composer install
npm install
npm run build
```
##### 3: Set Up Environment Variables
Rename the .env.example file to .env and change the following  variables to match your local environment setup.
```sh
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```
Generate a new application key
```sh
php artisan key:generate
```
##### 4: Set Up Database
Run the migrations to set up your database schema and seeders to populate the database with initial data.
```sh
php artisan migrate
php artisan db:seed
```
## Integration Testing
To run Integration tests we need to set the testing environment first.
### Testing Environment.  
To create a separate database for testing and configure PHPUnit to use this database follow these steps. 

##### 1.Create a Separate Testing Database:
    Create a seperate testing database for testing.
##### 2.Create `.env.testing` File:
1: In the root of your project directory, create a new file named `.env.testing`. 
2: Copy the contents of your `.env` file into `.env.testing`. 
3: Change the `DB_DATABASE` configuration to your test database: 

      ```ini 
      DB_DATABASE=EventBookingApp_testing 
      ``` 
##### 3.Configure PHPUnit to Use the Test Database:
PHPUnit will automatically use the `.env.testing` file when running tests. 
##### 4.Run Migrations and Seeders for the Test Database:
1: Run migrations for the test database using the following command: 
      ```sh 
      php artisan migrate --env=testing 
      ``` 
2: To seed the test database, use: 
      ```sh 
      php artisan db:seed --env=testing 
      ``` 
