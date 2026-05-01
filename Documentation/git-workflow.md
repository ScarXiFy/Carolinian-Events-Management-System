# 🔄 Git Workflow

To ensure a smooth development process, we follow a feature-branch workflow.

## 1. Start a New Task
Never work directly on the `main` branch. Always create a new branch for every feature or fix.
```bash
# Update your local main first
git checkout main
git pull origin main

# Create and switch to a new branch
git checkout -b feature/your-feature-name
```

## 2. Save Your Progress
Commit often with descriptive messages.
```bash
# Stage all changes
git add .

# Commit with a meaningful message
git commit -m "feat: implement event registration form"
```

## 3. Push to GitHub
Upload your branch to the remote repository.
```bash
git push origin feature/your-feature-name
```

## 4. Create a Pull Request (PR)
Use the GitHub CLI to open a PR for review.
```bash
gh pr create --title "feat: your feature title" --body "Describe what you changed."
```

## 5. Merge and Cleanup
Once approved, merge the PR and clean up your branches.
```bash
# Merge the PR (choose Squash and Merge)
gh pr merge

# Sync your local main
git checkout main
git pull origin main

# Delete the old local branch
git branch -d feature/your-feature-name
```

---