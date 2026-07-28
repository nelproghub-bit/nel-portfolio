# Quick Start Guide - Nel Portfolio

## 🚀 Local Development (Windows)

### Prerequisites
- Node.js 18+ (https://nodejs.org)
- PHP 8.1+ (via XAMPP)
- MySQL running on `localhost:3306`

### Build & Run
```powershell
# Navigate to project
cd c:\xampp\htdocs\nel-portfolio

# Run build script
.\build.ps1

# Start development server
cd my-app
npm run dev
```

Visit: `http://localhost:5173`

---

## 🌐 Deploy to Vercel

### Step 1: Push to GitHub
```bash
git add .
git commit -m "Initial deployment configuration"
git push origin main
```

### Step 2: Create Vercel Project
1. Go to https://vercel.com
2. Click "Add New" → "Project"
3. Import your GitHub repository
4. Click "Deploy"

### Step 3: Add Environment Variables
In Vercel Dashboard → Settings → Environment Variables:

```
DB_HOST=your-mysql-host.com
DB_PORT=3306
DB_NAME=nel_portfolio
DB_USER=your_username
DB_PASS=your_password
ENVIRONMENT=production
BASE_PATH=/
```

### Step 4: Setup Database
On your MySQL host, import:
```bash
mysql -u username -p nel_portfolio < database.sql
```

### Step 5: Done!
Your site will be live at: `https://your-project.vercel.app`

---

## 🐛 Troubleshooting

### npm build errors
```powershell
# Clear cache and rebuild
rm -r my-app\node_modules
cd my-app
npm install
npm run build
```

### Database connection failed
- Verify `DB_HOST`, `DB_USER`, `DB_PASS` are correct
- Ensure MySQL server allows remote connections
- Check database name is correct

### Pages not loading
- Check environment variable `BASE_PATH`
- Verify all API endpoints are accessible
- Check browser console for JavaScript errors

---

## 📁 Project Structure

```
nel-portfolio/
├── my-app/              # React frontend
│   ├── src/
│   ├── dist/            # Build output
│   └── package.json
├── api/                 # PHP API handlers
├── admin/               # Admin panel (PHP)
├── config/              # Configuration files
├── uploads/             # User uploads
├── package.json         # Root configuration
├── vercel.json          # Vercel deployment
├── DEPLOYMENT.md        # Full deployment guide
└── README.md
```

---

## 🔑 Important Files

| File | Purpose |
|------|---------|
| `package.json` | Root npm config for Vercel |
| `vercel.json` | Vercel deployment settings |
| `.env.example` | Environment variables template |
| `config/db.php` | Database configuration |
| `config/auth.php` | Authentication settings |
| `DEPLOYMENT.md` | Full deployment guide |

---

## 💡 Development Tips

### Run only frontend
```bash
cd my-app
npm run dev
```

### Build production files
```bash
cd my-app
npm run build
```

### Check for lint errors
```bash
cd my-app
npm run lint
```

### Preview production build locally
```bash
cd my-app
npm run preview
```

---

## ⚠️ Important Security Notes

- **Never commit `.env` files** with real credentials
- Use environment variables on production
- Update database credentials before deploying
- Implement HTTPS (Vercel provides free SSL)
- Regular database backups recommended
- Monitor API logs for suspicious activity

---

## 📞 Need Help?

1. Check `DEPLOYMENT.md` for detailed guides
2. Review Vercel logs in dashboard
3. Check browser console (F12 → Console)
4. Verify environment variables are set
5. Test database connection locally first

---

**Ready to deploy?** → Read `DEPLOYMENT.md` for complete guide  
**Building locally?** → Run `.\build.ps1`  
**Need reference?** → See `QUICK_START.md` (this file)
