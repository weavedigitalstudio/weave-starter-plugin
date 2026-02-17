# CLAUDE.md — Agent Instructions for weave-starter-plugin

## What This Is

A WordPress plugin scaffold for Weave Digital Studio / HumanKind Funeral Websites.
This is REAL, WORKING CODE — not templates. Clone this repo, tell an agent what to
build, and it handles the renaming and adaptation.

These plugins are for internal use across our client sites — they are NOT on the
wordpress.org repository. Updates are delivered via a custom GitHub updater class.

For general WordPress development best practices, refer to the WordPress Agent Skills
(wp-plugin-development, wp-block-development, wp-interactivity-api). This file covers
project-specific conventions only.

## Quick Start for AI Agents

1. Clone this directory to your new plugin name
2. Read this ENTIRE file before making changes
3. Perform the find-and-replace steps below (ORDER MATTERS)
4. Decide which optional modules to keep or remove
5. Modify CPT fields, blocks, and config to match requirements
6. Run `npm install && npm run build`
7. Test activation in WordPress (or use blueprint.json in Playground)

## Architecture

- **PHP**: Namespaced functions in `inc/`, not classes (exception: GitHub updater)
- **JS**: React with `@wordpress/components`, `@wordpress/dataviews`, `@wordpress/interactivity`
- **Build**: `@wordpress/scripts` — separate blocks and scripts builds
- **Settings**: `register_setting` with `show_in_rest` → React reads/writes via `/wp/v2/settings`
- **Meta fields**: `register_post_meta` with `show_in_rest` → block reads/writes via `useEntityProp`
- **Admin UI**: Only `@wordpress/components` — no custom CSS, no HTML forms
- **Namespace pattern**: `WeaveStarterPlugin\FeatureName`

### Data Flow

```
register_post_meta (inc/post-types.php)
    ↓
WordPress REST API (/wp/v2/starter-items)
    ↓
useEntityProp (src/blocks/starter-item-meta/edit.js)     → editor meta entry
useEntityRecords (src/js/admin/hooks/useItems.js)         → DataViews admin
WP_Query (src/blocks/starter-item-frontend/render.php)    → frontend display
```

## Renaming — Find and Replace

IN THIS EXACT ORDER (longest patterns first to avoid partial matches):

### Phase 1: Full-name patterns
1. `weave-starter-plugin` → `your-plugin-slug`
2. `weave_starter_plugin` → `your_plugin_slug`
3. `WEAVE_STARTER_PLUGIN` → `YOUR_PLUGIN_SLUG`
4. `WeaveStarterPlugin` → `YourPluginName`
5. `Weave_Starter_Plugin` → `Your_Plugin_Name`
6. `Weave Starter Plugin` → `Your Plugin Name`

### Phase 2: Prefix patterns
7. `weave_starter` → `your_prefix`
8. `WEAVE_STARTER` → `YOUR_PREFIX`
9. `WeaveStarter` → `YourPrefix`
10. `weave-starter` → `your-prefix`
11. `Weave Starter` → `Your Plugin`

### Phase 3: CPT-specific patterns
12. `starter_item` → `your_cpt_slug`
13. `Starter Item` → `Your CPT Label`
14. `Starter Items` → `Your CPT Labels`
15. `starter-item-category` → `your-taxonomy`
16. `starter-item-tag` → `your-tag-taxonomy`
17. `starter-item` → `your-cpt-slug`

### Phase 4: File and directory renames
18. Rename `weave-starter-plugin.php` → `your-plugin-slug.php`
19. Rename block directories in `src/blocks/` if CPT name changed

### Phase 5: Organisation-specific values
20. `weave.co.nz` → `your-domain.com`
21. `weavedigitalstudio` → `your-github-org`

**For HumanKind funeral plugins:** Use `hk_` / `hk-` / `HK_` prefixes. GitHub org: `HumanKind-nz`. BunnyCDN path: `weave-hk-github.b-cdn.net/humankind/`

**For Weave Digital Studio plugins:** Use `weave_` / `weave-` / `WEAVE_` prefixes (already the scaffold default). GitHub org: `weavedigitalstudio`. BunnyCDN path: `weave-hk-github.b-cdn.net/weave/`

After renaming: `npm install && npm run build` to verify.

## Optional Modules

Files marked "OPTIONAL MODULE" in their header comment can be removed. Also remove their `require_once` from `weave-starter-plugin.php` and the webpack entry from `webpack.scripts.config.js`.

| Module | PHP | JS | Webpack Entry |
|--------|-----|-----|---------------|
| DataViews admin | `inc/admin-page.php` | `src/js/admin/` | `admin/index` |
| Frontend block | — | `src/blocks/starter-item-frontend/` | — (blocks build) |
| Legacy shortcode output | `inc/shortcodes.php` | — | — |
| Admin columns | `inc/admin-columns.php` | — | — |

**No-CPT plugins (e.g. utility plugins like weave-cache-purge-helper):** Remove `inc/post-types.php`, both block directories in `src/blocks/`, `inc/shortcodes.php`, `inc/admin-columns.php`, `inc/admin-page.php`, `src/js/admin/`, the `admin/index` webpack entry, and all their `require_once` lines. Keep settings page, hooks, and GitHub updater. Use `add_options_page()` instead of `add_menu_page()` to nest settings under the Settings menu.

