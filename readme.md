# Project Setup Guide

Welcome to the **WowManageApp** project! Follow the steps below to set up the project on your local machine.

## Requirements

Before starting, ensure you have the following installed:

1. **PHP**: Version 8.1 or higher.  
2. **Composer**: Dependency manager for PHP.  
3. **Relational Database**: MySQL or PostgreSQL (ensure the database server is running).  
4. **Symfony CLI**

## Setup Instructions

1. **Clone the Repository**  
    Clone the project repository to your local machine:  
    ```bash
    git clone <repository-url>
    cd wowmanageapp
    ```

2. **Install Dependencies**  
    Run the following command to install all required dependencies:  
    ```bash
    composer install
    ```

3. **Configure Environment**  
    Copy the `.env` file and configure your database credentials:  
    ```bash
    cp .env .env.local
    ```
    Update the `DATABASE_URL` in `.env.local` to match your database configuration.

4. **Run Database Migrations**  
    Execute the migrations to set up the database schema:  
    ```bash
    php bin/console doctrine:migrations:migrate
    ```

5. **Load Fixtures (Optional)**  
    If you want to populate the database with sample data, run:  
    ```bash
    php bin/console doctrine:fixtures:load
    ```

## Running the Application

Start the Symfony development server:  
```bash
symfony server:start
```

Access the application in your browser at `http://127.0.0.1:8000`.

## Troubleshooting

- Ensure all requirements are installed and properly configured.  
- Check the Symfony logs in `var/log/` for any errors.  

Enjoy working on **WowManageApp**!  