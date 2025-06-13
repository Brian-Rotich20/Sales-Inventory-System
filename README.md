📦 PHP Inventory System
A simple and lightweight inventory management system designed to track products, Categories, Customers, and Sales.

🔹 Features
✅ Product Management:
Add, view, and track products in your inventory.

✅ Category Management:
Classify products into Categories.

✅ Customer Management:
Add and view customer details.

✅ Sales Recording:
Record transactions by selecting products and customers.

✅ Reporting:
View total sales, total products, and total Categories.

🔹 Tech Stack
Language: PHP

Database: MySQL

Web Server: Apache (XAMPP or WAMP)

🔹 Installation
1️⃣ Clone this repository:

bash
Copy
Edit
git clone <your-repo-url>
2️⃣ Create a MySQL database (e.g. inventory_db) and import the inventory_db.sql file.

3️⃣ Edit config/db.php to match your database credentials:

php
Copy
Edit
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory_db";
4️⃣ Start Apache and MySQL (using XAMPP or WAMP).

5️⃣ Access the application in your browser:

arduino
Copy
Edit
http://localhost/php-inventory/
🔹 File Structure (simplified)
arduino
Copy
Edit
php-inventory/
│ ├─ config/
│ ├─ products/
│ ├─ css/
│ ├─ includes/
│ ├─ index.php
🔹 Contribution
Contributions are warmly welcomed!
Fork this repository, make your modifications, and submit a pull request.

🔹 Credits
Developer – Your Name
Framework – Pure PHP
Database – MySQL
