<h1 align="center">Online Notice Board</h1>

<p align="center">
  <img src="https://img.shields.io/badge/Status-Active-success?style=flat-square" />
  <img src="https://img.shields.io/badge/Backend-PHP-blue?style=flat-square" />
  <img src="https://img.shields.io/badge/Database-PostgreSQL-lightblue?style=flat-square" />
  <img src="https://img.shields.io/badge/Frontend-HTML%2FCSS%2FJS-yellow?style=flat-square" />
</p>

<p align="center">
  <b>A digital platform for managing and viewing college or school notices — anywhere, anytime!</b>
</p>

---

## 📖 Overview

**Online Notice Board** is a web-based application built using **PHP**, **PostgreSQL**, and **HTML/CSS/JS** that allows **admins**, **teachers**, and **students** to manage and view important announcements in one place.

It replaces the traditional paper-based notice boards with an easy-to-use, secure, and modern web interface.

---

## Features

✅ **User Roles**
- **Admin:** Manage users and notices.
- **Teacher:** Create, edit, archive, and publish notices.
- **Student:** View and download notices.

✅ **Authentication System**
- Secure login & signup.
- Password reset via OTP verification.
- Role-based dashboards.

✅ **Notice Management**
- Create, view, and archive notices.
- Attach supporting documents (PDFs, images, etc.).
- Filter or search by category or date.

✅ **Responsive Interface**
- Clean and modern design.
- Works seamlessly on desktop and mobile.

✅ **Database-Driven**
- All notices, users, and logs stored in **PostgreSQL** for reliability.

---

## 🧰 Tech Stack

| Layer | Technology |
|-------|-------------|
| **Frontend** | HTML, CSS, JavaScript |
| **Backend** | PHP |
| **Database** | PostgreSQL |
| **Hosting (Recommended)** | Render / Railway / Localhost (XAMPP) |
| **Version Control** | Git & GitHub |

---

## 🖥️ Screenshots

HomePage | Student Login Page | Student Dashboard | 
<img width="1366" height="681" alt="image" src="https://github.com/user-attachments/assets/f2f5c048-2ace-4832-97f9-4c5d545f7d41" />
<img width="1366" height="685" alt="image" src="https://github.com/user-attachments/assets/2c583f7d-e1c0-4dd2-943a-f7e6bd3beba7" />
<img width="1366" height="688" alt="image" src="https://github.com/user-attachments/assets/12ff86cd-e7c3-4c91-9ed2-489c6e275048" />


---

## ⚙️ Installation & Setup (Localhost)

1. **Clone this repository**
git clone https://github.com/<your-username>/OnlineNoticeBoard.git

2.Move the project to your XAMPP htdocs folder.

3.Start Apache and PostgreSQL

4.Create the Database
Open pgAdmin
Create a new database named noticeboard
Copy the tables:
docs/database.txt

5.Edit Database Configuration
Open config.php and update:
$host = 'localhost';
$db   = 'noticeboard';
$user = 'postgres';
$pass = 'your_password';
$port = '5432';

6.Run the Project
Open your browser and go to:
http://localhost/OnlineNoticeBoard


🌐 Live Demo (if hosted)
🚀 Live Project: https://online-notice-board.onrender.com
🗂️ GitHub Repository: https://github.com/<your-username>/OnlineNoticeBoard


🧑‍💻 Author
👩‍💻 Shreya Soma
📧 somashreya14@gmail.com
🌐 LinkedIn: www.linkedin.com/in/shreyasoma

“Turning static notice boards into smart digital experiences.”

---
License:
This project is open source and available under the MIT License
<p align="center">⭐ If you like this project, give it a star on GitHub! ⭐</p> ```
