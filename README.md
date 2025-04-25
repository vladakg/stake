# Project: Distribute Funds Service

This project is a service that enables the distribution of funds to investors in campaigns based on their investments.

## First Steps After Pulling the Project

After pulling the project, follow the steps below to set up everything you need for local development and testing.

## Setup Instructions

### 1. Clone the Repository

Clone the repository to your local machine:

```bash
git clone https://github.com/your-repository-name.git
cd your-repository-name
```

### 2. Copy the .env.example file to .env

```bash
cp .env.example .env
```

### 3. Set Up Environment Variables in env file

```bash
APP_ENV – Set to testing for testing environment.

DB_CONNECTION – Set to mysql for the database connection.

DB_HOST – Database host (e.g., 127.0.0.1).

DB_PORT – Database port (e.g., 3306).

DB_DATABASE – Database name (e.g., stake_test for the test database).

DB_USERNAME – Database username (e.g., root).

DB_PASSWORD – Database password (e.g., root).

Other services like MAIL_MAILER, QUEUE_CONNECTION, etc., can be configured as required.
```
### 4. Install Dependencies
```bash
composer install
```

### 5. Set Up the Database
```bash
php artisan migrate

If you are setting up a testing environment, you can use:
php artisan migrate --env=testing
```

### 6. Seed the Database (Optional)
```bash
php artisan db:seed

Alternatively, if you need to refresh the database with both migrations and seeders:
php artisan migrate:refresh --seed
```

### 7. Run Tests
```bash
php artisan test
```

### 8. Additional Information
Make sure your local environment is properly configured to work with the database.

### 9. Run Funds Distribution Command

Once your setup is complete and you have investments and campaigns in your database, you can trigger the distribution of funds to investors by running the following Artisan command:

```bash
php artisan funds:distribute {property_id} {amount}
