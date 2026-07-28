# Deployment Guide for Nel Portfolio

## Overview
This is a full-stack application with:
- **Backend**: PHP (API and admin panel)
- **Frontend**: React + Vite
- **Database**: MySQL

## Local Development

### Prerequisites
- Node.js 18+ and npm 9+
- PHP 8.1+
- MySQL 5.7+

### Setup
```bash
# Install frontend dependencies
cd my-app
npm install
npm run build

# Start development server
npm run dev
```

## Deployment to Vercel

### Prerequisites
1. Vercel account (free tier available)
2. GitHub repository with this code
3. MySQL database on a hosting service (e.g., PlanetScale, AWS RDS, etc.)

### Step 1: Prepare Environment Variables

On Vercel Dashboard, add these environment variables:

```
DB_HOST=your-database-host.com
DB_PORT=3306
DB_NAME=nel_portfolio
DB_USER=your_db_user
DB_PASS=your_db_password
ENVIRONMENT=production
BASE_PATH=/
```

### Step 2: Database Setup

1. Create a MySQL database on your hosting service
2. Import the database schema from `database.sql`:
   ```bash
   mysql -u your_db_user -p nel_portfolio < database.sql
   ```

3. Run additional schema migrations:
   ```bash
   mysql -u your_db_user -p nel_portfolio < update_certifications_table.sql
   mysql -u your_db_user -p nel_portfolio < create_hero_tech_stack_table.sql
   mysql -u your_db_user -p nel_portfolio < create_hero_social_links_table.sql
   mysql -u your_db_user -p nel_portfolio < add_hero_font_settings.sql
   mysql -u your_db_user -p nel_portfolio < add_projects_header_settings.sql
   mysql -u your_db_user -p nel_portfolio < add_skills_header_settings.sql
   mysql -u your_db_user -p nel_portfolio < add_social_links_settings.sql
   ```

### Step 3: Deploy to Vercel

1. Push your code to GitHub
2. Connect repository to Vercel
3. Vercel will automatically:
   - Detect the project configuration from `vercel.json`
   - Build the React app
   - Deploy PHP backend

### Important Notes

#### File Uploads
- Files are uploaded to `/uploads` directory
- On Vercel, files are ephemeral (temporary storage)
- **For production**: Use a cloud storage service like:
  - AWS S3
  - Cloudinary
  - Azure Blob Storage
  - Google Cloud Storage

Update `/uploads/.htaccess` and PHP handlers accordingly.

#### Session Management
- Sessions use PHP's default file storage
- On serverless platforms (Vercel), sessions may not persist across requests
- **Recommendation**: Implement session handling using:
  - JWT tokens
  - Redis for session storage
  - Database session storage

#### Database Credentials
- **NEVER commit** `.env` files with real credentials
- Always use environment variables on production
- The `.env.example` file shows required variables

#### URLs and Paths
- If deploying to a subdirectory, update `BASE_PATH` environment variable
- Update API endpoints in PHP files to use environment variables
- React app automatically uses relative paths (should work fine)

## Troubleshooting

### npm error: Could not read package.json
✅ **Fixed** - Root `package.json` now properly configured

### Database connection failed on Vercel
1. Check `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` environment variables
2. Verify database server allows connections from Vercel's IP range
3. Test database connection locally first

### 404 errors after deployment
1. Check `BASE_PATH` environment variable matches deployment path
2. Verify `vercel.json` rewrite rules are correct
3. Check PHP error logs in Vercel dashboard

### Uploaded files not persisting
- Vercel serverless functions are stateless
- Use cloud storage for file uploads
- See "File Uploads" section above

### Sessions not working
- Implement token-based authentication (JWT)
- Or use external session storage (Redis, database)
- See "Session Management" section above

## Production Checklist

- [ ] Database provisioned on external host
- [ ] All environment variables set in Vercel
- [ ] Database migrations applied
- [ ] Test user created for admin panel
- [ ] File upload service configured (S3, Cloudinary, etc.)
- [ ] Session management implemented for production
- [ ] SSL/HTTPS enabled (Vercel provides free SSL)
- [ ] Domain connected to Vercel
- [ ] Backup strategy for database configured
- [ ] Monitor error logs regularly

## Useful Commands

```bash
# Build locally
npm run build

# Test production build
npm run preview

# Run linter
npm run lint

# Check git status before deployment
git status

# Push to GitHub (triggers Vercel deployment)
git push origin main
```

## Support
For issues, check:
1. Vercel deployment logs
2. Browser console (F12 → Console)
3. Network tab (F12 → Network)
4. PHP error logs in Vercel dashboard

