# Role-Based Access Control (RBAC) System

## Overview
KopuGive now has a comprehensive role-based access control system with three user roles:
- **Super Admin** - Full system access
- **Admin** - Limited access (cannot view donor records or add new admins)
- **Donor** - Regular donor portal access

## Features Implemented

### 1. **Login Page with Role Selection**
- Users must select whether they're logging in as **Admin** or **Donor**
- System validates that the account role matches the selected login type
- Quick-click credentials for easy testing
- Prevents role confusion and unauthorized access attempts

**Location**: `auth/login.php`

### 2. **Three User Roles**

#### Super Admin
- **Full access** to all system features
- Can view and manage donor records
- Can add new admin accounts
- Can view all campaigns, donations, and reports
- **Test Account**: admin@mrsmkp.edu.my / admin123

#### Admin
- Can manage campaigns and donations
- Can view Stripe payments
- Can generate reports
- Can view other admin accounts
- **Cannot** view donor records (privacy protection)
- **Cannot** create new admin accounts
- **Test Account**: testadmin@mrsmkp.edu.my / admin123

#### Donor
- Can view active campaigns
- Can make donations
- Can view their own donation history
- Can manage their profile
- **Test Account**: ahmad@example.com / admin123

### 3. **Access Control Implementation**

#### Page-Level Protection

**Super Admin Only:**
- `admin/donors.php` - View donor records

**Admin & Super Admin:**
- `admin/dashboard.php` - Dashboard
- `admin/campaigns.php` - Manage campaigns
- `admin/donations.php` - View all donations
- `admin/stripe_transactions.php` - Stripe payments
- `admin/reports.php` - Generate reports
- `admin/settings.php` - System settings
- `admin/users.php` - View admin list (only Super Admin can add new admins)

**Donor Only:**
- `donor/dashboard.php` - Donor dashboard
- `donor/campaigns.php` - Browse campaigns
- `donor/my_donations.php` - Personal donations
- `donor/profile.php` - Profile management

#### Sidebar Navigation
- Dynamic menu based on user role
- "Donors" menu item only visible to Super Admin
- "Manage Admins" for Super Admin, "View Admins" for regular Admin
- Clean, role-appropriate interface

### 4. **Permission Functions**

**Available in**: `includes/functions.php`

```php
// Check if user is logged in
isLoggedIn()

// Check if user is Super Admin
isSuperAdmin()

// Check if user is Admin or Super Admin
isAdmin()
```

**Usage Example:**
```php
// Restrict to Super Admin only
if (!isSuperAdmin()) {
    setFlashMessage('error', 'Access denied.');
    redirect('dashboard.php');
}

// Allow both Admin and Super Admin
if (!isAdmin()) {
    redirect('../auth/login.php');
}
```

## Database Schema

### Users Table
```sql
role ENUM('super_admin', 'admin', 'donor') DEFAULT 'donor'
```

The database supports three distinct roles with appropriate permissions.

## Setup Instructions

### 1. Run Database Migration
To add the test admin account, run this migration:

```bash
# Using MySQL command line
mysql -u root -p kopugive < database/migrations/006_add_test_admin.sql

# Or using phpMyAdmin
# Import the file: database/migrations/006_add_test_admin.sql
```

### 2. Test the System

#### Test Super Admin Access:
1. Go to login page
2. Select "Admin"
3. Use credentials: admin@mrsmkp.edu.my / admin123
4. Verify you can access all features including Donors and Manage Admins

#### Test Regular Admin Access:
1. Logout
2. Select "Admin"
3. Use credentials: testadmin@mrsmkp.edu.my / admin123
4. Verify you can access most features but NOT:
   - Donors menu (not visible)
   - Cannot add new admins (form hidden)

#### Test Donor Access:
1. Logout
2. Select "Donor"
3. Use credentials: ahmad@example.com / admin123
4. Verify you're in donor portal

#### Test Role Validation:
1. Try logging in as "Admin" with donor credentials
2. Should see error: "This account is not an admin account"
3. Try logging in as "Donor" with admin credentials
4. Should see error: "This is an admin account"

## Security Features

### 1. Role Validation on Login
- System checks if account role matches selected login type
- Prevents unauthorized access through wrong portal

### 2. Page-Level Protection
- Every protected page checks user role
- Automatic redirect if unauthorized
- Flash messages inform users of access denial

