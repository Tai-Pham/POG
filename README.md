# CS 160 - Team Piplup
Repository for code used in our POG app in CS160.

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

# Internals
**Dependencies:**
- XAMPP: https://www.apachefriends.org/index.html

**File Setup:**
1. You can download or pull the repository from the master branch onto your machine.
2. Locate the htdocs folder inside the XAMPP folder.
3. Drag the all the PHP files into the htdocs folder.
4. Type in `http://localhost/phpmyadmin/` inside your browser and log into MySQL.
5. Create a database named `Pog` and create the following tables specified by the SQL files that you have just downloaded.
6. Type in `http://localhost/poglogin.php` inside your browser and the web application should start up.
