# Git Commands to Update Repository

## Quick Update (All files)

```bash
# Navigate to plugin directory
cd c:/wamp64/www/vstore/e107_plugins/vstore

# Check current status
git status

# Add all modified files
git add .

# Commit with descriptive message
git commit -m "Major update: PHP 8.x compatibility, coupon fixes, enhanced documentation

- Fixed ibericode/vat v2.x namespace compatibility (14 references)
- Resolved cart emptying when applying coupons
- Fixed shipping data validation in checkout
- Added PHP 8.x compatibility fixes (undefined array keys)
- Fixed PDF generation warnings
- Enhanced README with multilingual support (EN, ES, PT)
- Added comprehensive CHANGELOG.md
- Improved error handling and validation throughout codebase"

# Push to remote repository
git push origin main
```

## Step-by-Step (Recommended)

### 1. Check Repository Status
```bash
git status
git diff
```

### 2. Stage Changes Selectively

#### Core Plugin Files
```bash
git add vstore.class.php
git add inc/admin_config_orders.php
git add inc/vstore_pdf.class.php
git add shortcodes/batch/vstore_shortcodes.php
git add e_header.php
```

#### Documentation
```bash
git add README.md
git add README.es-ES.md
git add README.pt-PT.md
git add CHANGELOG.md
```

### 3. Review Staged Changes
```bash
git diff --staged
```

### 4. Commit
```bash
git commit -m "Major update: PHP 8.x compatibility and bug fixes

Core Fixes:
- ibericode/vat v2.x namespace update (DvK\Vat → Ibericode\Vat)
- Coupon system: date validation and cart preservation
- Checkout: automatic shipping data copy when using billing address
- PHP 8.x: undefined array key fixes (9 functions in shortcodes)
- PDF generation: type checking before foreach

Documentation:
- Enhanced README with multilingual support (English, Spanish, Portuguese)
- Added comprehensive CHANGELOG.md
- Improved feature descriptions and installation guide

See CHANGELOG.md for detailed list of all changes."
```

### 5. Push to GitHub
```bash
# Push to main branch
git push origin main

# Or if you're using master branch
git push origin master
```

## Create a New Release (Optional)

After pushing, create a release on GitHub:

1. Go to: https://github.com/e107-Kanonimpresor/vstore/releases
2. Click "Draft a new release"
3. Tag version: `v1.1.0` (or appropriate version)
4. Release title: `v1.1.0 - PHP 8.x Compatibility Update`
5. Description: Copy relevant sections from CHANGELOG.md
6. Click "Publish release"

## Verify Upload

```bash
# Check remote status
git remote -v

# Fetch and compare
git fetch origin
git log origin/main

# View on GitHub
# Navigate to: https://github.com/e107-Kanonimpresor/vstore
```

## Troubleshooting

### If remote repository has changes
```bash
# Pull remote changes first
git pull origin main --rebase

# Then push
git push origin main
```

### If you need to update remote URL
```bash
# Check current remote
git remote -v

# Update remote URL
git remote set-url origin https://github.com/e107-Kanonimpresor/vstore.git
```

### If authentication fails
```bash
# Use personal access token (not password)
# Generate token at: https://github.com/settings/tokens

# Or configure SSH
git remote set-url origin git@github.com:e107-Kanonimpresor/vstore.git
```

## Files Modified Summary

### Core Plugin Files (8 files)
- ✅ vstore.class.php
- ✅ inc/admin_config_orders.php  
- ✅ inc/vstore_pdf.class.php
- ✅ shortcodes/batch/vstore_shortcodes.php
- ✅ e_header.php

### Documentation (4 new/updated files)
- ✅ README.md (enhanced)
- ✅ README.es-ES.md (new)
- ✅ README.pt-PT.md (new)
- ✅ CHANGELOG.md (new)

**Total: 12 files changed**

## Post-Upload Checklist

- [ ] Verify all files uploaded: https://github.com/e107-Kanonimpresor/vstore
- [ ] Check README displays correctly on GitHub
- [ ] Language badges link to correct files
- [ ] CHANGELOG.md is formatted properly
- [ ] Consider creating a release tag
- [ ] Update e107 plugin repository listing (if applicable)
- [ ] Announce update in e107 forums/Gitter
