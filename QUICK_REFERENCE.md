# Quick Reference Guide

## 🚀 Start the Application

```bash
php artisan serve
```

Then visit: **http://localhost:8000/admin**

## 🔐 Login Credentials

### Admin (Full Access)
```
Email: admin@lesgo.com
Password: password
```

### Other Test Accounts
- Staff: maria.santos@lesgo.com / password
- Partner: contact@fastmove.com / password
- Driver: driver1@lesgo.com / password
- Customer: pedro.reyes@gmail.com / password

## 📊 What You'll See

### Dashboard
- 6 stat cards with trends
- Orders line chart (12 months)
- Revenue bar chart (12 months)
- Order status doughnut chart
- Latest 10 orders table

### Navigation
- **Dashboard** - Analytics and overview
- **User Management** - Users, Drivers
- **Business** - Partners
- **Operations** - Orders, Services, Vehicles
- **Finance** - Payments

## 🎨 Theme

- **Primary**: Light Blue (#38BDF8)
- **Background**: Very Light Blue (#F0F9FF)
- **Cards**: White
- **Text**: Black/Dark Gray

## 📁 Key Files

### Widgets
- `app/Filament/Widgets/StatsOverview.php`
- `app/Filament/Widgets/OrdersChart.php`
- `app/Filament/Widgets/RevenueChart.php`
- `app/Filament/Widgets/OrderStatusChart.php`
- `app/Filament/Widgets/LatestOrders.php`

### Theme
- `resources/css/filament/admin/theme.css`
- `public/css/filament/admin/theme.css`

### Config
- `app/Providers/Filament/AdminPanelProvider.php`

## 🔄 Common Commands

### Reset Database
```bash
php artisan migrate:fresh --seed
```

### Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Recompile Theme
```bash
npx tailwindcss@3 --input ./resources/css/filament/admin/theme.css --output ./public/css/filament/admin/theme.css --config ./resources/css/filament/admin/tailwind.config.js --minify
```

### Run Seeders Only
```bash
php artisan db:seed
```

## 📈 Seeded Data

- 29 Users (1 admin, 2 staff, 2 partners, 10 drivers, 15 customers)
- 4 Partners
- 8-12 Partner Branches
- 15-20 Services
- 20-30 Addresses
- 10 Driver Profiles
- 10-15 Vehicles
- 100-200 Orders (last 12 months)
- 80-150 Payments
- 29 Wallets with transactions

## 🎯 Test Scenarios

### View Analytics
1. Login as admin
2. Dashboard shows all charts
3. Check stats are populated

### Manage Partners
1. Go to Partners
2. View a partner
3. Check tabs: Branches, Services, Drivers, Vehicles

### View Orders
1. Go to Orders
2. View an order
3. Check Payments tab

### Create Payment
1. Go to Payments
2. Click Create
3. Fill form and save

## 📚 Documentation

- `CODE_REVIEW_SUMMARY.md` - All bug fixes
- `MIGRATION_COMPLETE.md` - Database setup
- `SEEDERS_COMPLETE.md` - Seeded data details
- `THEME_AND_ANALYTICS_COMPLETE.md` - Theme & widgets
- `DEPLOYMENT_CHECKLIST.md` - Production deployment

## 🆘 Troubleshooting

### Charts Not Showing
```bash
php artisan config:clear
php artisan cache:clear
```

### Theme Not Applied
```bash
# Recompile theme
npx tailwindcss@3 --input ./resources/css/filament/admin/theme.css --output ./public/css/filament/admin/theme.css --config ./resources/css/filament/admin/tailwind.config.js --minify
```

### No Data in Dashboard
```bash
php artisan db:seed
```

### Can't Login
```bash
# Recreate admin user
php artisan tinker
User::where('email', 'admin@lesgo.com')->first()->update(['password' => bcrypt('password')]);
```

---

**Everything is ready!** 🎉
Start the server and login to see your light blue themed dashboard with analytics!
