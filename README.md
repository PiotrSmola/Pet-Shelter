# Pet Shelter Management System

## Final project in the course Web Applications.

## Project Overview
The **Pet Shelter Management System** is a web application designed to improve the management of animal shelters. It allows shelter staff and administrators to efficiently manage animals, adoptions, and vaccinations. The application also provides features for potential adopters to browse animals, register, and submit adoption requests.

---

## Key Features

### For Administrators
- **Animal Management:** Add, edit, and delete animal records.
- **Adoption Management:** Oversee adoption processes, including reservations and status changes.
- **Vaccination Tracking:** Record and manage animal vaccination histories.
- **User Management:** Edit user profiles and assign roles (e.g., admin, customer).
- **Automation:** Automate availability updates and track adoption statuses.

### For Users
- **Animal Browsing:** Search for animals using filters like age, weight, and species.
- **Adoption Requests:** Submit and manage adoption or reservation requests.
- **User Profiles:** View and update personal details, including adoption history.

---

## Technologies Used
- **Framework:** Laravel 11.x
- **Backend:** PHP + Laravel
- **Frontend:** Blade templates, Bootstrap 5, CSS, JavaScript
- **Database:** MySQL

---

## Getting Started

### Prerequisites
1. **XAMPP:** Install a version that includes PHP 8.x.
2. **Composer:** Required for dependency management.
3. **Visual Studio Code:** Recommended for development.

### Installation
1. Clone the repository to your local machine.
2. Navigate to the project directory and configure the `.env` file with your database credentials.
3. Run the `start.bat` script

### Running with Docker
The only requirement is [Docker Desktop](https://www.docker.com/products/docker-desktop/) (or Docker Engine with the Compose plugin).

1. Clone the repository.
2. Run the setup script from the repository root:
   - Windows: `docker-setup.bat`
   - Linux / macOS: `./docker-setup.sh`

The script builds the image, starts the containers, creates and seeds the database on the first run, and opens the app in your browser.

| Service    | URL                     |
|------------|-------------------------|
| App        | http://localhost:8000   |
| phpMyAdmin | http://localhost:8081   |
| MySQL      | `localhost:3307` (user `petshelter`, password `petshelter`) |

Admin account from the seeders: `john.doe@example.com` / `Haslo12345@`.

Ports can be changed with the `APP_PORT`, `PMA_PORT` and `DB_PORT` environment variables. Containers restart automatically with Docker. Useful commands:
- `docker compose down` stops the app (data is kept in Docker volumes)
- `docker compose down -v` stops the app and deletes the database and uploaded photos
- `docker compose up -d --build` rebuilds after code changes

## Functionality Highlights
- **Responsive Design:** Optimized for various screen sizes using Bootstrap.
- **Role-Based Access:** Separate functionalities for administrators and users.
- **Data Validation:** Ensures secure and consistent data handling.
- **Dynamic Filters:** Flexible animal search and sorting features.

