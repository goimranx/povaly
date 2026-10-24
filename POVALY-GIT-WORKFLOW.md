# Povaly Git Workflow - Complete Guide

> **Purpose:** Official Git workflow for Povaly project  
> **For:** Developers, AI assistants, team members  
> **Updated:** October 23, 2026

---

## 📖 Table of Contents

1. [Essential Terms & Meanings](#essential-terms--meanings)
2. [Branch Structure](#branch-structure)
3. [Version Numbering](#version-numbering)
4. [Daily Workflow](#daily-workflow)
5. [Complete Examples](#complete-examples)
6. [Quick Reference](#quick-reference)

---

## Essential Terms & Meanings

### 1. BRANCH

**What it is:** A separate version of your code in the same folder.

**Real-life analogy:** Like different save slots in a video game - same game, different progress.

**Technical:** Git changes the actual files in your ONE folder when you switch branches.

**Example:**
```
You have ONE folder: D:\povaly\

git checkout main
→ Folder shows stable code (ReviewController.php doesn't exist)

git checkout feature/reviews
→ SAME folder now shows ReviewController.php (file appears!)

Git physically changes files on your computer!
```

**When to use:**
- Starting new feature
- Fixing bugs
- Experimenting

**Types of branches:**
```
main             - Production code (live on povaly.com)
develop          - Testing code (live on stage.povaly.com)
feature/name     - New features
bugfix/name      - Bug fixes
hotfix/name      - Urgent production fixes
```

---

### 2. STAGE

**What it is:** Selecting which files to save (commit).

**Real-life analogy:** Putting items in a shopping cart before checkout.

**Example:**
```
You edited 5 files:
- ProductController.php
- CartController.php
- ReviewController.php
- README.md
- style.css

You only want to save ProductController.php:

git add ProductController.php  ← STAGE (select this file)

Now only ProductController.php is ready to be committed.
```

**When to use:** Before every commit (select what to save).

**Commands:**
```bash
git add .                    # Stage all changed files
git add file.php            # Stage one file
git add app/Http/           # Stage entire folder
```

---

### 3. COMMIT

**What it is:** Saving staged files with a description message.

**Real-life analogy:** Taking a photo - once clicked, the moment is saved forever.

**Example:**
```bash
# You staged ProductController.php
git commit -m "feat: Added review system"

# Now ProductController.php changes are saved permanently
# Saved on YOUR COMPUTER only (not GitHub yet)
```

**When to use:** 
- After finishing a small task (hourly)
- Before switching branches
- Before pushing to GitHub

**Good commit messages:**
```bash
# Format: type: description

git commit -m "feat: Added product reviews"      # New feature
git commit -m "fix: Fixed cart calculation bug"  # Bug fix
git commit -m "style: Changed button color"      # Visual change
git commit -m "refactor: Improved code structure" # Code cleanup
```

**Bad commit messages:**
```bash
git commit -m "updates"        # Too vague
git commit -m "changes"        # What changes?
git commit -m "fixed stuff"    # What stuff?
```

---

### 4. PUSH

**What it is:** Uploading commits from your computer to GitHub.

**Real-life analogy:** Sending an email - you wrote it (commit), now send it (push).

**Example:**
```bash
# You committed on your computer
git commit -m "feat: Added reviews"

# Now upload to GitHub
git push origin feature/reviews

# Your commits are now backed up on GitHub
```

**When to use:**
- End of work day (backup your work)
- After several commits (backup frequently)
- Before asking team to review

**Important:** 
- Commit = Save locally (on your computer)
- Push = Backup remotely (on GitHub)

---

### 5. PULL

**What it is:** Downloading commits from GitHub to your computer.

**Real-life analogy:** Receiving an email - someone sent it (pushed), you receive it (pull).

**Example:**
```bash
# Teammate pushed code to GitHub
# You want to get their changes

git pull origin develop

# Now you have their latest code on your computer
```

**When to use:**
- Start of work day (get latest code)
- Before creating new branch
- Before merging branches

---

### 6. MERGE

**What it is:** Combining code from one branch into another.

**Real-life analogy:** Copying files from USB drive to your computer.

**Example:**
```bash
# You finished feature/reviews
# Now add it to develop branch

git checkout develop          # Go to develop
git merge feature/reviews     # Copy code from feature/reviews
git push origin develop       # Upload to GitHub

# Now develop has all your review code!
```

**When to use:**
- Feature complete → merge to develop
- Tested on staging → merge to main
- Bug fixed → merge to develop

---

### 7. TAG

**What it is:** Marking a specific commit as a version (v1.0.0).

**Real-life analogy:** Putting a label on a package - "This is version 1.0.0".

**Example:**
```bash
# You're ready to deploy v1.1.0
git tag -a v1.1.0 -m "Added product reviews"
git push origin v1.1.0

# Now commit is marked as v1.1.0
# You can deploy by saying "deploy v1.1.0" (clear!)
# Instead of "deploy commit abc123def" (confusing!)
```

**When to use:**
- **ALWAYS when deploying to production (povaly.com)**
- Every time you update the live site

**Version format:**
```
v1.0.0
│ │ │
│ │ └─ PATCH: Bug fixes (v1.0.0 → v1.0.1)
│ └─── MINOR: New features (v1.0.0 → v1.1.0)
└───── MAJOR: Big changes (v1.0.0 → v2.0.0)
```

---

### 8. RELEASE

**What it is:** A GitHub page announcing what's in a version.

**Real-life analogy:** Movie premiere - the movie exists (tag), but premiere is the announcement (release).

**Example:**
```
Tag v1.1.0 exists (marked in code)

Release page shows:
┌─────────────────────────────────┐
│ v1.1.0 - Product Reviews        │
│ Released: Nov 5, 2026           │
│                                 │
│ What's New:                     │
│ - Review system                 │
│ - Star ratings                  │
│ - Customer feedback             │
│                                 │
│ Bug Fixes:                      │
│ - Cart calculation fixed        │
│                                 │
│ [Download v1.1.0]              │
└─────────────────────────────────┘
```

**When to use:**
- **OPTIONAL** - Your choice!
- Recommended for big updates (v1.1.0, v1.2.0, v2.0.0)
- Skip for small fixes (v1.0.1, v1.0.2)

**Tag vs Release:**
```
Tag     = Required (marks version in code)
Release = Optional (announces to people)

Think:
Tag     = ISBN number on book (technical)
Release = Book launch event (announcement)
```

---

## Branch Structure

### The 3 Main Branches

```
┌─────────────────────────────────────┐
│ main (Production)                   │
│ - Live on povaly.com                │
│ - Always stable                     │
│ - Only tagged versions deployed     │
│ - Never work directly here          │
└─────────────────────────────────────┘
         ↑ merge when tested
┌─────────────────────────────────────┐
│ develop (Staging)                   │
│ - Live on stage.povaly.com          │
│ - Testing ground                    │
│ - May have bugs (that's ok!)        │
│ - Merge features here first         │
└─────────────────────────────────────┘
         ↑ merge when feature done
┌─────────────────────────────────────┐
│ feature/reviews (Your Work)         │
│ - Your computer only                │
│ - Work in progress                  │
│ - Can break things (safe here)      │
│ - Merge when complete               │
└─────────────────────────────────────┘
```

### Branch Naming Rules

**Format:** `type/description`

**Types:**
- `feature/` - New features (reviews, wishlist, checkout)
- `bugfix/` - Bug fixes (cart-bug, email-error)
- `hotfix/` - Urgent production fixes (security, site-down)
- `experiment/` - Testing ideas (might not go live)

**Examples:**
```bash
# Good names (clear purpose)
feature/product-reviews
feature/customer-wishlist
bugfix/cart-calculation
bugfix/email-sending
hotfix/security-patch

# Bad names (unclear)
reviews          # Is this feature or bug?
fix              # Fix what?
test             # Test what?
my-branch        # Your branch? Doing what?
```

**Why use slashes?**
```
Organization! Like folders:

features/
  ├── reviews
  ├── wishlist
  └── checkout

bugs/
  ├── cart
  └── email

You immediately know what each branch is for!
```

---

## Version Numbering

### Format: vMAJOR.MINOR.PATCH

```
v1.2.3
│ │ │
│ │ └─ PATCH: 3 (bug fixes)
│ └─── MINOR: 2 (new features)  
└───── MAJOR: 1 (big changes)
```

### When to Increment:

#### PATCH (v1.0.0 → v1.0.1)
**What:** Small bug fixes, typos, minor changes

**Examples:**
- Fixed cart calculation bug
- Fixed email sending error
- Changed button color
- Fixed typo on homepage

**Rule:** If it breaks nothing and adds nothing = PATCH

**Release?** NO - too small

---

#### MINOR (v1.0.0 → v1.1.0)
**What:** New features (backwards compatible)

**Examples:**
- Added product reviews
- Added wishlist feature
- Added new payment method
- Added email notifications

**Rule:** If you ADD something new = MINOR

**Release?** YES - announce new features!

---

#### MAJOR (v1.0.0 → v2.0.0)
**What:** Big changes (might break compatibility)

**Examples:**
- Complete redesign
- Changed database structure
- Removed old features
- New architecture

**Rule:** If you change/remove existing things = MAJOR

**Release?** YES - important announcement!

---

### Real Examples

```
v1.0.0 - Oct 23, 2026 - First stable release
v1.0.1 - Oct 25, 2026 - Fixed cart bug
v1.0.2 - Oct 27, 2026 - Fixed email bug
v1.1.0 - Nov 5, 2026  - Added reviews (NEW FEATURE!)
v1.1.1 - Nov 8, 2026  - Fixed review bug
v1.2.0 - Dec 10, 2026 - Added wishlist (NEW FEATURE!)
v2.0.0 - Jan 15, 2027 - Complete redesign (MAJOR!)
```

---

## Daily Workflow

### Morning (Start Work)

```bash
# 1. Open terminal in project folder
cd D:\Work Area\Development\Projects\povaly

# 2. Make sure you're on develop
git checkout develop

# 3. Get latest code from GitHub
git pull origin develop

# 4. Create your work branch
git checkout -b feature/product-reviews

# 5. Start coding in VS Code!
```

---

### During Work (Save Progress)

```bash
# Edit files in VS Code...
# Save files (Ctrl+S)

# Every 1-2 hours, commit your work:

# 1. See what you changed
git status

# 2. Stage files (select what to save)
git add .

# 3. Commit (save with message)
git commit -m "feat: Added review form"

# 4. Push to GitHub (backup)
git push origin feature/product-reviews

# Continue working...
```

**Do this multiple times a day! Commit often!**

---

### End of Day (Backup Everything)

```bash
# Make sure everything is pushed
git add .
git commit -m "WIP: End of day progress"
git push origin feature/product-reviews

# Your work is safe on GitHub!
# Tomorrow, continue from here:
git checkout feature/product-reviews
```

---

### Feature Complete (Deploy to Staging)

```bash
# 1. Feature is done and working locally
# 2. Time to test on stage.povaly.com

# Merge to develop
git checkout develop
git pull origin develop
git merge feature/product-reviews
git push origin develop

# 3. Deploy to stage.povaly.com
# SSH to staging server:
ssh user@stage.povaly.com
cd /var/www/stage
git pull origin develop
php artisan cms:theme:assets:publish martfury
php clear-cache.php

# 4. Test on stage.povaly.com for 2-3 days
# Fix any bugs, repeat process
```

---

### Ready for Production (Go Live)

```bash
# 1. Tested on staging - everything works!
# 2. Time to go live on povaly.com

# Merge to main
git checkout main
git pull origin main
git merge develop
git push origin main

# 3. CREATE TAG (REQUIRED!)
git tag -a v1.1.0 -m "v1.1.0 - Added product reviews

Added:
- Review system
- Star ratings
- Customer feedback

Fixed:
- Cart calculation bug"

git push origin v1.1.0

# 4. Update CHANGELOG.md
echo "## [1.1.0] - $(date +%Y-%m-%d)
- Added product review system
- Fixed cart calculation bug" >> CHANGELOG.md

git add CHANGELOG.md
git commit -m "docs: Update changelog for v1.1.0"
git push origin main

# 5. Create GitHub Release (OPTIONAL - 2 minutes)
# Go to: https://github.com/goimranx/povaly/releases
# Click: "Draft a new release"
# Select tag: v1.1.0
# Title: "v1.1.0 - Product Reviews"
# Description: Copy from tag message
# Click: "Publish release"

# 6. Deploy to povaly.com
# SSH to production:
ssh user@povaly.com
cd /var/www/povaly
git fetch --tags
git checkout v1.1.0
php artisan cms:theme:assets:publish martfury
php clear-cache.php

# 7. Test live site
# Visit povaly.com - verify everything works

# DONE! v1.1.0 is live! 🎉
```

---

## Complete Examples

### Example 1: Adding Reviews Feature (New Feature)

#### Step-by-Step:

**Day 1: Start Work**
```bash
git checkout develop
git pull origin develop
git checkout -b feature/product-reviews

# Create files:
# - app/Http/Controllers/ReviewController.php
# - app/Models/Review.php
# - database/migrations/*_create_reviews_table.php

git add .
git commit -m "feat: Added review database and models"
git push origin feature/product-reviews
```

**Day 2: Continue**
```bash
# Create review form view
# - resources/views/reviews/form.blade.php

git add .
git commit -m "feat: Added review form UI"
git push origin feature/product-reviews
```

**Day 3: Finish**
```bash
# Display reviews on product page
# - resources/views/products/show.blade.php

git add .
git commit -m "feat: Display reviews on product page"
git push origin feature/product-reviews

# Feature complete! Test locally
```

**Day 4: Deploy to Staging**
```bash
git checkout develop
git merge feature/product-reviews
git push origin develop

# Deploy to stage.povaly.com
# Test for 2-3 days
```

**Day 7: Deploy to Production**
```bash
git checkout main
git merge develop
git push origin main

git tag -a v1.1.0 -m "Added product reviews"
git push origin v1.1.0

# Deploy to povaly.com
ssh povaly.com
git checkout v1.1.0
php clear-cache.php

# Create GitHub release (optional)
```

**Version:** v1.0.0 → v1.1.0 (MINOR - new feature)  
**Release:** YES (new feature worth announcing)

---

### Example 2: Fixing Cart Bug (Bug Fix)

#### Step-by-Step:

**Same Day:**
```bash
git checkout develop
git pull origin develop
git checkout -b bugfix/cart-calculation

# Fix the bug in:
# - app/Http/Controllers/CartController.php

git add app/Http/Controllers/CartController.php
git commit -m "fix: Fixed cart total calculation"
git push origin bugfix/cart-calculation

# Merge to develop
git checkout develop
git merge bugfix/cart-calculation
git push origin develop

# Test on stage.povaly.com (1 day)

# If good, deploy to production
git checkout main
git merge develop
git push origin main

git tag -a v1.0.1 -m "Fixed cart calculation bug"
git push origin v1.0.1

# Deploy to povaly.com
ssh povaly.com
git checkout v1.0.1
php clear-cache.php
```

**Version:** v1.0.0 → v1.0.1 (PATCH - bug fix)  
**Release:** NO (small fix, not worth announcing)

---

### Example 3: URGENT! Site is Down! (Hotfix)

#### Step-by-Step:

**Immediate:**
```bash
# Site is broken! Skip staging!

git checkout main
git pull origin main
git checkout -b hotfix/critical-error

# Fix the problem FAST!
# - Fix the critical error

git add .
git commit -m "hotfix: Fixed critical error causing site crash"

# Merge directly to main (emergency!)
git checkout main
git merge hotfix/critical-error
git push origin main

git tag -a v1.0.2 -m "Emergency fix: site crash"
git push origin v1.0.2

# Deploy to povaly.com NOW!
ssh povaly.com
git checkout v1.0.2
php clear-cache.php

# Also update develop (important!)
git checkout develop
git merge hotfix/critical-error
git push origin develop
```

**Version:** v1.0.1 → v1.0.2 (PATCH - urgent fix)  
**Release:** NO (just fix it fast!)

---

## Quick Reference

### Essential Commands

```bash
# Check current branch
git branch --show-current

# See all branches
git branch

# Create and switch to new branch
git checkout -b feature/name

# Switch to existing branch
git checkout branch-name

# See what changed
git status
git diff

# Stage files
git add .                    # All files
git add file.php            # One file

# Commit
git commit -m "description"

# Push
git push origin branch-name

# Pull
git pull origin branch-name

# Merge
git checkout target-branch
git merge source-branch

# Tag
git tag -a v1.0.0 -m "message"
git push origin v1.0.0

# List tags
git tag

# Checkout tag
git checkout v1.0.0

# Delete branch
git branch -d branch-name
```

---

### When to Do What

| Action | When | Command |
|--------|------|---------|
| Create branch | Starting new work | `git checkout -b feature/name` |
| Commit | Every 1-2 hours | `git add . && git commit -m "msg"` |
| Push | End of day | `git push origin branch-name` |
| Merge to develop | Feature done | `git checkout develop && git merge feature/name` |
| Merge to main | Tested on staging | `git checkout main && git merge develop` |
| Create tag | Deploying to production | `git tag -a v1.0.0 -m "msg"` |
| Create release | Big updates only | GitHub → Releases → Draft |

---

### Decision Tree

```
What are you doing?

├─ Starting new work
│  └─> git checkout develop
│      git checkout -b feature/name
│
├─ Finished feature
│  └─> Merge to develop
│      Test on stage.povaly.com
│
├─ Tested on staging
│  └─> Merge to main
│      Create tag
│      Deploy to povaly.com
│
├─ Fixed bug
│  └─> Same as feature (but bugfix/name)
│
└─ URGENT production issue
   └─> Hotfix from main
       Deploy immediately
       Then merge to develop
```

---

### Version Number Decision

```
What did you do?

├─ Fixed typo/small bug
│  └─> PATCH: v1.0.0 → v1.0.1
│      Release: NO
│
├─ Added new feature
│  └─> MINOR: v1.0.0 → v1.1.0
│      Release: YES
│
└─ Complete redesign
   └─> MAJOR: v1.0.0 → v2.0.0
       Release: YES
```

---

## Rules to Remember

### Golden Rules

1. **Never work on main directly**
   ```bash
   ❌ git checkout main (edit files)
   ✅ git checkout -b feature/name (edit files)
   ```

2. **Always test on staging first**
   ```bash
   ❌ feature → main → povaly.com
   ✅ feature → develop → stage.povaly.com → test → main → povaly.com
   ```

3. **Always tag production deployments**
   ```bash
   ❌ git push origin main (deploy without tag)
   ✅ git tag v1.1.0 → git push origin v1.1.0
   ```

4. **Commit often, push daily**
   ```bash
   ❌ Work 3 days → one commit
   ✅ Work 1 hour → commit → work 1 hour → commit
   ```

5. **Good commit messages**
   ```bash
   ❌ "updates", "changes", "fixes"
   ✅ "feat: Added reviews", "fix: Cart bug"
   ```

6. **One branch = One task**
   ```bash
   ❌ feature/everything (reviews + wishlist + cart)
   ✅ feature/reviews (only reviews)
   ```

---

## For AI Assistants

When helping with Povaly development:

1. **Always follow this workflow**
2. **Create feature branches for new work**
3. **Never commit directly to main**
4. **Always create tags when deploying to production**
5. **Use proper version numbering (MAJOR.MINOR.PATCH)**
6. **Suggest GitHub releases for MINOR and MAJOR versions**
7. **Skip releases for PATCH versions**
8. **Update CHANGELOG.md with every version**

---

## For New Developers

### First Day Setup:

```bash
# 1. Clone repository
git clone https://github.com/goimranx/povaly.git
cd povaly

# 2. Install dependencies
composer install

# 3. Setup environment
cp .env.example .env
# Edit .env with your database credentials

# 4. Check branches
git branch -a

# 5. Checkout develop
git checkout develop

# 6. Read this guide again!
cat POVALY-GIT-WORKFLOW.md
```

### Your First Task:

```bash
# 1. Create feature branch
git checkout -b feature/your-name-test

# 2. Make small change (add comment to README.md)
echo "// Test by Your Name" >> README.md

# 3. Commit
git add README.md
git commit -m "test: First commit by Your Name"

# 4. Push
git push origin feature/your-name-test

# You did it! Now delete test branch:
git checkout develop
git branch -D feature/your-name-test
```

---

## Summary

### The Complete Flow:

```
1. Create branch    → git checkout -b feature/name
2. Work & commit    → git add . && git commit -m "msg"
3. Push daily       → git push origin feature/name
4. Merge to develop → git checkout develop && git merge feature/name
5. Deploy staging   → stage.povaly.com (test 2-3 days)
6. Merge to main    → git checkout main && git merge develop
7. Create tag       → git tag -a v1.1.0 -m "msg"
8. Deploy prod      → povaly.com (git checkout v1.1.0)
9. Create release   → GitHub (optional, for big updates)
```

### Remember:

- **Branch** = Different version in same folder
- **Stage** = Select files to save
- **Commit** = Save files locally
- **Push** = Upload to GitHub
- **Pull** = Download from GitHub
- **Merge** = Combine branches
- **Tag** = Mark version (REQUIRED for production!)
- **Release** = Announce version (OPTIONAL!)

---

**Last Updated:** October 23, 2026  
**Version:** 1.0  
**Project:** Povaly E-commerce Platform  
**Repository:** https://github.com/goimranx/povaly

---

**Keep this file! This is your single source of truth for Git workflow!** 📚