### 3. UI-Level Protection
- Sensitive menu items hidden based on role
- Forms/buttons hidden if user lacks permission
- Clean interface without confusing options

### 4. Database-Level Separation
- Donors and Admins are in same table but role-separated
- Regular admins cannot query donor records
- Clear audit trail with activity logs

## Admin Management

### Creating New Admins (Super Admin Only)

1. Login as Super Admin
2. Navigate to "Manage Admins"
3. Fill in the form:
   - Full Name
   - Email Address
   - Phone (optional)
   - Password (min 8 characters)
   - Confirm Password
4. Click "Create Admin"
5. New admin can immediately login

**Note**: Regular admins can view the admin list but cannot create new admins.

## Privacy Protection

### Donor Records Access
- Only Super Admins can view donor information
- Regular admins have NO access to:
  - Donor personal details
  - Donor contact information
  - Donor user accounts
- Protects donor privacy while allowing donation management

**Why?**
- Separates operational duties
- Prevents unauthorized contact
- Complies with privacy best practices
- Reduces risk of data misuse

## Modified Files

1. `auth/login.php` - Added role selection and validation
2. `admin/donors.php` - Restricted to Super Admin only
3. `admin/users.php` - Allow viewing for Admin, creation for Super Admin only
4. `admin/includes/admin_sidebar.php` - Dynamic menu based on role
5. `database/migrations/006_add_test_admin.sql` - Test admin account
6. `includes/functions.php` - Already had role checking functions

## Quick Reference

| Feature | Super Admin | Admin | Donor |
|---------|-------------|-------|-------|
| View Dashboard | ✅ | ✅ | ✅ (own) |
| Manage Campaigns | ✅ | ✅ | ❌ |
| View All Donations | ✅ | ✅ | ❌ |
| View Donor Records | ✅ | ❌ | ❌ |
| Add New Admins | ✅ | ❌ | ❌ |
| View Admin List | ✅ | ✅ | ❌ |
| Generate Reports | ✅ | ✅ | ❌ |
| Stripe Payments | ✅ | ✅ | ❌ |
| Make Donations | ❌ | ❌ | ✅ |
| View Own Donations | ❌ | ❌ | ✅ |

## Test Accounts Summary

```
🔴 Super Admin
   Email: admin@mrsmkp.edu.my
   Password: admin123
   Access: EVERYTHING

🔵 Admin
   Email: testadmin@mrsmkp.edu.my
   Password: admin123
   Access: Most features (NOT donors)

🟢 Donor
   Email: ahmad@example.com
   Password: admin123
   Access: Donor portal only
```

## Troubleshooting

### "Access denied" when trying to view Donors
- You're logged in as regular Admin
- Only Super Admins can access donor records
- Login with Super Admin credentials

### Can't create new admins
- Only Super Admins can create new admins
- Regular admins can only view the list
- Contact your Super Admin

### Wrong portal after login
- Check which role option you selected
- Admin accounts must select "Admin"
- Donor accounts must select "Donor"

### Test admin account doesn't exist
- Run the migration: `database/migrations/006_add_test_admin.sql`
- Or have a Super Admin create it manually

## Best Practices

1. **Principle of Least Privilege**
   - Users only get access they need
   - Regular admins don't need donor data
   - Reduces security risks

2. **Clear Role Separation**
   - Admin vs Donor portals are distinct
   - No confusion about capabilities
   - Better user experience

3. **Audit Trail**
   - All actions logged with user ID
   - Can track who did what
   - Important for accountability

4. **Password Security**
   - All passwords hashed with bcrypt
   - Minimum 8 characters required
   - Change default passwords in production

## Production Deployment

Before going live:

1. ✅ Change all default passwords
2. ✅ Remove or change test account credentials
3. ✅ Verify Super Admin email is correct
4. ✅ Test all role permissions
5. ✅ Review activity logs regularly
6. ✅ Document your specific Super Admin credentials securely

## Support

For issues or questions:
- Check this documentation first
- Review the Quick Reference table
- Test with the provided test accounts
- Check browser console for JavaScript errors
- Review PHP error logs for server-side issues

---

**Implementation Date**: February 1, 2026
**System**: KopuGive - MRSM Kota Putra Donation Management
**Version**: 1.0 with RBAC



