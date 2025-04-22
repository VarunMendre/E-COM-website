# 🛒 E-Commerce Web Application

This is a full-stack E-Commerce web application built using **HTML, CSS, JavaScript, PHP, and MySQL**, and run locally using **XAMPP**. It features two separate login systems: one for **Vendors** and one for **Customers**, with complete product management and simulated payment gateways.

---

## 🚀 Features

### 🔐 Vendor Side
- Secure login for vendors
- Upload new products with images, descriptions, price, etc.
- View all uploaded products
- Edit or delete existing product details
- View how many customers have purchased each product

### 🛍️ Customer Side
- Home page with a list of all available products
- Add products to a shopping cart
- View selected products on a dedicated "View Cart" page
- Choose from various payment gateways (UPI, Card, Net Banking)
- After successful payment, purchase details are sent to the vendor dashboard

---

## 🛠️ Tech Stack

- **Frontend:** HTML, CSS, JavaScript  
- **Backend:** PHP  
- **Database:** MySQL  
- **Local Server:** XAMPP (Apache + MySQL)

---

## 🗃️ Database Schema

The application uses the following tables:

| Table Name         | Description                                                                 |
|--------------------|-----------------------------------------------------------------------------|
| `user`             | Stores user login credentials and role information (Vendor/Customer)       |
| `product`          | Contains product details like name, description, price, image, vendor ID   |
| `cart`             | Tracks products added to a user's cart before purchase                     |
| `purchase_history` | Logs completed purchases by customers and links them to vendors/products   |
| `refunds`          | Stores refund requests or refund status for customer purchases             |

> You can import the SQL schema using phpMyAdmin or through your MySQL terminal.

---

## 📂 How to Run Locally

1. Make sure XAMPP is installed on your machine.
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. Clone or download this project into the `htdocs` folder inside the XAMPP directory.
4. Open **phpMyAdmin**, and import the SQL file to set up the database (file name: `ecom_db.sql` or similar).
5. Open your browser and go to:  
   `[http://localhost//](http://localhost/INTERN_PROJECT/)`

---

## 📸 Screenshots

*Add screenshots here showing the vendor dashboard, customer home, cart, and payment page.*

---

## 📌 Notes

- This project uses **simulated payment gateways** (no real transactions).
- All backend functionality is written in **PHP**, connected to **MySQL** database.
- Vendor and Customer roles are handled via user roles in the login system.

---

## 📧 Contact

Feel free to reach out if you have questions or want to contribute.  
**Developer:** Varun Mendre  
**Email:** *varunmm0404@gmail.com* 
