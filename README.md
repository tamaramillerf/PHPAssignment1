# PHPAssignment1 - Task Manager 

## Requirements
- XAMPP (Apache + MySQL/MariaDB)
- PHP 8+
- phpMyAdmin

## Setup
1. Copy the project folder into:
   - Windows: xampp/htdocs/PHPAssignment1
   - Mac: /Applications/XAMPP/htdocs/PHPAssignment1

2. Start Apache + MySQL in XAMPP

3. Open phpMyAdmin and run the SQL file:
   - Import `phpassignment1.sql`
   - This creates `categories` and `tasks` tables with a foreign key

4. Update database connection if needed:
   - `database.php` uses database name `phpassignment1`

## Features
- Related tables: tasks + categories (foreign key)
- Task list uses JOIN query
- Add task with image upload
- Update task including optional image replace
- Task details page
- Delete task (optionally deletes image file)

## Run
http://localhost/PHPAssignment1/index.php