## Key Files to Modify

| Task | Files to Edit |
|------|---------------|
| Change/add CPT fields | `inc/post-types.php`, `src/blocks/starter-item-meta/edit.js` |
| Change settings/toggles | `inc/settings-page.php`, `src/js/settings/components/GeneralTab.js` |
| Add REST endpoint | Create `inc/rest-api.php`, hook on `rest_api_init` |
| Add a new block | Create `src/blocks/block-name/` with `block.json` + `index.js` |
| Change DataViews columns | `src/js/admin/config/itemConfig.js` |
| Change shortcode output | `inc/shortcodes.php` |
| Change admin columns | `inc/admin-columns.php` |
| Update GitHub updater | `inc/github-updater.php` — change constants at top of class |
| Update BunnyCDN icons | `inc/github-updater.php` — `ICON_SMALL` and `ICON_LARGE` constants |

**About tab logo:** Uses the same BunnyCDN icon URL from the GitHub updater class. No bundled image files.

## Common Tasks

### Adding a new meta field
```php
// inc/post-types.php — add to $meta_fields array in register_meta_fields()
'_weave_starter_new_field' => [
    'type' => 'string', 'single' => true, 'default' => '',
    'description' => __( 'New field.', 'weave-starter-plugin' ),
    'sanitize_callback' => 'sanitize_text_field',
],
```
```javascript
// src/blocks/starter-item-meta/edit.js — add to the Edit component
<TextControl
    label={ __( 'New Field', 'weave-starter-plugin' ) }
    value={ meta?._weave_starter_new_field || '' }
    onChange={ ( value ) => updateMeta( '_weave_starter_new_field', value ) }
/>
```
```javascript
// src/js/admin/config/itemConfig.js — add to fields array for DataViews column
{ id: 'new_field', label: __( 'New Field', 'weave-starter-plugin' ),
  type: 'text', enableSorting: true,
  getValue: ( { item } ) => item.meta?._weave_starter_new_field || '' }
```

### Menu placement:
CPT plugins → settings as submenu under the CPT menu. 
Utility plugins (no CPT) → use `add_options_page()` to nest under Settings.

## Build & Release

```bash
npm run build          # Build everything (scripts + blocks)
npm run build:scripts  # Settings page + admin page (webpack.scripts.config.js)
npm run build:blocks   # Gutenberg blocks (--blocks-manifest)
npm run start          # Watch mode for development
npm run lint           # Lint JS and CSS
```

### Creating a Release
1. Bump version in `weave-starter-plugin.php` header and `package.json`
2. Update `CHANGELOG.md`
3. Commit: `git commit -am "Release v1.x.x"`
4. Tag: `git tag -a v1.x.x -m "Version 1.x.x"`
5. Push: `git push origin main --tags`
6. GitHub Actions creates the release automatically

## Coding Conventions

- **PHP prefix**: `weave_starter_` for functions, `WeaveStarterPlugin\` for namespaces
- **Language**: NZ English everywhere (organised, colour, licence, centre, optimised)
- **Standards**: WordPress Coding Standards (tabs, spaces inside parentheses, short arrays `[]`)
- **Escaping**: Always. `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`
- **Nonces**: On every admin form and AJAX handler
- **Text domain**: `weave-starter-plugin` (matches plugin slug)
- **Licence**: GPL-2.0-or-later
- **PHP minimum**: 8.1 — `declare(strict_types=1)` in all files
- **WordPress minimum**: 6.6
- **Dependencies**: No ACF, no WooCommerce, no Composer, no jQuery
- **Shortcode docs**: Always document shortcodes and their attributes in README.md with usage examples. Do not reference specific page builders — shortcodes work everywhere.
- **README**: Every plugin README must list all shortcodes, their attributes, and default values. Keep it concise — one line per attribute is enough.

## Admin UI Rules

ALL admin interfaces use `@wordpress/components`. No custom CSS. No hand-rolled HTML.

Import components like `ToggleControl`, `TabPanel`, `Card`, `Button`, `Notice` from
`@wordpress/components`. The build process auto-detects these imports and adds
`wp-components` as a script dependency. WordPress enqueues the component stylesheet
automatically. Browse available components: https://wordpress.github.io/gutenberg/

**Never do this:**
- Write custom admin CSS files (components handle all styling)
- Use `<table class="form-table">` or classic admin HTML patterns
- Use Bootstrap, Tailwind, or jQuery UI for admin screens
- Add inline styles to components unless absolutely necessary

## Development & Hosting

**Local dev:** WordPress Studio (Automattic desktop app) or blueprint.json in Playground. Edit in Nova or Cursor AI. Source in `~/Projects/`, symlink into `~/Studio/{site}/wp-content/plugins/`.

**GitHub orgs:** `HumanKind-nz` (funeral, `hk-` prefix) / `weavedigitalstudio` (general, `weave-` prefix)

**Production:** NGINX via GridPane on Binary Lane VPS (NZ). Redis object caching. No Apache. PHP 8.1–8.4. BunnyCDN for static assets.

**Updates:** GitHub updater hooks into WordPress native update system — appears in Dashboard → Updates.