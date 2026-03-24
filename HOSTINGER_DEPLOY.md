# Hostinger Deployment Guide

## Step 1: Upload Files
1. Compress the entire project folder to ZIP
2. Upload to Hostinger via File Manager or FTP
3. Extract in `public_html` or your domain folder

## Step 2: Set Document Root
1. Go to Hostinger Control Panel
2. Navigate to **Advanced** → **PHP Configuration**
3. Set **Document Root** to: `public_html/public` (or `yourdomain/public`)

## Step 3: Create MySQL Database
1. Go to **Databases** → **MySQL Databases**
2. Create new database (e.g., `u123456_lesgo`)
3. Create database user with password
4. Assign user to database with ALL PRIVILEGES
5. Note down:
   - Database name
   - Database user
   - Database password
   - Database host (usually `localhost`)

## Step 4: Configure .env File
1. Open `.env` file in root directory
2. Update these values:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u123456_lesgo
DB_USERNAME=u123456_lesgo_user
DB_PASSWORD=your_password

SESSION_DRIVER=file
CACHE_STORE=file
```

## Step 5: Set Permissions
Run these commands via SSH or File Manager:
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## Step 6: Run Migrations
Via SSH:
```bash
cd public_html
php artisan migrate --force
php artisan cache:clear
php artisan config:clear
```

Or use Hostinger's **Terminal** feature in control panel.

## Step 7: Access Your App
Visit: `https://yourdomain.com/admin/login`

## Troubleshooting

### 500 Error
- Check `.env` file exists and is configured
- Check storage and bootstrap/cache permissions (755)
- Check PHP version is 8.2 in Hostinger control panel

### Database Connection Error
- Verify database credentials in `.env`
- Make sure database user has ALL PRIVILEGES
- Try `DB_HOST=127.0.0.1` instead of `localhost`

### Assets Not Loading
- Run `php artisan storage:link`
- Check document root is set to `public` folder
- Clear browser cache

## Important Notes
- PHP Version: 8.2 or higher
- Required PHP Extensions: pdo_mysql, zip, intl, mbstring, openssl
- Make sure `.env` file is NOT in public folder
- Never commit `.env` to git
