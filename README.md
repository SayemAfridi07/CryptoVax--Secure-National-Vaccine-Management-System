##  National Vaccine Management

This web application is intended to make the national vaccination operations smooth and automated where possible in a convenient way.

### System Dependency
-   PHP 8.2^
    - PHP extensions
        -   gd
        -   mbstring
        -   zip
        -   Other missing dependencies will be promted when `composer install` command is run
-   mysql 8.0^
-   node 18.20.3^
-   npm 10.7.0^

### Installation Steps
*Run the following commands in the project directory*
-   `cp .env.example .env` *add the db and email credentials in the .env file*
-   `composer install`
-   `php artisan migrate`
-   `php artisan db:seed`
-   `php artisan key:generate`
-   `npm install`
-   `npm run dev` or `npm run build` for production

### Testing accounts credentials
-   Test Admin
    -   Email:  ``admin@test.com``
    -   Password:   ``password``
-   Test Operator
    -   Email: ``operator@test.com``
    -   Password:   ``password``
-   Applicant accounts can be registered right away and no verification needed for services

### Features
-   Admin panel for the higher authority
    -   Admin manages **Vaccine Centers**
    -   Admin supplies vaccine(vails) to the **centers**
    -   Admin assigns and authorizes **Healthcare Practitioners**
-   Vaccine Candidates utilize the system to
    -   Register on the platform
    -   Apply for the vaccines
    -   Set appointment
    -   Downloads **vaccine cards** and **certificates**