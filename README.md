🍽️ College Canteen Management System

A modern College Canteen Web Application built to simplify food ordering, menu management, and order tracking for students and administrators.

This project provides a digital solution for college canteens where students can browse menus, place orders, and manage their cart — reducing waiting time and improving canteen efficiency.

🚀 Features
👨‍🎓 Student Features

🔐 User Registration & Login

📋 View Canteen Menu

🛒 Add Items to Cart

✅ Place Orders Easily

📦 View Order History

🚪 Secure Logout

👨‍💼 Admin Features (Extendable)

Manage food items

Update menu dynamically

Track customer orders

Monitor usage

🧱 Tech Stack
Layer	Technology
Frontend	HTML, CSS
Backend	PHP
Database	MySQL
Server	XAMPP (Apache + MySQL)
📁 Project Structure
college_canteen/
│
├── index.php          # Home page
├── login.php          # Login page
├── register.php       # User registration
├── menu.php           # Food menu
├── cart.php           # Cart system
├── orders.php         # Order history
├── logout.php         # Logout logic
│
├── config.php         # Database connection
│
├── css/
│   └── style.css      # Styling
│
└── database.sql       # Database schema
⚙️ Installation & Setup
✅ Prerequisites

Make sure you have installed:

XAMPP

Web Browser (Chrome recommended)

🔧 Step 1 — Move Project

Copy project folder into:

C:\xampp\htdocs\

Example:

C:\xampp\htdocs\college_canteen
🗄️ Step 2 — Setup Database

Start XAMPP Control Panel

Start:

Apache ✅

MySQL ✅

Open:

http://localhost/phpmyadmin

Create database:

college_canteen

Import:

database.sql
▶️ Step 3 — Run Project

Open browser:

http://localhost/canteenu/canteen/
🎉 Application is now running locally.

🔐 Database Configuration

Edit config.php if needed:

$host = "localhost";
$user = "root";
$password = "";
$database = "college_canteen";
🧠 How It Works

User registers an account.

Logs into the system.

Views available food items.

Adds items to cart.

Places order.

Orders are stored in MySQL database.

📸 Screenshots (Add Later)

You can add screenshots here:

/screenshots/home.png
/screenshots/menu.png
/screenshots/cart.png
🌟 Future Improvements

✅ Online payment integration

📱 Mobile responsive UI

🔔 Order notifications

👨‍💼 Admin dashboard

📊 Analytics & reports

🍔 Live order status tracking

🤝 Contributing

Contributions are welcome!

Fork the repository

Create feature branch

Commit changes

Open Pull Request

👩‍💻 Author

Komali

GitHub:
👉 https://github.com/Komali-2593
