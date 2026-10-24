# Povaly Versioning Strategy

## Overview
Povaly follows **Semantic Versioning (SemVer)** used by major platforms like Amazon, Google, Facebook, and GitHub.

## Version Format: `MAJOR.MINOR.PATCH`

### Examples:
- `v1.0.0` - First stable release
- `v1.1.0` - Added new features (backwards compatible)
- `v1.1.1` - Bug fix (backwards compatible)
- `v2.0.0` - Major changes (may break compatibility)

## When to Increment:

### MAJOR version (v1.0.0 → v2.0.0)
Increment when making **incompatible changes**:
- Changing database schema significantly
- Removing features
- Changing API endpoints
- Major redesign that breaks existing customizations
- Upgrading to new Botble CMS major version

### MINOR version (v1.0.0 → v1.1.0)
Increment when adding **new features** (backwards compatible):
- New plugin installations
- New theme features
- New admin features
- New payment gateways
- Enhanced functionality

### PATCH version (v1.0.0 → v1.0.1)
Increment for **bug fixes** and small improvements:
- Security patches
- Bug fixes
- Performance improvements
- CSS/JS fixes
- Documentation updates

## Release Process

### 1. Create a Tag
```bash
# For major release
git tag -a v1.0.0 -m "Release v1.0.0 - Description"

# For minor release
git tag -a v1.1.0 -m "Release v1.1.0 - New features"

# For patch release
git tag -a v1.0.1 -m "Release v1.0.1 - Bug fixes"

# Push tag to GitHub
git push origin v1.0.0
```

### 2. Create GitHub Release
1. Go to https://github.com/goimranx/povaly/releases
2. Click "Draft a new release"
3. Select the tag (v1.0.0)
4. Add release notes
5. Check "Set as the latest release"
6. Publish release

### 3. Deploy to Production
```bash
# On server
git fetch --tags
git checkout v1.0.0
php artisan cms:theme:assets:publish martfury
php clear-cache.php
```

## Version Branches Strategy

### Main Branches:
- `main` - **Production-ready code** (always stable)
- `develop` - **Development branch** (next release features)
- `hotfix/*` - **Emergency fixes** for production

### Feature Branches:
- `feature/product-reviews` - New features
- `bugfix/cart-issue` - Bug fixes
- `hotfix/security-patch` - Urgent production fixes

### Workflow:
```
develop → feature/new-feature → develop → main → v1.1.0 release
main → hotfix/critical-bug → main → v1.0.1 release
```

## Version History

### v1.0.0 (Current - 2026-10-23)
**First Stable Release**

✨ Features:
- Martfury theme with custom enhancements
- Enhanced product cards with quantity controls
- System updater with real-time progress
- PV Gallery improvements
- Custom video features (likes, shares)

🐛 Bug Fixes:
- Fixed sticky header (uses theme option)
- Fixed admin sidebar collapsed hover
- Fixed carousel initialization
- Restored all corrupted admin UI files

📦 Based On:
- Botble CMS v1.41.11
- Laravel Framework
- Custom version: 1.41.11-povaly.1

## Rollback Strategy

### If v1.1.0 has issues:
```bash
# Rollback to v1.0.0
git checkout v1.0.0
php artisan cms:theme:assets:publish martfury
php clear-cache.php
```

### Database Rollback:
```bash
# If migrations were added
php artisan migrate:rollback --step=1
```

## How Big Platforms Version

### Amazon (AWS)
- Uses dates: `2023-11-27` for API versions
- Maintains old versions indefinitely
- Clear deprecation warnings

### Google
- Chrome: `v119.0.6045.105` (Major.Minor.Build.Patch)
- Android: `v14` (Major version only)
- APIs: `v3`, `v4` (Major version in URL)

### Facebook/Meta
- API versions: `v18.0` (Year.Release)
- 2-year support window
- Clear migration guides

### GitHub
- API: `v3`, `v4` (Major version)
- Clear breaking change notifications
- Beta features flagged

## Povaly Strategy (Recommended)

### Production Deployment:
1. **Always use tagged releases** (v1.0.0, v1.1.0)
2. **Never deploy from `main` branch directly**
3. **Test on staging first**
4. **Keep database backups before major updates**

### Development:
1. Work on `develop` branch
2. Merge to `main` only when stable
3. Tag and release from `main`
4. Hotfixes go directly to `main` then back to `develop`

## Quick Reference

```bash
# Check current version
git describe --tags

# List all versions
git tag -l

# Switch to specific version
git checkout v1.0.0

# Compare versions
git diff v1.0.0 v1.1.0

# Delete tag (if needed)
git tag -d v1.0.0
git push origin :refs/tags/v1.0.0
```

## Version in Code

Update `platform/core/core.json`:
```json
{
    "name": "Core",
    "version": "1.0.0",
    "namespace": "Botble\\Base\\"
}
```

## Changelog

Keep `CHANGELOG.md` updated with each release:
```markdown
## [1.0.0] - 2026-10-23
### Added
- New feature X
- New feature Y

### Fixed
- Bug fix A
- Bug fix B

### Changed
- Updated dependency Z
```

---

**Remember:** Consistent versioning helps track changes, enables rollbacks, and communicates impact to your team!
