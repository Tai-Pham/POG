# CS 160 - Team Piplup
Repository for code used in our POG app in CS160.

# Internals
**Dependencies:**
- XAMPP: https://www.apachefriends.org/index.html

**File Setup:**
1. You can download or pull the repository from the master branch onto your machine.
2. Locate the htdocs folder inside the XAMPP folder.
3. Drag the all the PHP files into the htdocs folder.
4. Start up XAMPP through the executable and allow Apache and MySQL to start.
5. Type in `http://localhost/phpmyadmin/` inside your browser and log into MySQL.
6. Create a database named `Pog` and create the following tables specified by the SQL files that you have just downloaded.
7. Type in `http://localhost/poglogin.php` inside your browser and the web application should start up.
8. When finished, do not forget to close XAMPP.

# User Manual
**Link:** https://docs.google.com/document/d/1OPadbnPe-dor-21CAF0QjbKL2YxxGbZ11wE-yFP5kHg/edit?usp=sharing

# Docker Setup (Only partially working)
**Instructions to set up Docker container**
- cd into the docker folder and run `docker build -t pog .`
- Let the docker build complete and then run: 
  - `docker run --name pogrun -d -p 8000:80 --mount type=bind,source="$(pwd)/pog",target=/var/www/html pog`
 - This will have the POG website available at `localhost:8000` and it will bind the "pog" folder (containing all necessary php files) to the /var/www/html folder, which will host the site.
    - The reason why the docker container is only partially working is that we could not figure out why the PHP files could not "see" or access the other PHP files, even when they are in the same directory. Because of this, the program is not in a usable state in the docker container unfortunately. However, the POG website will perform perfectly if downloaded to one's machine and XAMPP or similar alternatives are configured and the "Internal" instructions are followed.

# Automated Frontend Test Cases (Uses Cypress)
- Register Tests:
  - Tests username input, all other fields blank (should succeed if error message is shown)
  - Tests all fields input correctly (should succeed if brought back to login page)
- Login Tests:
  - Tests login of just-created user (should succeed if brought to home page)
  
# Automated Backend API Test Cases (Uses Postman)
- Register Tests:
  - Tests no username input, all fields blank (should succeed if response code is 503 and message returned is "Please fill out a username.")
  - Tests no password input, all other fields filled (should succeed if response code is 503 and message returned is "Please fill out a password.")
  - Tests an ideal input where all fields are correctly filled (should succeed if response code is 201 and message returned is "User creation successful.")
