# Database Setup

The Products page uses the MySQL database configured in `.env`.

1. Start MySQL in XAMPP.
2. Open phpMyAdmin at `http://localhost/phpmyadmin`.
3. Open the SQL tab and run `database/pos_system.sql`.
4. Confirm that the `.env` file contains:

```env
database.default.hostname = localhost
database.default.database = pos_system
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

The customer, staff, product, and sales features use the database tables defined in this file.

The SQL file also creates a starter administrator account:

- Username: `admin`
- Password: `admin123`
