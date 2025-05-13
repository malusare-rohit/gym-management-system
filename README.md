💪 Gym Management System

This project is a complete **Gym Management System** that allows users to register, login, choose a membership plan, and for the admin to manage users and their memberships efficiently.

---

### 🧑‍💻 User Features:

* **Homepage** with `Register` and `Login` buttons.
* **Register Page**:

  * Users enter first name, last name, phone number, email, and password.
  * If email is already registered:
    `This email is already registered. Please use a different email or login.`
* **Login Page**:

  * Login with email and password.
  * Error shown if credentials are incorrect:
    `Invalid email or password.`
* **Switch Links** between user and admin login pages.
* **After successful login** (user):

  * Displays:
    `Welcome, (user_name)! You are not a member yet.`
  * Button to **Join Membership**.

---

### 💳 Membership System:

* **Plan Selection Page**:

  * Basic Plan - ₹499.00 (1 month)
  * Standard Plan - ₹1,299.00 (3 months)
  * Premium Plan - ₹2,399.00 (6 months)
  * Elite Plan - ₹4,499.00 (12 months)
* After selection:

  ```
  Welcome, (user_name)!
  Membership Plan: Standard Plan
  Valid From: 2025-03-18
  Valid Till: 2025-06-18
  Payment Status: Pending
  ```
* **Renew Membership** option after expiration or rejection.

---

### 🛡️ Admin Features:

* **Admin Dashboard** with:

  * Logout button
  * User Management Table:

    * View, Edit, and Delete user details
  * **Pending Membership Approvals**:

    * Approve or Reject based on payment
  * **Membership Details Table**:

    * Shows plan, price, start/end date, and payment status for each user

* After **Admin Approval**:

  * User dashboard is updated with:
    `Payment Status: Paid`

* After **Admin Rejection**:

  * User is shown the Join Membership page again

---

### 🛠️ Tech Stack:

* **Frontend**: HTML, CSS, JavaScript
* **Backend**: PHP / Java (mention what you used)
* **Database**: MySQL / PostgreSQL
