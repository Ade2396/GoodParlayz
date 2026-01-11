# GoodParlayz

GoodParlayz is a sports betting style web application built with PHP that allows users to create an account, log in, manage their profile, add parlays, and save favorites through a simple dashboard interface.

This project was developed as part of COSC 459 (Database Design), with a focus on relational database design, user authentication, and PHP MySQL integration.

## Tech Stack
1. PHP
2. HTML
3. CSS
4. JavaScript
5. MySQL
6. MAMP (local development)

## Features
1. User signup and login
2. Dashboard after authentication
3. Add parlay functionality
4. Favorites page
5. Profile viewing and updating
6. Secure logout

## Project Structure
1. login.html and login.php handle authentication
2. signup.html and signup.php handle account creation
3. dashboard.php serves as the main user hub
4. add_parlay.php handles creating parlays
5. favorites.php manages saved favorites
6. profile.php, update_profile.php, and delete_profile.php handle user account management
7. assets folder stores static assets
8. style.css handles application styling

## How to Run Locally (MAMP)
1. Install MAMP
2. Place the project folder in  
   /Applications/MAMP/htdocs/GoodParlayz
3. Start MAMP and run the servers
4. Create a MySQL database
5. Update database credentials in config.php
6. Open the project using the MAMP localhost URL

## Database Setup
This repository does not include real database credentials.

To run locally:
1. Create a MySQL database (example name: goodparlayz)
2. Import your SQL schema
3. Update config.php with your local database credentials

## Notes
1. config.php is ignored to prevent leaking credentials
2. Screenshots will be added later

## Author
Adedeji Ayokanmi

