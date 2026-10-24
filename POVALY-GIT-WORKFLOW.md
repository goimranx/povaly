# Povaly Git Workflow Guide

> **Simple, clear workflow for working on Povaly project**  
> **Anyone can understand this - developers, AI assistants, or new team members**  
> **Last Updated:** October 23, 2026

---

## 🎯 Quick Start (5-Minute Read)

**You have Povaly code. You want to add features. Here's how:**

1. **Create branch** → Work in isolated space (don't break main code)
2. **Test locally** → Run on your computer (`php artisan serve`)
3. **Merge to develop** → Put multiple features together
4. **Test on staging** → Check on stage.povaly.com (real server)
5. **Merge to main** → Ready for production
6. **Create tag** → Mark version (v1.1.0) - **REQUIRED!**
7. **Deploy** → Update povaly.com using the tag
8. **Create release** → (Optional) Announce what's new on GitHub

**That's it! Details below...**

---

## 📖 Table of Contents

1. [Core Concepts Explained Simply](#core-concepts-explained-simply)
2. [When to Create a New Branch](#when-to-create-a-new-branch)
3. [Our Branch Structure](#our-branch-structure)
4. [The Complete Workflow](#the-complete-workflow)
5. [Testing: Local vs Staging vs Production](#testing-local-vs-staging-vs-production)
6. [Tags vs Releases (What's Required?)](#tags-vs-releases-whats-required)
7. [Version Numbering Made Simple](#version-numbering-made-simple)
8. [Real-World Examples](#real-world-examples)
9. [Common Questions Answered](#common-questions-answered)
10. [Quick Command Reference](#quick-command-reference)

---

## Core Concepts Explained Simply

### 🌿 What is a BRANCH?

**Simple explanation:** Different versions of your code in the SAME folder.

**Think of it like Microsoft Word:**
- You write a document
- You save different versions: "Draft 1", "Draft 2", "Final"
- But they're all in the same "Document1.docx" file
- When you switch versions, the content changes but the file stays the same

**In Git:**
```bash
You have ONE folder: D:\povaly\

git checkout main
→ Files show: stable code (live version)

git checkout feature/reviews  
→ SAME folder, but files now include: ReviewController.php

Git physically replaces files when you switch branches!
```

**Important:** You DON'T have separate folders. Git changes the files for you.

---

### 💾 The 4 Steps of Saving Code

```
1. EDIT      → Change files in VS Code
2. STAGE     → Select which files to save (git add)
3. COMMIT    → Save locally with a message (git commit)
4. PUSH      → Upload to GitHub backup (git push)
```

**Real-life analogy:**
```
1. Write email (edit files)
2. Add attachments (stage files)
3. Click "Done" (commit)
4. Click "Send" (push to GitHub)
```

---

### 🏷️ TAG = Required, RELEASE = Optional

**TAG** (Required for deployment):
- Technical marker in code: "This is v1.1.0"
- Your deployment script uses this: `git checkout v1.1.0`
- **ALWAYS create when deploying to povaly.com**

**RELEASE** (Optional announcement):
- Pretty page on GitHub showing what changed
- Good for: announcements, changelogs, team communication
- **Skip for small bug fixes** (v1.0.1, v1.0.2)
- **Create for big updates** (v1.1.0, v1.2.0, v2.0.0)

**Think:**
```
Tag     = Barcode on product (technical, required)
Release = Launch party for product (optional, nice to have)
```

---

## When to Create a New Branch?

### ✅ Create New Branch When:

**Rule:** One branch = One clear task

```bash
# Each separate feature
git checkout -b feature/product-reviews
git checkout -b feature/customer-wishlist
git checkout -b feature/payment-gateway

# Each separate bug
git checkout -b bugfix/cart-calculation
git checkout -b bugfix/email-not-sending

# Each experiment
git checkout -b experiment/new-ui-design
```

### ❌ DON'T Create Branch For:

```bash
# Small fixes on same feature (just commit to existing branch)
feature/button          ❌ Too small
feature/button-color    ❌ Same feature
feature/button-text     ❌ Same feature
→ Should be ONE branch: feature/improve-checkout-ui

# Multiple unrelated features (split them!)
feature/everything      ❌ Too broad
→ Split into: feature/reviews, feature/wishlist, feature/cart-fix
```

### 🔄 Branch Lifecycle (Create → Use → Delete)

**Important:** Branches are temporary! Delete after merging!

```bash
# 1. Create branch
git checkout -b feature/reviews

# 2. Work on it (days/weeks)
git commit -m "Added review model"
git commit -m "Added review controller"
git commit -m "Added review views"

# 3. Merge to develop
git checkout develop
git merge feature/reviews

# 4. DELETE the branch (it's done!)
git branch -d feature/reviews  ← Clean up!

# 5. Next feature? NEW branch!
git checkout -b feature/wishlist
```

**Why delete?** 
- Keeps Git clean
- No confusion about which branch is active
- Forces you to merge completed work

### 🤔 Can I Reuse a Branch?

**Short answer:** NO! Delete and create new.

**Example of BAD practice:**
```bash
# First bug
git checkout -b bugfix/cart-issues
git commit -m "Fixed cart total"
git merge to develop

# Reuse same branch for different bug?
git checkout bugfix/cart-issues  ❌ Bad!
git commit -m "Fixed cart tax"   ❌ Confusing!

# Problems:
# - Branch name doesn't match content
# - Git history is messy
# - Hard to track what's in the branch
```

**Example of GOOD practice:**
```bash
# First bug
git checkout -b bugfix/cart-total
git commit -m "Fixed cart total"
git merge to develop
git branch -d bugfix/cart-total  ✅ Delete!

# Second bug? NEW branch!
git checkout -b bugfix/cart-tax  ✅ Clear!
git commit -m "Fixed cart tax"
git merge to develop
git branch -d bugfix/cart-tax    ✅ Delete!
```

---

## Our Branch Structure

### Our 3-Level Structure

```
┌─────────────────────────────────────────────────────┐
│ main                                                │
│ • Production code (live on povaly.com)              │
│ • Always stable, never broken                       │
│ • NEVER work directly here                          │
│ • Only receives tested code from develop            │
│ • Every deployment = new tag (v1.0.0, v1.1.0)       │
└─────────────────────────────────────────────────────┘
                    ↑
        Merge when tested on staging
                    ↑
┌─────────────────────────────────────────────────────┐
│ develop                                             │
│ • Staging code (live on stage.povaly.com)           │
│ • Testing ground for multiple features              │
│ • Can have bugs (that's okay, it's for testing!)    │
│ • Collect 3-5 features, test together               │
│ • When stable → merge to main                       │
└─────────────────────────────────────────────────────┘
         ↑              ↑              ↑
    Merge when      Merge when     Merge when
    complete        complete       complete
         ↑              ↑              ↑
┌──────────────┐ ┌──────────────┐ ┌──────────────┐
│ feature/     │ │ feature/     │ │ bugfix/      │
│ reviews      │ │ wishlist     │ │ cart-bug     │
│              │ │              │ │              │
│ Your work    │ │ Your work    │ │ Your work    │
│ happens here │ │ happens here │ │ happens here │
└──────────────┘ └──────────────┘ └──────────────┘
```

### Branch Types & Naming

**Format:** `type/short-description`

| Type | Purpose | Example |
|------|---------|---------|
| `feature/` | New functionality | `feature/product-reviews` |
| `bugfix/` | Fix non-critical bugs | `bugfix/cart-calculation` |
| `hotfix/` | Urgent production fixes | `hotfix/security-patch` |
| `experiment/` | Testing ideas (might not merge) | `experiment/new-checkout-ui` |

**Good names (clear and specific):**
```bash
feature/product-reviews
feature/customer-wishlist
feature/payment-gateway
bugfix/cart-total-wrong
bugfix/email-not-sending
hotfix/site-crash-fix
```

**Bad names (unclear):**
```bash
reviews          # Type missing - feature or bugfix?
fix              # Fix what?
test             # Testing what?
new-stuff        # What stuff?
my-work          # What work?
```

---

## The Complete Workflow

### 🚀 Overview: From Idea to Production

```
Idea → Branch → Code → Test Locally → Merge to develop → 
Test on Staging → Merge to main → Tag → Deploy → (Release)
```

### Step-by-Step Process

#### 1️⃣ Start Work (Create Branch)

```bash
# Always branch from develop (not main!)
git checkout develop
git pull origin develop

# Create your work branch
git checkout -b feature/product-reviews

# Start coding!
```

**Why branch from develop?**
- develop has latest tested code
- main might be old (waiting for deployment)
- Your feature builds on top of develop

---

#### 2️⃣ Work & Save (Edit, Commit, Push)

```bash
# Edit files in VS Code
# Save files (Ctrl+S)

# Commit every 1-2 hours:
git add .
git commit -m "feat: Added review model and migration"

# Keep working...
git add .
git commit -m "feat: Added review controller"

# Push to GitHub at end of day (backup!)
git push origin feature/product-reviews
```

**Tips:**
- Commit often (every small task done)
- Push daily (backup your work)
- Good commit messages help later

---

#### 3️⃣ Test Locally (Your Computer)

```bash
# Start Laravel server
php artisan serve

# Visit: http://localhost:8000
# Test your feature thoroughly!

# Found bug? Fix it:
# Edit code → Save → Reload browser → Test again

# Works? Commit:
git add .
git commit -m "fix: Fixed review form validation"
git push origin feature/product-reviews
```

**Important:** DON'T stop/restart server every time!
- Start server ONCE
- Edit code → Save → Reload browser ✅
- Only restart server when switching branches

---

#### 4️⃣ Merge to Develop (Collect Features)

```bash
# Feature complete and tested locally
# Time to merge to develop

git checkout develop
git pull origin develop
git merge feature/product-reviews
git push origin develop

# Delete branch (it's merged!)
git branch -d feature/product-reviews
```

**Why not go straight to main?**
- Collect 3-5 features in develop first
- Test them TOGETHER on staging
- Find integration bugs before production

---

#### 5️⃣ Test on Staging (Real Server)

```bash
# Deploy develop to stage.povaly.com
ssh user@stage.povaly.com
cd /var/www/stage
git pull origin develop
php artisan cms:theme:assets:publish martfury
php clear-cache.php

# Visit: https://stage.povaly.com
# Test everything for 2-3 days
# Let others test too if you have a team
```

**What to test:**
- Your new features work
- Old features still work (didn't break anything)
- Features work together (no conflicts)
- Real database, real files, real server

**Found bugs?**
```bash
# Create bugfix branch from develop
git checkout develop
git checkout -b bugfix/review-crash
# Fix → Commit → Merge back to develop
# Re-deploy to staging → Test again
```

---

#### 6️⃣ Merge to Main (Ready for Production)

```bash
# Everything works on staging!
# Time to go live

git checkout main
git pull origin main
git merge develop
git push origin main
```

**Now main has:**
- All tested features from develop
- Ready for production deployment
- But NOT deployed yet (need tag first!)

---

#### 7️⃣ Create Tag (REQUIRED!)

```bash
# Decide version number:
# - Bug fixes only? → v1.0.1 (PATCH)
# - New features? → v1.1.0 (MINOR)
# - Major changes? → v2.0.0 (MAJOR)

git tag -a v1.1.0 -m "v1.1.0 - Product Reviews

Added:
- Product review system
- Star ratings
- Customer feedback display

Fixed:
- Cart calculation bug
- Email sending issue"

git push origin v1.1.0
```

**This tag is what you deploy!**

---

#### 8️⃣ Deploy to Production (Go Live!)

```bash
# SSH to production server
ssh user@povaly.com
cd /var/www/povaly

# Deploy the TAG (not branch!)
git fetch --tags
git checkout v1.1.0

# Clear cache and publish assets
php artisan cms:theme:assets:publish martfury
php clear-cache.php

# Visit: https://povaly.com
# Verify everything works!
```

**Why deploy tag instead of branch?**
```
git checkout main        ❌ Which version of main?
git checkout v1.1.0      ✅ Exact version, clear!
```

---

#### 9️⃣ Create Release (Optional)

**For big updates (v1.1.0, v1.2.0, v2.0.0):**

1. Go to: https://github.com/goimranx/povaly/releases
2. Click: "Draft a new release"
3. Choose tag: v1.1.0
4. Title: "v1.1.0 - Product Reviews"
5. Description:
```markdown
## What's New 🎉
- Added product review system
- Added star ratings (1-5 stars)
- Added customer feedback display

## Bug Fixes 🐛
- Fixed cart total calculation
- Fixed email sending issue

## Deployment
Deploy with: `git checkout v1.1.0`
```
6. Click: "Publish release"

**For small fixes (v1.0.1, v1.0.2):** Skip release, just use tag!

---

#### 🔄 Update CHANGELOG (Good Practice)

```bash
# Edit CHANGELOG.md
git checkout main

# Add entry:
echo "## [1.1.0] - $(date +%Y-%m-%d)
### Added
- Product review system
- Star ratings

### Fixed
- Cart calculation bug
" >> CHANGELOG.md

git add CHANGELOG.md
git commit -m "docs: Update changelog for v1.1.0"
git push origin main
```

---

## Testing: Local vs Staging vs Production

### 🏠 Local Testing (Your Computer)

**Purpose:** Quick testing during development

**How:**
```bash
php artisan serve
# Visit: http://localhost:8000
```

**Test:**
- Does feature work?
- Basic functionality
- No syntax errors

**Speed:** FAST (seconds to test)

**Can break:** YES! Break things, fix them, no problem!

---

### 🏗️ Staging Testing (stage.povaly.com)

**Purpose:** Real-world testing before going live

**How:**
```bash
# Deploy develop branch
ssh stage.povaly.com
git pull origin develop
```

**Test:**
- Real server environment
- Real database
- Real files/images
- Multiple features together
- Performance

**Speed:** MEDIUM (hours/days of testing)

**Can break:** YES! It's for testing, breaking is okay!

---

### 🌍 Production Testing (povaly.com)

**Purpose:** Final verification after deployment

**How:**
```bash
# Deploy tag
ssh povaly.com
git checkout v1.1.0
```

**Test:**
- Quick smoke test
- Main features work
- Site is accessible
- No obvious errors

**Speed:** QUICK (5-10 minutes)

**Can break:** NO! This is live, customers see it!

---

### 🎯 Testing Flow

```
┌──────────────────────────────────────────┐
│ LOCAL (localhost)                        │
│ • Test while coding                      │
│ • Fix bugs immediately                   │
│ • Repeat 100 times                       │
│ • Until feature works ✅                 │
└──────────────────────────────────────────┘
              ↓ Feature works locally
┌──────────────────────────────────────────┐
│ STAGING (stage.povaly.com)               │
│ • Test on real server                    │
│ • Test with team                         │
│ • Test for 2-3 days                      │
│ • Find integration bugs                  │
│ • Until everything stable ✅             │
└──────────────────────────────────────────┘
              ↓ Staging stable
┌──────────────────────────────────────────┐
│ PRODUCTION (povaly.com)                  │
│ • Deploy tagged version                  │
│ • Quick verification                     │
│ • Should just work ✅                    │
│ • Customers use it                       │
└──────────────────────────────────────────┘
```

---

## Tags vs Releases (What's Required?)

### 🏷️ TAG = REQUIRED

**What:** Technical marker in Git pointing to specific commit

**Purpose:** Deployment reference

**Example:**
```bash
git tag -a v1.1.0 -m "Added reviews"
git push origin v1.1.0

# Later, deploy this exact version:
git checkout v1.1.0
```

**When:** EVERY production deployment

**Analogy:** Barcode on product - technical identifier

---

### 📢 RELEASE = OPTIONAL

**What:** Pretty page on GitHub with changelog

**Purpose:** Public announcement, documentation

**Example:**
- Visit: https://github.com/goimranx/povaly/releases
- See: Nice formatted list of what changed
- Download: Code for that version

**When:** Big updates only (v1.1.0, v1.2.0, v2.0.0)

**Analogy:** Launch party for product - optional celebration

---

### 📊 Tag vs Release Comparison

| Aspect | TAG | RELEASE |
|--------|-----|---------|
| **Required?** | ✅ YES | ⭕ Optional |
| **Purpose** | Deployment | Announcement |
| **Location** | Git repository | GitHub website |
| **Who uses** | Deployment script | Humans reading |
| **When create** | Every deployment | Big updates only |
| **Takes time** | 10 seconds | 2 minutes |

---

### 💡 Decision Guide

```
Deploying to production?
├─ Create TAG ✅ (Always required!)
│  └─ git tag -a v1.1.0 -m "message"
│
└─ Create RELEASE? (Optional, your choice)
   ├─ Big update (v1.1.0, v2.0.0)? 
   │  └─ YES ✅ Create release (2 mins)
   │
   └─ Small fix (v1.0.1)?
      └─ NO ⭕ Skip release (just tag is fine)
```

---

## Version Numbering Made Simple

### Format: vMAJOR.MINOR.PATCH

```
v1.2.3
│ │ │
│ │ └─ PATCH: Bug fixes (v1.0.0 → v1.0.1)
│ └─── MINOR: New features (v1.0.0 → v1.1.0)
└───── MAJOR: Big changes (v1.0.0 → v2.0.0)
```

---

### 🔧 PATCH: Bug Fixes (v1.0.0 → v1.0.1)

**What you did:**
- Fixed bugs
- Fixed typos
- Small improvements
- NO new features

**Examples:**
- Fixed cart calculation
- Fixed email sending
- Fixed broken link
- Changed button color

**Create release?** NO (too small)

**Example:**
```bash
git tag -a v1.0.1 -m "Fixed cart bug"
# Skip GitHub release
```

---

### ✨ MINOR: New Features (v1.0.0 → v1.1.0)

**What you did:**
- Added new feature
- Existing features still work
- Nothing removed

**Examples:**
- Added product reviews
- Added wishlist
- Added new payment method
- Added email notifications

**Create release?** YES (announce new features!)

**Example:**
```bash
git tag -a v1.1.0 -m "Added product reviews"
# Create GitHub release explaining new features
```

---

### 🚀 MAJOR: Big Changes (v1.0.0 → v2.0.0)

**What you did:**
- Complete redesign
- Removed old features
- Changed how things work
- Database structure changed

**Examples:**
- Complete UI redesign
- Changed from Laravel 10 to 11
- Removed old checkout, added new one
- Changed database tables

**Create release?** YES (important announcement!)

**Example:**
```bash
git tag -a v2.0.0 -m "Complete redesign"
# Create GitHub release with migration guide
```

---

### 🎯 Quick Decision Tree

```
What did you change?

├─ Only fixed bugs?
│  └─ PATCH: v1.0.0 → v1.0.1
│     Release: NO
│
├─ Added new features?
│  └─ MINOR: v1.0.0 → v1.1.0
│     Release: YES
│
└─ Major redesign/changes?
   └─ MAJOR: v1.0.0 → v2.0.0
      Release: YES
```

---

### 📅 Real Example Timeline

```
v1.0.0 - Oct 23, 2026 - Initial release
v1.0.1 - Oct 25, 2026 - Fixed cart bug (no release)
v1.0.2 - Oct 27, 2026 - Fixed email bug (no release)
v1.1.0 - Nov 5, 2026  - Added reviews ⭐ (with release)
v1.1.1 - Nov 8, 2026  - Fixed review bug (no release)
v1.2.0 - Dec 10, 2026 - Added wishlist ⭐ (with release)
v1.2.1 - Dec 15, 2026 - Fixed wishlist bug (no release)
v2.0.0 - Jan 15, 2027 - Complete redesign 🚀 (with release)
```

---

## Real-World Examples

### Example 1: Adding Product Reviews (New Feature)

**Goal:** Add product review system

**Steps:**

```bash
# Day 1: Start work
git checkout develop
git pull origin develop
git checkout -b feature/product-reviews

# Create files
php artisan make:model Review -m
php artisan make:controller ReviewController

# Edit files, then commit
git add .
git commit -m "feat: Added review model and migration"
git push origin feature/product-reviews

# Day 2: Continue work
# Add review form, display reviews
git add .
git commit -m "feat: Added review form and display"
git push origin feature/product-reviews

# Day 3: Test locally
php artisan serve
# Visit localhost:8000, test reviews
# Found bug? Fix it:
git add .
git commit -m "fix: Fixed review form validation"
git push origin feature/product-reviews

# Day 4: Merge to develop
git checkout develop
git merge feature/product-reviews
git push origin develop
git branch -d feature/product-reviews

# Deploy to staging
ssh stage.povaly.com
git pull origin develop
php clear-cache.php

# Test on stage.povaly.com for 2-3 days

# Day 7: Ready for production!
git checkout main
git merge develop
git push origin main

# Create tag
git tag -a v1.1.0 -m "v1.1.0 - Product Reviews

Added:
- Product review system
- Star ratings
- Customer feedback"

git push origin v1.1.0

# Deploy to production
ssh povaly.com
git checkout v1.1.0
php clear-cache.php

# Create GitHub release (yes, new feature!)
# Go to GitHub → Releases → Draft new release
```

**Version:** v1.0.0 → v1.1.0 (MINOR)  
**Release:** YES  
**Time:** 7 days (3 days dev, 3 days testing, 1 day deploy)

---

### Example 2: Fixing Cart Bug (Bug Fix)

**Goal:** Fix cart total calculation

**Steps:**

```bash
# Same day work
git checkout develop
git pull origin develop
git checkout -b bugfix/cart-calculation

# Fix the bug
# Edit: app/Http/Controllers/CartController.php
git add app/Http/Controllers/CartController.php
git commit -m "fix: Fixed cart total calculation"
git push origin bugfix/cart-calculation

# Test locally
php artisan serve
# Works? Merge!

git checkout develop
git merge bugfix/cart-calculation
git push origin develop
git branch -d bugfix/cart-calculation

# Deploy to staging, test for 1 day
ssh stage.povaly.com
git pull origin develop

# Next day: Production
git checkout main
git merge develop
git push origin main

git tag -a v1.0.1 -m "Fixed cart calculation bug"
git push origin v1.0.1

# Deploy
ssh povaly.com
git checkout v1.0.1
php clear-cache.php

# Skip GitHub release (small fix)
```

**Version:** v1.0.0 → v1.0.1 (PATCH)  
**Release:** NO  
**Time:** 2 days (1 day dev, 1 day testing)

---

### Example 3: URGENT! Site Down! (Hotfix)

**Goal:** Fix critical error causing site crash

**Steps:**

```bash
# EMERGENCY - branch from main, not develop!
git checkout main
git pull origin main
git checkout -b hotfix/critical-crash

# Fix FAST
# Edit: app/Exceptions/Handler.php
git add .
git commit -m "hotfix: Fixed critical error"

# Merge to main immediately (skip staging!)
git checkout main
git merge hotfix/critical-crash
git push origin main

git tag -a v1.0.2 -m "Emergency fix: site crash"
git push origin v1.0.2

# Deploy NOW
ssh povaly.com
git checkout v1.0.2
php clear-cache.php

# Also merge to develop (important!)
git checkout develop
git merge hotfix/critical-crash
git push origin develop

git branch -d hotfix/critical-crash
```

**Version:** v1.0.1 → v1.0.2 (PATCH)  
**Release:** NO (just fix it!)  
**Time:** 1 hour (emergency!)

---

### Example 4: Multiple Features Together

**Goal:** Add wishlist, improve search, fix 2 bugs

**Steps:**

```bash
# Week 1: Feature 1
git checkout -b feature/wishlist
# Work... commit... merge to develop
git checkout develop
git merge feature/wishlist

# Week 2: Feature 2  
git checkout -b feature/better-search
# Work... commit... merge to develop
git checkout develop
git merge feature/better-search

# Week 3: Bug fixes
git checkout -b bugfix/email-issue
# Work... commit... merge to develop
git checkout develop
git merge bugfix/email-issue

git checkout -b bugfix/image-upload
# Work... commit... merge to develop
git checkout develop
git merge bugfix/image-upload

# Test all together on staging for 3 days
# All features work well together!

# Deploy to production
git checkout main
git merge develop
git push origin main

git tag -a v1.2.0 -m "v1.2.0 - Multiple Improvements

Added:
- Customer wishlist feature
- Improved search with filters

Fixed:
- Email notification issue
- Image upload error"

git push origin v1.2.0

# Deploy
ssh povaly.com
git checkout v1.2.0

# Create release (yes, multiple new features!)
```

**Version:** v1.1.0 → v1.2.0 (MINOR)  
**Release:** YES  
**Time:** 3 weeks work + 3 days testing

---

## Common Questions Answered

### Q1: Do I need to stop `php artisan serve` when editing files?

**A:** NO! Just reload browser.

```bash
# Start server once
php artisan serve

# Then:
# Edit file → Save (Ctrl+S) → Reload browser (F5) ✅
# Edit file → Save → Reload browser ✅
# Repeat 1000 times!

# Only restart server when:
# - Switching branches
# - Changing .env or config/ files
# - Installing new packages
```

---

### Q2: Do I need to switch branches to see different code?

**A:** YES, but you don't need to switch often!

**Best practice:** Work on ONE branch for days/weeks.

```bash
# Monday-Friday: Only feature/reviews
git checkout feature/reviews
php artisan serve
# Work all week, never switch!

# Next week: New feature
Ctrl+C  # Stop server
git checkout feature/wishlist
php artisan serve
# Work all week on this
```

**Don't do this:**
```bash
# Switch 10 times a day ❌
git checkout feature/A  # 1 hour
git checkout feature/B  # 30 mins
git checkout feature/C  # 2 hours
# Confusing! Servers restarting! Lost context!
```

---

### Q3: Multiple branches at same time?

**A:** For solo developer: Use ONE branch at a time

**For advanced/team:** Use multiple folders or Git worktree

**Multiple folders (easy way):**
```bash
# Clone repo twice
git clone https://github.com/goimranx/povaly povaly-main
git clone https://github.com/goimranx/povaly povaly-feature

# Terminal 1:
cd povaly-main
git checkout main
php artisan serve --port=8000

# Terminal 2:
cd povaly-feature
git checkout feature/reviews  
php artisan serve --port=8001

# Now:
# localhost:8000 → main branch
# localhost:8001 → feature/reviews branch
# Switch in browser, not Git!
```

---

### Q4: How many features before merging to main?

**A:** Collect 3-5 features in develop, then merge all to main.

```bash
# Don't do this (too frequent):
feature/A → develop → main → v1.0.1 → deploy
feature/B → develop → main → v1.0.2 → deploy  
feature/C → develop → main → v1.0.3 → deploy
# 3 deployments! Too much work!

# Do this (batch them):
feature/A → develop
feature/B → develop
feature/C → develop
# Test all together on staging
develop → main → v1.1.0 → deploy
# 1 deployment with 3 features! ✅
```

**Exception:** Urgent bugs = deploy immediately

---

### Q5: Can I work directly on develop branch?

**A:** For solo work, yes! But feature branches are safer.

**Without feature branches:**
```bash
git checkout develop
# Edit files for feature A
git commit -m "Started feature A"
# Oh no! Urgent bug! Can't deploy develop (feature A half-done)
# Stuck! ❌
```

**With feature branches:**
```bash
git checkout feature/A
# Work on feature A (half-done)

# Urgent bug?
git checkout develop  # Clean!
git checkout -b bugfix/urgent
# Fix bug, merge, deploy! ✅
# feature/A still safe, continue later
```

---

### Q6: Forgot to create branch, worked on main directly!

**A:** Fix it:

```bash
# You're on main with uncommitted changes
git status  # Shows modified files

# Create branch and move changes there
git checkout -b feature/my-work  

# Your changes moved to new branch!
git add .
git commit -m "feat: My work"

# Go back to main (clean)
git checkout main
```

---

### Q7: When should I push to GitHub?

**A:** At least daily, more is better!

```bash
# Minimum: End of day
git push origin feature/my-work

# Better: Every few commits
git commit -m "Added model"
git commit -m "Added controller"  
git commit -m "Added views"
git push origin feature/my-work  # Backup!

# Best: After every commit (if internet is fast)
git commit -m "Added model"
git push origin feature/my-work
```

**Why:** GitHub is your backup! Computer crashes? Code is safe!

---

### Q8: Merge conflict! What do I do?

**A:** Don't panic! Git will show you.

```bash
git merge feature/reviews
# CONFLICT! Git says: fix these files

# Open conflicted file:
<<<<<<< HEAD
// Code from develop
=======
// Code from feature/reviews
>>>>>>> feature/reviews

# Choose what to keep:
// Final code (remove markers)

# Mark as resolved:
git add ConflictedFile.php
git commit -m "Merged feature/reviews"
```

**Better:** Avoid conflicts!
- Pull develop often
- Merge develop into your feature branch regularly
- Work on different files than teammates

---

### Q9: Need to undo last commit?

**A:** Use git reset

```bash
# Undo last commit (keep changes)
git reset HEAD~1

# Your files still changed, just uncommitted
# Edit more, then commit again

# Undo last commit (delete changes)
git reset --hard HEAD~1
# ⚠️ Changes gone forever!
```

---

### Q10: Accidentally committed to wrong branch?

**A:** Move commit to correct branch

```bash
# You committed to main (oops!)
git checkout main
git log  # See your commit hash (abc123)

# Create branch with that commit
git checkout -b feature/correct-branch

# Remove commit from main
git checkout main
git reset --hard HEAD~1

# Now commit is on correct branch!
git checkout feature/correct-branch
```

---

## Quick Command Reference

### Daily Commands

```bash
# Check current branch
git branch

# Check what changed
git status
git diff

# Save work
git add .
git commit -m "feat: Description"
git push origin branch-name

# Switch branch
git checkout branch-name

# Create new branch
git checkout -b feature/name

# Merge branch
git checkout target
git merge source

# Delete branch
git branch -d branch-name

# Pull latest
git pull origin branch-name
```

---

### Tagging Commands

```bash
# Create tag
git tag -a v1.1.0 -m "Description"

# Push tag
git push origin v1.1.0

# List tags
git tag

# Delete tag (local)
git tag -d v1.1.0

# Delete tag (remote)
git push origin --delete v1.1.0

# Checkout tag
git checkout v1.1.0
```

---

### Branch Commands

```bash
# List local branches
git branch

# List all branches (including remote)
git branch -a

# Create branch
git checkout -b feature/name

# Switch branch
git checkout branch-name

# Delete branch (safe)
git branch -d branch-name

# Delete branch (force)
git branch -D branch-name

# Rename branch
git branch -m old-name new-name
```

---

### Helpful Commands

```bash
# See commit history
git log
git log --oneline

# See who changed what
git blame file.php

# Undo uncommitted changes
git checkout -- file.php

# Undo all changes
git reset --hard

# See difference between branches
git diff main..develop

# Show tag info
git show v1.1.0
```

---

## Rules to Remember

### ✅ DO:

1. **Always work on feature branches**
   ```bash
   git checkout -b feature/name  ✅
   ```

2. **Test locally before merging**
   ```bash
   php artisan serve  # Test here first! ✅
   ```

3. **Commit often (every 1-2 hours)**
   ```bash
   git commit -m "Small progress"  ✅
   ```

4. **Push daily (backup)**
   ```bash
   git push origin feature/name  ✅
   ```

5. **Test on staging before production**
   ```bash
   develop → stage.povaly.com → test → main  ✅
   ```

6. **Always tag production deployments**
   ```bash
   git tag -a v1.1.0 -m "message"  ✅
   ```

7. **Delete branches after merging**
   ```bash
   git branch -d feature/name  ✅
   ```

8. **Good commit messages**
   ```bash
   git commit -m "feat: Added reviews"  ✅
   git commit -m "fix: Fixed cart bug"  ✅
   ```

---

### ❌ DON'T:

1. **Work directly on main**
   ```bash
   git checkout main  # edit files  ❌
   ```

2. **Skip staging testing**
   ```bash
   feature → main → production  ❌
   ```

3. **Deploy without tags**
   ```bash
   git checkout main  # deploy this  ❌
   git checkout v1.1.0  # deploy this  ✅
   ```

4. **Vague commit messages**
   ```bash
   git commit -m "updates"  ❌
   git commit -m "changes"  ❌
   git commit -m "fixes"  ❌
   ```

5. **Reuse branches**
   ```bash
   feature/stuff  # Used for 3 different features  ❌
   ```

6. **Switch branches constantly**
   ```bash
   # 10 switches per day  ❌
   # Work on one branch for days  ✅
   ```

---

## Summary: The Complete Flow

### Simple Version (Remember This!)

```
1. Create branch from develop
2. Code and test locally  
3. Merge to develop
4. Test on staging (stage.povaly.com)
5. Merge to main
6. Create tag (v1.x.x)
7. Deploy to production (povaly.com)
8. (Optional) Create GitHub release
```

---

### Detailed Version

```
┌─────────────────────────────────────────────┐
│ 1. START                                    │
│    git checkout develop                     │
│    git checkout -b feature/name             │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│ 2. WORK                                     │
│    Edit code in VS Code                     │
│    git add . && git commit -m "message"     │
│    (Repeat many times)                      │
│    git push origin feature/name             │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│ 3. TEST LOCALLY                             │
│    php artisan serve                        │
│    Visit localhost:8000                     │
│    Fix bugs, commit, test again             │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│ 4. MERGE TO DEVELOP                         │
│    git checkout develop                     │
│    git merge feature/name                   │
│    git push origin develop                  │
│    git branch -d feature/name               │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│ 5. TEST ON STAGING                          │
│    Deploy to stage.povaly.com               │
│    Test for 2-3 days                        │
│    Collect more features if needed          │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│ 6. MERGE TO MAIN                            │
│    git checkout main                        │
│    git merge develop                        │
│    git push origin main                     │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│ 7. CREATE TAG (Required!)                   │
│    git tag -a v1.1.0 -m "message"           │
│    git push origin v1.1.0                   │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│ 8. DEPLOY TO PRODUCTION                     │
│    ssh povaly.com                           │
│    git checkout v1.1.0                      │
│    php clear-cache.php                      │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│ 9. CREATE RELEASE (Optional)                │
│    For big updates only                     │
│    GitHub → Releases → Draft new            │
└─────────────────────────────────────────────┘
                    ↓
              ✅ DONE!
```

---

## For AI Assistants

When helping with Povaly development, always follow this workflow:

1. Create feature branches from develop (never work on main)
2. Test locally before merging
3. Merge features to develop first
4. Always create tags when merging to main (v1.x.x format)
5. Suggest GitHub releases for MINOR/MAJOR versions only
6. Update CHANGELOG.md with deployments
7. Use proper commit message format: `feat:`, `fix:`, `docs:`

---

## For New Team Members

### First Day:

```bash
# 1. Clone repository
git clone https://github.com/goimranx/povaly.git
cd povaly

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
# Edit .env with your database

# 4. Check out develop
git checkout develop

# 5. Read this guide!
```

### Your First Task:

```bash
# Small test task
git checkout -b feature/your-name-test
echo "# Test by Your Name" >> TEST.md
git add TEST.md
git commit -m "test: First commit"
git push origin feature/your-name-test

# You did it! Now delete:
git checkout develop
git branch -D feature/your-name-test
```

---

## Keep This Guide Updated!

This guide should evolve with the project. When you:
- Change workflow
- Add new processes
- Find better practices

Update this file! It's your single source of truth.

---

**Project:** Povaly E-commerce Platform  
**Repository:** https://github.com/goimranx/povaly  
**Base:** Botble CMS  
**Current Version:** v1.0.0  
**Last Updated:** October 23, 2026

---

**🎉 You now know the complete Git workflow for Povaly!**

Keep this guide handy - use it every time you work on the project.

Questions? Check "Common Questions Answered" section above!

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
