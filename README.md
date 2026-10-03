# Pointly POS - CodeIgniter 4

This project contains the CodeIgniter 4 POS laboratory project with a themed dashboard and Product catalog management.

## Pages

- `/` - Landing page
- `/about` - About page
- `/customers` - Customer Accounts listing
- `/users` - User Accounts listing
- `/products` - Product catalog with add, edit, delete, stock, and image upload
- `/sales/new` - Record a sale and automatically reduce stock
- `/sales` - Sales history

## Installation

1. Install CodeIgniter 4 using Composer:

   ```powershell
   composer create-project codeigniter4/appstarter pos-system
   ```

2. Copy the files from this package into the generated `pos-system` folder, replacing files when asked.
3. Make sure MySQL is running in XAMPP.
4. Import `database/pos_system.sql` in phpMyAdmin.
5. Confirm the database settings in `.env`.
6. Run the application:

   ```powershell
   php spark serve
   ```

7. Open `http://localhost:8080` and log in with:

   - Username: `admin`
   - Password: `admin123`

All dashboard, customer, product, staff, and sales pages require login. Passwords created through Staff Management are stored using PHP password hashing.

The Product catalog uses the `products` table. Product images are stored in `public/uploads/products`, and staff avatars are stored in `public/uploads/avatars`.

## GitHub submission

Upload the project files to a GitHub repository. Do not upload the `vendor` folder if your instructor does not require it. Include this README and the `database/README.md` note.
