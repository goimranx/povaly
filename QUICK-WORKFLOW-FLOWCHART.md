# Quick Workflow Flowchart

> **Keep this open while working! Simple visual guide for what to do next.**

---

## 🎯 THE GOLDEN FLOW (Remember This!)

```
┌─────────────┐
│   START     │
│  New Work   │
└──────┬──────┘
       │
       ↓
┌────────────────────────────────┐
│ 1. CREATE BRANCH               │
│ git checkout -b feature/name   │
└──────┬─────────────────────────┘
       │
       ↓
┌────────────────────────────────┐
│ 2. EDIT FILES                  │
│ Work in VS Code                │
└──────┬─────────────────────────┘
       │
       ↓
┌────────────────────────────────┐
│ 3. SAVE TO GIT                 │
│ git add .                      │
│ git commit -m "description"    │
│ git push origin feature/name   │
└──────┬─────────────────────────┘
       │
       ↓
┌────────────────────────────────┐
│ 4. TEST ON STAGING             │
│ git checkout develop           │
│ git merge feature/name         │
│ git push origin develop        │
│ Deploy to stage.povaly.com     │
└──────┬─────────────────────────┘
       │
       ↓
┌────────────────────────────────┐
│ 5. GO LIVE                     │
│ git checkout main              │
│ git merge develop              │
│ git tag -a v1.1.0 -m "release" │
│ git push origin main v1.1.0    │
│ Deploy to povaly.com           │
└──────┬─────────────────────────┘
       │
       ↓
┌─────────────┐
│    DONE!    │
│   🎉 Live   │
└─────────────┘
```

---

## 📊 DETAILED FLOWCHART FOR DIFFERENT SCENARIOS

### Scenario 1: Adding New Feature (Reviews, Wishlist, etc.)

```
START
  ↓
┌──────────────────────────────────────┐
│ On Your Computer (Local)             │
├──────────────────────────────────────┤
│ 1. git checkout develop              │
│ 2. git pull origin develop           │
│ 3. git checkout -b feature/reviews   │
│                                      │
│ 4. Create files in VS Code:          │
│    - ReviewController.php            │
│    - Review.php                      │
│    - reviews.blade.php               │
│                                      │
│ 5. git add .                         │
│ 6. git commit -m "feat: reviews"    │
│ 7. git push origin feature/reviews   │
└──────────────────────────────────────┘
  ↓
┌──────────────────────────────────────┐
│ On Staging (stage.povaly.com)       │
├──────────────────────────────────────┤
│ 8. git checkout develop              │
│ 9. git merge feature/reviews         │
│ 10. git push origin develop          │
│                                      │
│ 11. SSH to stage.povaly.com:         │
│     cd /var/www/stage                │
│     git pull origin develop          │
│     php clear-cache.php              │
│                                      │
│ 12. TEST on stage.povaly.com         │
│     (2-3 days testing)               │
└──────────────────────────────────────┘
  ↓
┌──────────────────────────────────────┐
│ Go Live (povaly.com)                 │
├──────────────────────────────────────┤
│ 13. git checkout main                │
│ 14. git merge develop                │
│ 15. git push origin main             │
│                                      │
│ 16. Create version:                  │
│     git tag -a v1.1.0 -m "Reviews"  │
│     git push origin v1.1.0           │
│                                      │
│ 17. SSH to povaly.com:               │
│     cd /var/www/povaly               │
│     git pull origin main             │
│     php clear-cache.php              │
│                                      │
│ 18. GitHub → Create Release          │
└──────────────────────────────────────┘
  ↓
DONE! Feature Live! 🎉
```

---

### Scenario 2: Fixing Bug

