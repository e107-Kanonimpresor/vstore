# Quick Guide: Create GitHub Release v1.1.0

## ✅ Status: Ready to Publish

All code has been committed and pushed. The tag `v1.1.0` is ready on GitHub.

---

## 📋 Steps to Create the Release

### 1. Go to Releases Page
Visit: https://github.com/Kanonimpresor/vstore/releases

### 2. Click "Draft a new release"

### 3. Fill in the Release Form

**Choose a tag:**
- Select: `v1.1.0` (already exists)

**Release title:**
```
Vstore v1.1.0 - PHP 8.x Compatibility & Critical Bug Fixes
```

**Description:**
Copy the entire content from `RELEASE_NOTES_v1.1.0.md` file (already in the repository)

**Or use this summary:**

```markdown
## 🚨 IMPORTANT: Installation Instructions

⚠️ **After downloading this release, you MUST run:**

```bash
cd vstore
composer install --no-dev
```

**Why?** The `vendor/` directory is not included to keep the download lightweight.

---

## 🎯 What's New

### Major Fixes
✅ **PHP 8.x Full Compatibility** - Fixed all undefined array key warnings  
✅ **ibericode/vat v2.x Update** - Updated namespace and API methods  
✅ **Coupon System** - Fixed date validation and cart preservation bugs  
✅ **Checkout Process** - Auto-copy billing to shipping when needed  
✅ **Admin Panel** - Fixed multiple array access warnings  

### System Requirements
- e107 v2.3.0+
- PHP 7.4 - 8.3
- Composer v2.0+
- MySQL 5.7+ / MariaDB 10.2+

### Testing Status
✅ Fully tested on PHP 7.4, 8.0, 8.1, 8.2, 8.3  
✅ Order creation verified  
✅ All payment gateways working  

📚 **Full documentation**: See [RELEASE_NOTES_v1.1.0.md](https://github.com/Kanonimpresor/vstore/blob/main/RELEASE_NOTES_v1.1.0.md)

---

**Enjoy the new release! 🎉**
```

### 4. Attach Files (Optional)
You can attach:
- Custom installation packages
- Pre-built vendor.zip (if you want to provide dependencies pre-installed)

### 5. Set as Latest Release
✅ Check "Set as the latest release"

### 6. Publish
Click **"Publish release"**

---

## 🎉 After Publishing

1. **Announce on Gitter**: https://gitter.im/e107inc/vstore
2. **Post on e107 Forums**: https://e107.org/forum
3. **Update e107.org Plugin Directory** (if listed)

---

## 📝 Notes

- The tag `v1.1.0` points to commit `452c9f5`
- All documentation (README, CHANGELOG) is up to date
- All code changes have been pushed to `main` branch

---

**Ready to go! 🚀**
