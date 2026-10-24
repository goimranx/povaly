# Complete Git Workflow Guide for Povaly

> **For:** Beginners who want to understand Git properly  
> **Goal:** Professional development workflow like Facebook, Google, Amazon

---

## Table of Contents

1. [Understanding Git Terms](#understanding-git-terms)
2. [Branch Structure](#branch-structure)
3. [Daily Workflow](#daily-workflow)
4. [Complete Example](#complete-example)
5. [Commands Cheat Sheet](#commands-cheat-sheet)
6. [How to Remember Branches](#how-to-remember-branches)

---

## Understanding Git Terms

### 1. STAGE (Prepare Files)

**What it is:** Selecting which files to save

**Real-life example:**
```
You're packing for a trip:
- Put clothes in pile (stage) ← selecting what to pack
- Put pile in suitcase (commit) ← actually packing
```

**In Git:**
```bash
# You edited: ProductController.php, CartController.php, README.md
# But you only want to save ProductController.php

git add ProductController.php  ← STAGE (select this file)
# Now ProductController.php is in the "staging area" (the pile)
```

**Visual:**
```
Your Computer Files:
  ProductController.php (edited) ─┐
  CartController.php (edited)     │ You changed these
  README.md (edited)             ─┘

After: git add ProductController.php

Staging Area (Ready to Save):
  ✓ ProductController.php  ← Will be saved

Not Staged (Won't be saved yet):
  CartController.php
  README.md
```

---

### 2. COMMIT (Save Your Work)

**What it is:** Saving staged files with a message

**Real-life example:**
```
Taking a photo:
- Stage = Arrange people for photo
- Commit = Click camera button (photo is saved)
```

**In Git:**
```bash
git commit -m "Added product reviews feature"
```

This saves all STAGED files with message "Added product reviews feature"

**Visual:**
```
Before Commit:
Staging Area:
  ✓ ProductController.php (changes not saved permanently)

After Commit:
Git History:
  ✓ Commit #123: "Added product reviews feature"
    - ProductController.php (changes saved forever)
```

**Important:** Commit = Save to your LOCAL computer only (not GitHub yet)

---

### 3. PUSH (Upload to GitHub)

**What it is:** Uploading commits from your computer to GitHub

**Real-life example:**
```
Email:
- Write email (commit) ← saved on your computer
- Click Send (push) ← uploaded to email server
```

**In Git:**
```bash
git push origin feature/reviews
```

**Visual:**
```
Your Computer:                    GitHub:
┌─────────────────┐              ┌─────────────────┐
│ Commits:        │   git push   │ Commits:        │
│ - Review form   │  ─────────>  │ - Review form   │
│ - Review model  │              │ - Review model  │
└─────────────────┘              └─────────────────┘
```

---

### 4. TAG (Mark Special Version)

**What it is:** Marking a commit as "v1.0.0" or "v1.1.0"

**Real-life example:**
```
iPhone releases:
- Many commits daily (bug fixes, features)
- Some commits get tagged: iPhone 14, iPhone 15
- Tags = Official releases
```

**In Git:**
```bash
git tag -a v1.0.0 -m "First stable release"
git push origin v1.0.0
```

**Visual:**
```
Git History:
Commit 1: "Fixed typo"
Commit 2: "Added reviews"
Commit 3: "Fixed bug" ← git tag v1.0.0 (marked as release)
Commit 4: "Changed color"
Commit 5: "Added wishlist" ← git tag v1.1.0 (next release)
```

---

### 5. RELEASE (Published on GitHub)

**What it is:** Official version published on GitHub releases page

**Real-life example:**
```
Movie:
- Filming (commits)
- Editing (more commits)
- Theater Release (GitHub Release) ← Public announcement
```

**In Git:**
1. Create tag: `git tag -a v1.0.0 -m "Release"`
2. Push tag: `git push origin v1.0.0`
3. Go to GitHub → Releases → "Draft new release"
4. Select tag v1.0.0, add notes, publish

**Visual:**
```
https://github.com/goimranx/povaly/releases

v1.0.0 - First Stable Release (Latest)
  Released on Oct 23, 2026
  - Added reviews
  - Fixed bugs
  [Download Source Code]

v1.1.0 - Wishlist Feature  
  Released on Nov 5, 2026
  - Added wishlist
  - Performance improvements
  [Download Source Code]
```

---

## Branch Structure

### The 3 Main Branches

```
main            ← Production (live site: povaly.com)
  ↑
develop         ← Staging (test site: stage.povaly.com)
  ↑
feature/reviews ← Your work (your computer)
```

### Why "feature/reviews" not just "reviews"?

**Organization! Like folders:**

```
Good Organization:
  feature/reviews
  feature/wishlist
  feature/checkout
  bugfix/cart
  bugfix/login
  hotfix/security

You know:
  - feature/ = new features
  - bugfix/ = bug fixes
  - hotfix/ = urgent production fixes
```

**You can use simple names:**
```
reviews
wishlist
cart-bug

But harder to remember what each branch is for!
```

---

## Branch Naming Convention

### Standard Format:

```
type/description

Examples:
  feature/product-reviews
  feature/customer-wishlist
  bugfix/cart-calculation
  bugfix/email-sending
  hotfix/security-patch
```

### Types:

- **feature/** - New features
- **bugfix/** - Bug fixes
- **hotfix/** - Urgent production fixes
- **experiment/** - Testing new ideas
- **refactor/** - Code improvements (no new features)

### Why Use This?

```bash
git branch

# Without categories:
reviews
cart
wishlist
email
login

# What are these? Features? Bugs? No idea!

# With categories:
feature/reviews
feature/wishlist
bugfix/cart
bugfix/email
bugfix/login

# Clear! You know what each branch does!
```

---

## Daily Workflow

### Scenario: You Want to Add Reviews Feature

#### Step 1: STAGE (Select Files)

```bash
# You edited these files:
# - app/Http/Controllers/ReviewController.php (new)
# - app/Models/Review.php (new)
# - resources/views/reviews.blade.php (new)

# Select all files to save:
git add .

# Or select specific files:
git add app/Http/Controllers/ReviewController.php
git add app/Models/Review.php
```

**What happened:** Files moved to "staging area" (ready to save)

#### Step 2: COMMIT (Save)

```bash
git commit -m "feat: Added review system"
```

**What happened:** Files saved to Git history on your computer

#### Step 3: PUSH (Upload)

```bash
git push origin feature/reviews
```

**What happened:** Your commits uploaded to GitHub

#### Step 4: Later - TAG (Mark Release)

```bash
# When reviews feature is done and tested:
git checkout main
git merge develop
git tag -a v1.1.0 -m "Added product reviews"
git push origin v1.1.0
```

**What happened:** Marked this commit as version 1.1.0

#### Step 5: RELEASE (Publish)

1. Go to GitHub: https://github.com/goimranx/povaly/releases
2. Click "Draft a new release"
3. Select tag: v1.1.0
4. Add description
5. Publish

**What happened:** Public announcement of v1.1.0 release

---

## Complete Example: Adding Reviews Feature

### Day 1: Start Work

```bash
# 1. Start from clean develop
git checkout develop
git pull origin develop

# 2. Create feature branch
git checkout -b feature/product-reviews

# 3. Create ReviewController.php in VS Code
# Save the file

# 4. STAGE the file (select it)
git add app/Http/Controllers/ReviewController.php

# 5. COMMIT (save)
git commit -m "feat: Added review controller"

# 6. PUSH to GitHub (backup)
git push origin feature/product-reviews
```

**Status:**
- ✓ Work saved on your computer
- ✓ Work backed up on GitHub
- ✗ Not on develop yet
- ✗ Not on main yet
- ✗ Not live on website yet

### Day 2: Continue Work

```bash
# Still on feature/product-reviews branch

# 7. Create Review.php model
# Save the file

# 8. STAGE + COMMIT + PUSH
git add app/Models/Review.php
git commit -m "feat: Added review model"
git push origin feature/product-reviews
```

### Day 3: Feature Complete

```bash
# 9. Merge to develop (for testing on stage.povaly.com)
git checkout develop
git merge feature/product-reviews
git push origin develop

# 10. Deploy to stage.povaly.com
# SSH to staging server:
git pull origin develop
php clear-cache.php

# 11. Test on stage.povaly.com for 2-3 days
```

### Day 6: Ready for Production

```bash
# 12. Merge to main
git checkout main
git merge develop
git push origin main

# 13. Create TAG (version)
git tag -a v1.1.0 -m "v1.1.0 - Product Reviews Feature

Added:
- Review controller
- Review model
- Review display on product pages"

git push origin v1.1.0

# 14. Deploy to povaly.com
# SSH to production:
git pull origin main
php clear-cache.php
```

### Day 7: Create Release

1. Go to: https://github.com/goimranx/povaly/releases
2. Click "Draft a new release"
3. Select tag: v1.1.0
4. Title: "v1.1.0 - Product Reviews"
5. Description:
```
## What's New
- ✨ Added product review system
- 📝 Customers can now leave reviews
- ⭐ Star ratings on product pages

## Files Changed
- Added ReviewController
- Added Review model
- Updated product pages
```
6. Click "Publish release"

**Status:**
- ✓ Work saved on your computer
- ✓ Work backed up on GitHub
- ✓ Merged to develop (tested on stage.povaly.com)
- ✓ Merged to main
- ✓ Tagged as v1.1.0
- ✓ Live on povaly.com
- ✓ Published as GitHub Release

---

## How to Remember Branches

### Simple System:

#### Method 1: List Branches Often

```bash
git branch

Output:
  main
  develop
* feature/reviews        ← * means you're here
  feature/wishlist
  bugfix/cart
```

#### Method 2: Write Them Down

Keep a file: `BRANCHES.txt`
```
main - Production (povaly.com)
develop - Staging (stage.povaly.com)

feature/reviews - Adding product reviews (in progress)
feature/wishlist - Adding wishlist (paused)
bugfix/cart - Fixing cart total bug (completed, merged)
```

#### Method 3: Use GitHub

Go to: https://github.com/goimranx/povaly/branches

You'll see all branches with:
- Last commit date
- Who worked on it
- Status (merged or not)

#### Method 4: Branch Description

When creating branch:
```bash
git checkout -b feature/reviews

# Add description:
git config branch.feature/reviews.description "Product review system - allows customers to rate products"

# View description:
git config branch.feature/reviews.description
```

#### Method 5: Delete Old Branches

```bash
# Feature done and merged? Delete it!
git branch -d feature/reviews

# Now you only see active branches
git branch
  main
  develop
  feature/wishlist  ← Only working branches remain
```

---

## Commands Cheat Sheet

### Daily Commands

```bash
# See all branches
git branch

# See current branch
git branch --show-current

# Create and switch to new branch
git checkout -b feature/my-feature

# Switch to existing branch
git checkout main

# Stage files (select to save)
git add .                    # All files
git add file.php            # One file

# Commit (save)
git commit -m "Description"

# Push (upload)
git push origin branch-name

# Pull (download)
git pull origin branch-name

# Merge branch
git checkout main
git merge feature/reviews

# Create tag
git tag -a v1.0.0 -m "Release 1.0.0"
git push origin v1.0.0

# Delete branch
git branch -d branch-name

# See what changed
git status
git diff
```

### Branch Management

```bash
# List all branches
git branch -a

# List only remote branches (on GitHub)
git branch -r

# Rename branch
git branch -m old-name new-name

# Delete branch
git branch -d branch-name      # Safe (prevents deleting unmerged)
git branch -D branch-name      # Force (deletes even if unmerged)

# See branch details
git show-branch
```

### Tag Management

```bash
# List tags
git tag

# List tags with messages
git tag -n

# Create tag
git tag -a v1.0.0 -m "Message"

# Push tag
git push origin v1.0.0

# Push all tags
git push origin --tags

# Delete tag locally
git tag -d v1.0.0

# Delete tag on GitHub
git push origin :refs/tags/v1.0.0

# Checkout specific tag
git checkout v1.0.0
```

---

## Visual Summary

### The Complete Flow

```
┌─────────────────────────────────────────────────────┐
│ 1. WORK (Edit Files)                                │
│    Edit ProductController.php in VS Code            │
└─────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────┐
│ 2. STAGE (Select Files)                             │
│    git add ProductController.php                    │
│    (File ready to save)                             │
└─────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────┐
│ 3. COMMIT (Save)                                    │
│    git commit -m "Added reviews"                    │
│    (Saved on your computer)                         │
└─────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────┐
│ 4. PUSH (Upload)                                    │
│    git push origin feature/reviews                  │
│    (Uploaded to GitHub)                             │
└─────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────┐
│ 5. MERGE (Combine)                                  │
│    git checkout main                                │
│    git merge feature/reviews                        │
│    (Feature code now in main)                       │
└─────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────┐
│ 6. TAG (Mark Version)                               │
│    git tag -a v1.1.0 -m "Reviews feature"          │
│    (Marked as version 1.1.0)                        │
└─────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────┐
│ 7. RELEASE (Publish)                                │
│    GitHub → Releases → Publish v1.1.0              │
│    (Public announcement)                            │
└─────────────────────────────────────────────────────┘
```

---

## Quick Reference: What Goes Where

| Action | Stays on Computer | Goes to GitHub | Goes to Website |
|--------|------------------|----------------|-----------------|
| Edit files | ✓ | ✗ | ✗ |
| git add | ✓ | ✗ | ✗ |
| git commit | ✓ | ✗ | ✗ |
| git push | ✓ | ✓ | ✗ |
| git merge to main | ✓ | ✓ (after push) | ✗ |
| Deploy/git pull on server | ✓ | ✓ | ✓ |

---

## Common Mistakes to Avoid

### ❌ Mistake 1: Forgetting to Commit
```bash
# You edited files
git push origin feature/reviews
# Error! Nothing to push (you didn't commit)

# FIX:
git add .
git commit -m "Description"
git push origin feature/reviews
```

### ❌ Mistake 2: Committing Without Staging
```bash
git commit -m "Description"
# Nothing happens! (no files staged)

# FIX:
git add .  # Stage first
git commit -m "Description"
```

### ❌ Mistake 3: Working on Wrong Branch
```bash
# You're on main, editing files
# OH NO! Don't edit main directly!

# FIX:
git stash  # Save changes temporarily
git checkout develop
git checkout -b feature/my-work
git stash pop  # Bring changes back
```

### ❌ Mistake 4: Forgetting to Push
```bash
# Work on your computer all day
# Computer crashes - all work lost!

# FIX: Push often!
git add .
git commit -m "Work progress"
git push origin feature/my-work  # Backed up on GitHub!
```

---

## Pro Tips

### Tip 1: Commit Often
```bash
# Bad:
(Work for 3 days, one commit)

# Good:
(Work 1 hour, commit)
(Work 1 hour, commit)
(Work 1 hour, commit)
```

### Tip 2: Good Commit Messages
```bash
# Bad:
git commit -m "updates"
git commit -m "fixes"
git commit -m "changes"

# Good:
git commit -m "feat: Added review form validation"
git commit -m "fix: Fixed cart total calculation bug"
git commit -m "style: Changed button color to green"
```

### Tip 3: Check Status Before Committing
```bash
git status  # See what changed
git diff    # See actual changes
git add .
git commit -m "Description"
```

### Tip 4: Use .gitignore
```bash
# Don't commit:
.env (passwords!)
node_modules/ (huge folder)
vendor/ (huge folder)

# Create .gitignore:
.env
node_modules/
vendor/
*.log
```

---

## Remember This Simple Flow

**Edit → Stage → Commit → Push → Merge → Tag → Release**

```
1. Edit files in VS Code
2. git add . (stage)
3. git commit -m "message" (save)
4. git push origin branch-name (backup)
5. git merge (combine branches)
6. git tag v1.0.0 (mark version)
7. GitHub Release (publish)
```

**That's it! Everything else is just variations of this!** 🎉

---

## Your Simple Workflow Summary

### Starting Work:
```bash
git checkout develop
git pull
git checkout -b feature/my-work
```

### During Work:
```bash
# Edit files
git add .
git commit -m "Description"
git push origin feature/my-work
```

### Finishing Work:
```bash
git checkout develop
git merge feature/my-work
git push origin develop
# Test on stage.povaly.com

git checkout main
git merge develop
git tag -a v1.1.0 -m "Release"
git push origin main v1.1.0
# Deploy to povaly.com
```

**Done!** 🎯