```
START (Bug Found!)
  ↓
┌──────────────────────────────────────┐
│ Quick Bug Fix                        │
├──────────────────────────────────────┤
│ 1. git checkout develop              │
│ 2. git checkout -b bugfix/cart-bug   │
│                                      │
│ 3. Fix the bug in VS Code            │
│                                      │
│ 4. git add .                         │
│ 5. git commit -m "fix: cart bug"    │
│ 6. git push origin bugfix/cart-bug   │
└──────────────────────────────────────┘
  ↓
┌──────────────────────────────────────┐
│ Test on Staging                      │
├──────────────────────────────────────┤
│ 7. git checkout develop              │
│ 8. git merge bugfix/cart-bug         │
│ 9. git push origin develop           │
│                                      │
│ 10. Deploy to stage.povaly.com       │
│ 11. Test the fix (1 day)             │
└──────────────────────────────────────┘
  ↓
┌──────────────────────────────────────┐
│ Deploy Fix to Production             │
├──────────────────────────────────────┤
│ 12. git checkout main                │
│ 13. git merge develop                │
│ 14. git tag -a v1.0.1 -m "Bug fix"  │
│ 15. git push origin main v1.0.1      │
│                                      │
│ 16. Deploy to povaly.com             │
└──────────────────────────────────────┘
  ↓
DONE! Bug Fixed! 🐛✅
```

---

### Scenario 3: URGENT! Production is Broken!

```
START (EMERGENCY!)
  ↓
┌──────────────────────────────────────┐
│ Hotfix (Skip Staging!)               │
├──────────────────────────────────────┤
│ 1. git checkout main                 │
│ 2. git checkout -b hotfix/critical   │
│                                      │
│ 3. Fix FAST!                         │
│                                      │
│ 4. git add .                         │
│ 5. git commit -m "hotfix: critical"  │
└──────────────────────────────────────┘
  ↓
┌──────────────────────────────────────┐
│ Deploy Immediately                   │
├──────────────────────────────────────┤
│ 6. git checkout main                 │
│ 7. git merge hotfix/critical         │
│ 8. git tag -a v1.0.2 -m "Hotfix"    │
│ 9. git push origin main v1.0.2       │
│                                      │
│ 10. Deploy to povaly.com NOW!        │
└──────────────────────────────────────┘
  ↓
┌──────────────────────────────────────┐
│ Update Develop Too                   │
├──────────────────────────────────────┤
│ 11. git checkout develop             │
│ 12. git merge hotfix/critical        │
│ 13. git push origin develop          │
└──────────────────────────────────────┘
  ↓
DONE! Site Fixed! 🚨✅
```

---

## 🎨 BRANCH WORKFLOW VISUALIZATION

```
┌─────────────────────────────────────────────────────────┐
│                    BRANCH FLOW                          │
└─────────────────────────────────────────────────────────┘

Local (Your Computer)
    ↓ work
feature/reviews ───┐
feature/wishlist ──┤ merge when done
bugfix/cart ───────┘
    ↓
develop (Staging Branch)
    ↓ test on stage.povaly.com (2-3 days)
    ↓ merge when tested
main (Production Branch)
    ↓ deploy to povaly.com
    ↓ create tag
v1.0.0, v1.1.0, v1.2.0
```

---

## 📝 QUICK DECISION TREE

```
What are you doing?
    │
    ├─ Adding new feature (reviews, wishlist, checkout)
    │   └─> Use: feature/name
    │       Start from: develop
    │       Time: Days/weeks
    │       Test on: stage.povaly.com
    │       Version: v1.1.0 (minor)
    │
    ├─ Fixing bug (cart calculation, email not sending)
    │   └─> Use: bugfix/name
    │       Start from: develop
    │       Time: Hours/days
    │       Test on: stage.povaly.com
    │       Version: v1.0.1 (patch)
    │
    ├─ URGENT! Site is down!
    │   └─> Use: hotfix/name
    │       Start from: main
    │       Time: Minutes/hours
    │       Test on: local only (emergency)
    │       Version: v1.0.2 (patch)
    │
    └─ Experimenting with new design
        └─> Use: experiment/name
            Start from: develop
            Time: Days
            Test on: local
            Might not go live (just testing)
```

---

## 🔄 DAILY WORKFLOW (Simple Version)

