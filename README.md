# Trips & Bookings Application
### Laravel-Based API to manage trips and make bookings by users.
The application allows users to explore and book travel trips. Admin users can manage trips, including creating,
updating, and deleting travel options. Regular users can view available trips and book seats for their desired
destinations. The system ensures bookings are validated based on trip availability and date constraints.

## What it achieves:

* Trips and Bookings CRUD
* Authentication and Role-Based Access via Middleware
* Critical action logging
* Trips search and filters with pagination
* Unit ant Feature tests for bookings and trips creation
* Automated Job to update trip status to "Completed" once the end_date is passed
* Factories and Seeders to easily seed the database during development

### The Postman api collection was added to the repo for testing the app
