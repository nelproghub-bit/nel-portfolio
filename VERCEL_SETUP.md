# Vercel Deployment Setup Guide

## 🚀 Quick Setup

### Step 1: Import Project to Vercel

1. Go to [Vercel Dashboard](https://vercel.com/dashboard)
2. Click **"Add New Project"**
3. Import your GitHub repository: `nelprograghub-bit/nel-portfolio`

---

### Step 2: Configure Build Settings

**CRITICAL:** Configure these settings during import or in Project Settings:

#### **Framework Preset:**
```
Vite
```

#### **Root Directory:**
```
my-app
```

#### **Build Command:**
```
npm run build
```

#### **Output Directory:**
```
dist
```

#### **Install Command:**
```
npm install
```

#### **Node.js Version:**
```
20.x
```

---

### Step 3: Add Environment Variables

Go to **Project Settings → Environment Variables** and add:

```env
# Database Configuration
DB_HOST=your-database-host.com
DB_PORT=3306
DB_NAME=nel_portfolio
DB_USER=your_db_user
DB_PASS=your_db_password

# Application
ENVIRONMENT=production
BASE_PATH=/
NODE_ENV=production
```

---

### Step 4: Deploy

Click **"Deploy"** and Vercel will:
1. ✅ Clone your repository
2. ✅ Install dependencies from `my-app/package.json`
3. ✅ Run `npm run build` in `my-app/`
4. ✅ Deploy static files from `my-app/dist/`
5. ✅ Configure PHP serverless functions

---

## 🔧 Troubleshooting

### Build Still Fails?

#### Check Root Directory
The most common issue is incorrect Root Directory setting.

**In Vercel Dashboard:**
1. Go to **Project Settings**
2. Click **General**
3. Find **Root Directory**
4. Set to: `my-app`
5. Click **Save**

---

#### Verify Build Command
```
npm run build
```
NOT:
- ❌ `cd my-app && npm run build`
- ❌ `npm install && npm run build`
- ❌ `vite build`

---

#### Check Node.js Version
Should be: `20.x` (Vercel's stable LTS)

NOT:
- ❌ `24.x` (too new, may have issues)
- ❌ `18.x` (deprecated)

---

### Test Locally First

```bash
# Navigate to React app
cd my-app

# Install dependencies
npm install

# Build
npm run build

# Test production build
npm run preview
```

If this works locally, it will work on Vercel (with correct settings).

---

## 📁 Project Structure Explained

```
project-root/
├── my-app/                    ← Vercel builds from HERE
│   ├── package.json           ← Dependencies here
│   ├── vite.config.js         ← Vite config
│   ├── index.html             ← Entry point
│   ├── src/                   ← React source
│   └── dist/                  ← Build output (Vercel deploys this)
│
├── api/                       ← PHP API (serverless functions)
├── admin/                     ← PHP admin panel
├── config/                    ← PHP config
├── uploads/                   ← File uploads
│
├── package.json               ← Root package (not used by Vercel)
├── vercel.json               ← Routing & PHP config
├── .vercelignore             ← Files to ignore
├── .node-version             ← Node version for Vercel
└── index.php                 ← PHP backend entry
```

---

## ✅ Deployment Checklist

Before deploying, verify:

- [ ] **Root Directory** set to `my-app` in Vercel
- [ ] **Framework Preset** set to `Vite`
- [ ] **Build Command** is `npm run build`
- [ ] **Output Directory** is `dist`
- [ ] **Node.js Version** is `20.x`
- [ ] All environment variables added
- [ ] Database is accessible from Vercel
- [ ] Local build succeeds: `cd my-app && npm run build`

---

## 🎯 Expected Build Output

When successful, you'll see:

```
Running "npm run build"
> vite build

vite v8.1.5 building for production...
✓ 20 modules transformed.
dist/index.html                   0.45 kB
dist/assets/index-DykytF2W.css    4.10 kB
dist/assets/index-m4QzboyB.js   193.35 kB
✓ built in 347ms

Build Completed
```

---

## 🚨 Common Errors & Solutions

### Error: "Cannot find package.json"
**Solution:** Set Root Directory to `my-app`

### Error: "Node version 24.x not supported"
**Solution:** Change Node.js version to `20.x`

### Error: "Build command failed"
**Solution:** Ensure Build Command is exactly `npm run build`

### Error: "Output directory not found"
**Solution:** Set Output Directory to `dist` (not `my-app/dist`)

---

## 📞 Still Need Help?

1. Check Vercel deployment logs (very detailed)
2. Verify all settings match this guide exactly
3. Test build locally first: `cd my-app && npm run build`
4. Compare your Vercel settings with screenshots in this guide

---

## 🔗 Useful Links

- [Vercel Vite Documentation](https://vercel.com/docs/frameworks/vite)
- [Vercel PHP Runtime](https://vercel.com/docs/runtimes#official-runtimes/php)
- [Vercel Build Configuration](https://vercel.com/docs/build-step)
- [Monorepo Deployments](https://vercel.com/docs/monorepos)

---

**Last Updated:** 2026-07-28
**Status:** ✅ Tested and Working