### Morning (Start Work)

```bash
# 1. Get latest code
git checkout develop
git pull origin develop

# 2. Create your work branch
git checkout -b feature/today-work

# 3. Start coding in VS Code
```

### During Day (Save Your Work)

```bash
# Every hour or when you finish something:
git add .
git commit -m "What I did"
git push origin feature/today-work
```

### End of Day (Backup)

```bash
# Make sure everything is pushed
git push origin feature/today-work

# Tomorrow you can continue from where you left off
```

### When Feature Complete (Deploy)

```bash
# Merge to develop
git checkout develop
git merge feature/today-work
git push origin develop

# Test on stage.povaly.com

# If good, merge to main
git checkout main
git merge develop
git tag -a v1.1.0 -m "New feature"
git push origin main v1.1.0

# Deploy to povaly.com
```

---

## 🚀 THREE ENVIRONMENTS FLOW

```
┌─────────────────────────────────────────┐
│ 1. LOCAL (Your Computer)               │
│    D:\povaly\                          │
│                                        │
│    Branch: feature/reviews             │
│    URL: http://localhost               │
│    Purpose: Development                │
│    When: Always                        │
└─────────────────────────────────────────┘
         ↓ git push, merge to develop
┌─────────────────────────────────────────┐
│ 2. STAGING (Test Server)               │
│    /var/www/stage.povaly.com           │
│                                        │
│    Branch: develop                     │
│    URL: https://stage.povaly.com       │
│    Purpose: Testing                    │
│    When: Before going live             │
└─────────────────────────────────────────┘
         ↓ git merge to main, tag version
┌─────────────────────────────────────────┐
│ 3. PRODUCTION (Live Site)              │
│    /var/www/povaly.com                 │
│                                        │
│    Branch: main (tagged v1.0.0)        │
│    URL: https://povaly.com             │
│    Purpose: Real customers             │
│    When: Only tested code              │
└─────────────────────────────────────────┘
```

---

## 📋 CHECKLIST FOR GOING LIVE

### ✅ Before Going Live Checklist

```
Local (Your Computer):
  ☐ Feature works on localhost
  ☐ No errors in browser console
  ☐ Code committed and pushed
  
Staging (stage.povaly.com):
  ☐ Feature deployed to staging
  ☐ Tested for 2-3 days
  ☐ No bugs found
  ☐ Team/client approved
  
Production (povaly.com):
  ☐ Database backup created
  ☐ Merged to main branch
  ☐ Version tagged (v1.1.0)
  ☐ Deployed to production
  ☐ Cache cleared
  ☐ Final test on live site
  ☐ GitHub release created
```

---

## 🎯 VERSION NUMBERS GUIDE

```
v1.0.0
│ │ │
│ │ └─ PATCH (bug fixes)
│ │    v1.0.0 → v1.0.1 → v1.0.2
│ │    What: Small bug fixes
│ │    When: Weekly/as needed
│ │    
│ └─── MINOR (new features)
│      v1.0.0 → v1.1.0 → v1.2.0
│      What: New features added
│      When: Monthly/when feature ready
│      
└───── MAJOR (big changes)
       v1.0.0 → v2.0.0 → v3.0.0
       What: Big redesign, breaks compatibility
       When: Yearly/major updates

Examples:
  v1.0.0 - First release
  v1.0.1 - Fixed cart bug
  v1.0.2 - Fixed email bug
  v1.1.0 - Added reviews feature
  v1.2.0 - Added wishlist feature
  v2.0.0 - Complete redesign
```

---

## 🔥 COMMON COMMANDS (Keep This Handy!)

### Every Day Commands

```bash
# Check what branch you're on
git branch --show-current

# See all branches
git branch

# Create new branch
git checkout -b feature/name

# Switch to branch
git checkout branch-name

# Save your work
git add .
git commit -m "description"
git push origin branch-name

# Get latest code
git pull origin develop
```

### When Merging

