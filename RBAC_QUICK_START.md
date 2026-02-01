# 🚀 Role-Based Access Control - Quick Start

## What Changed?

Your KopuGive system now has proper role-based access control:
- ✅ Login page with role selection (Admin/Donor)
- ✅ Super Admin can access everything
- ✅ Admin can access most features (NOT donor records)
- ✅ Super Admin can add new admins
- ✅ Clear visual distinction between roles

## Setup (2 Steps)

### Step 1: Start Your MySQL Server
Make sure XAMPP MySQL is running.

### Step 2: Run the Migration
Open phpMyAdmin and run this SQL:

```sql
-- Or import: database/migrations/006_add_test_admin.sql
INSERT INTO users (full_name, email, phone, password_hash, role, is_active) 
VALUES ('Test Admin', 'testadmin@mrsmkp.edu.my', '0123456780', '$2y$10$YJGwM7RCLDSqYC0LvJqyJuVG9QVvzHPFQ6dWzKxLm8HvmVGFZGONe', 'admin', 1)
ON DUPLICATE KEY UPDATE role = 'admin';
```

## Test It!

### 1️⃣ Test Super Admin
- Go to: `http://localhost/kopugive/auth/login.php`
- Click on the **Super Admin** quick login text
- Credentials auto-fill, click Sign In
- ✅ You should see: Dashboard with "Donors" and "Manage Admins" menu

### 2️⃣ Test Regular Admin
- Logout
- Click on the **Admin** quick login text
- Credentials auto-fill, click Sign In
- ✅ You should see: Dashboard WITHOUT "Donors" menu
- ✅ "View Admins" instead of "Manage Admins"

### 3️⃣ Test Donor
- Logout
- Click on the **Donor** quick login text
- Credentials auto-fill, click Sign In
- ✅ You should see: Donor dashboard (different interface)

### 4️⃣ Test Role Validation
- Logout
- Select "Admin" but use donor email: ahmad@example.com
- Click Sign In
- ✅ You should see error: "This account is not an admin account"

## Test Accounts

```
🔴 SUPER ADMIN (Full Access)
Email: admin@mrsmkp.edu.my
Password: admin123

🔵 ADMIN (Limited Access)
Email: testadmin@mrsmkp.edu.my
Password: admin123

🟢 DONOR (Donor Portal)
Email: ahmad@example.com
Password: admin123
```

## Key Differences

| Feature | Super Admin | Admin | Donor |
|---------|-------------|-------|-------|
| View Donors | ✅ YES | ❌ NO | ❌ NO |
| Add Admins | ✅ YES | ❌ NO | ❌ NO |
| View Campaigns | ✅ YES | ✅ YES | ✅ YES (browse) |
| View All Donations | ✅ YES | ✅ YES | ❌ NO |
| Generate Reports | ✅ YES | ✅ YES | ❌ NO |

## Adding New Admins

1. Login as **Super Admin** (admin@mrsmkp.edu.my)
2. Click "Manage Admins" in sidebar
3. Fill the form on the left
4. New admin can immediately login

**Note**: Regular admins can see the list but NOT the creation form.

## Need More Info?

📖 Read the full documentation: `ROLE_BASED_ACCESS_CONTROL.md`

## Troubleshooting

**Problem**: Can't see "Donors" menu
**Solution**: Login as Super Admin, not regular Admin

**Problem**: Can't create new admins
**Solution**: Only Super Admin can create admins

**Problem**: Test admin doesn't exist
**Solution**: Run the migration SQL (Step 2 above)

---

**🎉 That's it! Your system now has proper role-based access control.**

