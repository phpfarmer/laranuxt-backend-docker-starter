[Go Back](Installation.md)

## Installation Example

Below is a step-by-step guide to installing this project. For this example, we'll name our project "ProjectX" and use "projectx" as the prefix for our Docker containers. Follow these steps to successfully install the application with the new project name and corresponding prefixes.

1. Navigate to the projectx directory.
2. Copy the `.env.example` file to `.env` by running `cp .env.example .env`.
3. Edit the `.env` file to set `APP_NAME`, `DB_PASSWORD`, and change the `APP_URL` port to `8381`.
4. Navigate to the docker directory: `cd docker`.
5. Copy the `.env.example` file to `.env` by running `cp .env.example .env`.
6. Ensure that the `MYSQL_DATABASE` credentials in the docker `.env` file match those in the application `.env` file.
7. Open `docker-compose.yaml` and verify that all ports are correct and not used by other services. Here are some example changes:
   1. Change `webserver` port from `8281` to `8381`.
   2. Change `database` port from `8299` to `8399`.
   3. Change `phpMyAdmin` port from `8282` to `8382`.
   4. Change `cache` port from `6279` to `6373` and ensure the `REDIS_PORT` in the application `.env` file matches.
   5. Change `Redis Commander` port from `8283` to `8383`.
   6. Change `mail` port from `8284` to `8384`.
8. Find and replace all occurrences of `laranuxt` with `projectx` in a case-sensitive manner across the project directory.
9. Find and replace all occurrences of `laranuxt` with `projectx` in a case-insensitive manner across the project directory.
10. Manually replace all references to `Laranuxt` or the application name, including:
    1. Application Name
    2. Readme contents
    3. GitHub repository URL
11. Update the `License` section in the `README.md` file.
12. Finally, run `docker-compose up --build`.
13. You should now be able to view all the running applications:
    1. Your ProjectX backend application will be available at `http://127.0.0.1:8381`.
    2. Access PHPMyAdmin at `http://localhost:8382` for database management.
    3. Access the SMTP Mail Server at `http://localhost:8384` for email.
    4. Access Redis Commander at `http://localhost:8383` for Redis management.

This guide should help you set up your project correctly with the new name and port configurations.

If everything is done, make the initial git commit!