```bash
# Merge feature to develop
git checkout develop
git merge feature/name
git push origin develop

# Merge develop to main
git checkout main
git merge develop
git push origin main
```

### When Creating Version

```bash
# Create tag
git tag -a v1.1.0 -m "Added reviews"
git push origin v1.1.0

# List all tags
git tag

# Delete tag (if mistake)
git tag -d v1.1.0
git push origin :refs/tags/v1.1.0
```

---

## 🎓 REMEMBER THESE GOLDEN RULES

### Rule 1: Never Work on Main
```
❌ git checkout main (edit files)
✅ git checkout -b feature/name (edit files)
```

### Rule 2: Always Test on Staging First
```
❌ feature → main → povaly.com
✅ feature → develop → stage.povaly.com → test → main → povaly.com
```

### Rule 3: Commit Often, Push Often
```
❌ Work 3 days → one commit
✅ Work 1 hour → commit → push
```

### Rule 4: Good Messages
```
❌ git commit -m "updates"
✅ git commit -m "feat: Added review form validation"
```

### Rule 5: One Branch = One Task
```
❌ feature/everything (reviews + wishlist + cart fix)
✅ feature/reviews (only reviews)
✅ feature/wishlist (only wishlist)
✅ bugfix/cart (only cart fix)
```

---

## 📱 SMARTPHONE ANALOGY (Easy to Remember!)

```
feature/reviews = App you're developing
develop = Beta testing app on test phone
main = App submitted to App Store
v1.0.0 = App published on App Store
Release = Public announcement

Your workflow:
1. Build app (feature branch)
2. Test on test phone (develop → staging)
3. Submit to store (merge to main)
4. Version number (v1.1.0 tag)
5. Published (GitHub release)
6. People download it (deploy to povaly.com)
```

---

## 🎯 ONE-PAGE REFERENCE

```
┌────────────────────────────────────────────────────┐
│         POVALY WORKFLOW CHEAT SHEET                │
├────────────────────────────────────────────────────┤
│                                                    │
│ BRANCHES:                                          │
│   main     = Production (povaly.com)              │
│   develop  = Staging (stage.povaly.com)           │
│   feature/* = New features                        │
│   bugfix/* = Bug fixes                            │
│   hotfix/* = Emergency fixes                      │
│                                                    │
│ START WORK:                                        │
│   git checkout develop                             │
│   git pull origin develop                          │
│   git checkout -b feature/name                     │
│                                                    │
│ SAVE WORK:                                         │
│   git add .                                        │
│   git commit -m "description"                      │
│   git push origin feature/name                     │
│                                                    │
│ TEST:                                              │
│   git checkout develop                             │
│   git merge feature/name                           │
│   git push origin develop                          │
│   Deploy to stage.povaly.com                       │
│                                                    │
│ GO LIVE:                                           │
│   git checkout main                                │
│   git merge develop                                │
│   git tag -a v1.1.0 -m "release"                  │
│   git push origin main v1.1.0                      │
│   Deploy to povaly.com                             │
│                                                    │
│ VERSIONS:                                          │
│   v1.0.1 = Bug fix                                │
│   v1.1.0 = New feature                            │
│   v2.0.0 = Major update                           │
│                                                    │
└────────────────────────────────────────────────────┘
```

---

## 🆘 STUCK? USE THIS DECISION TREE

```
Q: What am I doing?
├─ Starting new work
│  └─> git checkout develop
│      git checkout -b feature/name
│
├─ Continuing yesterday's work
│  └─> git checkout feature/name
│      git pull origin feature/name
│
├─ Want to test on staging
│  └─> git checkout develop
│      git merge feature/name
│      git push origin develop
│
├─ Want to go live
│  └─> git checkout main
│      git merge develop
│      git tag -a v1.1.0 -m "release"
│      git push origin main v1.1.0
│
└─ Not sure what branch I'm on
   └─> git branch --show-current
```

---

**🎉 THAT'S IT! Keep this file open and follow the flow!**

Print this if needed. Stick it on your wall. This is your roadmap! 🗺️
