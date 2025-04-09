# QuartoIdeal – University Room Booking System (PHP + MySQL)

## 📌 Overview

QuartoIdeal is a web-based platform designed to help university students find and book rooms from a selection of available hotels. Built using **PHP**, **CSS**, and **MySQL** (via **phpMyAdmin**), the system includes full user registration, booking management, and an admin interface to manage hotels, rooms, and user roles.

This project was developed using **XAMPP** for local development and is intended as a working prototype for a basic hotel/room management system tailored for academic housing or university-related travel.

## 🧠 Key Features

- 🔐 **User Authentication**
  - Register / Login / Logout
  - Session-based access control
- 🏨 **Hotel Management**
  - Add / Edit / Delete hotels
- 🛏️ **Room Management**
  - Add / Edit / Delete rooms
  - Room assignment and availability
- 🗓️ **Booking System**
  - Book / Cancel / Modify reservations
- 👮 **Admin Panel**
  - View all users
  - Update user roles
  - Manage bookings across hotels

## 🗂️ Folder Structure

```
/QuartoIdeal/
├── DB/                          ← All database interaction scripts (CRUD, auth)
├── css/                         ← Styling (vanilla CSS)
├── image/                       ← Static assets
├── hotels.sql                   ← SQL schema for DB setup
├── *.php                        ← Main pages (home, login, bookings, admin, etc.)
├── README.md                    ← This glorious file
```

## 🛠️ Tech Stack

| Technology   | Role                        |
|--------------|-----------------------------|
| PHP          | Backend logic & rendering   |
| MySQL        | Data storage (via phpMyAdmin) |
| HTML/CSS     | Page structure and styling  |
| XAMPP        | Local development stack     |


## 🎯 Future Improvements

- Add search & filter options for room type, price, and availability
- Implement input validation and security hardening (SQL injection prevention)
- Improve mobile responsiveness with modern CSS
- Add real-time booking confirmation via AJAX
- Build a front-facing design with a modern JS frontend (React or Vue?)

## 👨‍💻 Author

**Jorge Faria**  
🔗 [LinkedIn](https://www.linkedin.com/in/fariajorge)

