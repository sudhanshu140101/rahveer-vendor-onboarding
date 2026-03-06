# RAHVEER – Vendor Onboarding

Vendor/partner registration portal for **RAHVEER – Zero Accident Bharat**. Public registration form, document upload, and admin panel for managing submissions.

**Live:** [https://rahveer.com/](https://rahveer.com/)  
**Admin:** [https://rahveer.com/admin/login.php](https://rahveer.com/admin/login.php)



## Features ##

- **Public vendor registration** – Name, shop/business name, mobile, city, service type, optional document (JPG/PNG, max 5 MB)
- **Service types** – Mechanic, wheel alignment, tyre dealer, driver, medical support, legal advisor, loading service, other
- **Admin panel** – Login, dashboard (vendor list), view uploaded documents, export data
- **Validation** – Server-side checks, unique mobile per vendor, terms acceptance required
- **UI** – Responsive layout with Tailwind CSS


## Tech Stack

- **Backend:** PHP
- **Database:** MySQL
- **Frontend:** HTML, Tailwind CSS (CDN), Poppins font
- **Auth:** Session-based admin login



## Project Structure

├── admin/
│ ├── dashboard.php # Vendor list & actions
│ ├── export.php # Export vendors (e.g. CSV)
│ ├── index.php # Redirect to login/dashboard
│ ├── login.php # Admin sign-in
│ ├── logout.php # Sign out
│ └── view_document.php # Secure document viewer
├── includes/
│ └── auth.php # Admin auth helpers
├── sql/
│ └── schema.sql # DB schema & seed
├── uploads/ # Uploaded documents (+ .htaccess)
├── config.php # DB & app config
├── db.php # PDO connection
├── index.html # Public registration form
└── submit.php # Form submission API

