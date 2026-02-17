# Weave Starter Plugin

A modern WordPress plugin scaffold for **Weave Digital Studio** and **HumanKind**. Built with namespaced PHP functions, React settings, Gutenberg blocks, and the WordPress Interactivity API.

This is a **living reference scaffold** — real, functional, testable code. Clone it, point an AI agent at `CLAUDE.md`, and say "rename this to X and adapt it for Y".

## Requirements

- WordPress 6.6+
- PHP 8.1+
- Node.js 20+ (for building)

## Quick Start

```bash
# Clone the scaffold
git clone https://github.com/weavedigitalstudio/weave-starter-plugin.git my-plugin
cd my-plugin

# Install dependencies and build
npm install && npm run build

# Symlink into WordPress Studio for testing
ln -s ~/Projects/my-plugin ~/Studio/my-site/wp-content/plugins/my-plugin
```

## What's Included

### Core (always present)
- **Custom Post Type** with meta fields and taxonomies
- **Gutenberg block** for meta field entry in the editor
- **React settings page** using `@wordpress/components`
- **GitHub updater** for self-hosted automatic updates
- **GitHub Actions** release workflow

### Optional Modules (removable)
- **DataViews admin screen** — `@wordpress/dataviews` powered item browser
- **Frontend display block** — Interactivity API with category filtering
- **Shortcodes** — legacy/compatibility layer for Beaver Builder
- **Admin columns** — custom sortable columns on the CPT list table

## Development

```bash
npm run start        # Watch all source files
npm run build        # Production build
npm run lint         # Lint JS and CSS
```

## Release Process

1. Bump the version in `weave-starter-plugin.php` and `package.json`
2. Update `CHANGELOG.md`
3. Commit and push to `main`
4. Tag and push: `git tag -a v1.x.x -m "Version 1.x.x" && git push origin main --tags`
5. GitHub Actions builds and publishes the release automatically

## Adapting This Scaffold

See `CLAUDE.md` for the complete find-and-replace table and architecture guide.

## Licence

GPL-2.0-or-later
