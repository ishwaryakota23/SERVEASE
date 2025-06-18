# ServEase - Personalized Service Marketplace

ServEase is a web-based platform that connects customers with skilled freelancers for a wide range of personalized services. These services include home cleaning, electrician work, plumbing, salon & beauty, fitness training, tutoring, photography, and web support.

---

## 🛠️ Features

### 🔹 Customer Features:
- Sign up and log in as a customer
- Browse services by category, price, and description
- View and select freelancers based on service needs
- Send service requests to freelancers
- Manage service history and support queries through the customer dashboard

### 🔹 Freelancer Features:
- Sign up and log in as a freelancer
- Create a service profile including category, pricing, and description
- Receive and respond to customer service requests
- Manage personal dashboard: view service history, support issues, and profile
- Badges for achievements and credibility

### 🔹 Admin Role (Planned):
- Manage customer and freelancer accounts
- View and resolve support queries
- Oversee platform activities and data

---

## 💻 Tech Stack

- **Frontend:** HTML5, CSS3 (responsive design without JavaScript frameworks)
- **Backend:** PHP
- **Database:** MySQL
- **Others:** Sessions for login, basic security practices (e.g., password hashing recommended)

---


---

## 🧩 Database Structure

**Database Name:** `login_db`

### Tables:
- `customer_login` – Stores customer details
- `freelancer_login` – Stores freelancer details
- `freelancer_service_request` – Stores customer requests to freelancers
- Additional tables for support, feedback, and services can be added as needed

---

## 🚀 How to Run Locally

1. **Clone the repository** or download it as a ZIP
2. **Install XAMPP/WAMP** (local server)
3. **Move the project folder** to your `htdocs` (for XAMPP) or `www` (for WAMP)
4. **Create the MySQL database** named `login_db`
5. **Import SQL tables** (use `phpMyAdmin` or MySQL CLI)
6. **Update database credentials** in `db/connection.php` if needed
7. **Run the project** by visiting `http://localhost/ServeEase/index.html`

---

## 🔒 Compliance

- I acknowledge and commit to using my personal laptop/desktop during development and training.
- The project adheres to data privacy guidelines and aims to follow **ISO** and **SOC 2** compliance standards.
- Session management and secure coding practices are encouraged.

---

## 📩 Support System

- Freelancers and customers can raise issues using the Support page.
- All support queries are sent to the admin for review and resolution.

---

## 🧠 Future Enhancements

- ✅ Admin dashboard
- ✅ Ratings and reviews for freelancers
- ✅ Payment gateway (Razorpay/Stripe)
- ✅ Email & SMS alerts
- ✅ Chat system for customers and freelancers
- ✅ OTP-based login and password reset

---



