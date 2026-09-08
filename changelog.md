# Change log

## [Unreleased]

### Security
- Cleared all 13 Dependabot alerts. Every one was a transitive build-toolchain package reached through `@wordpress/scripts`, `stylelint` or `cssnano`, with no direct dependency to bump, so they are pinned with `overrides` in `package.json` and the constraints survive lockfile regeneration: `fast-uri ^3.1.6` (4 high, SSRF and host confusion), `serialize-javascript ^7.0.5` (high RCE, moderate CPU-exhaustion DoS), `browserslist ^4.28.7` (high), `@humanfs/node ^0.16.8` (moderate), and `sockjs > uuid ^11.1.1`. `postcss-selector-parser` (2 low) is pinned `^6.1.3` at the root for the cssnano v6 plugin set, with nested `^7.1.3` overrides for stylelint and the postcss-modules packages, so neither major line is forced across a boundary. `markdownlint-cli ^0.49.1` clears the `markdown-it` smartquotes ReDoS and the `minimatch` backtracking alerts at source rather than force-patching leaves into a 2022-era parent.

### Changed
- All version sources reconciled on `2.2.0`. The plugin header and the readme `Stable tag` read `2.2`, `LSX_TO_SPECIALS_VER` read `2.2.0`, and `package.json` was further behind still at `2.1.0`. Only `2.2.0` has ever existed as a git tag, and `10up/action-wordpress-plugin-deploy` derives the WordPress.org SVN tag from the git tag, so `Stable tag: 2.2` pointed at a tag that was never created. All four sources now read `2.2.0`, and `npm run lint:version` (`scripts/check-version-sync.mjs`) fails CI if they drift apart again.
- Raised the Node support contract to `>=24.0.0` with `npm >=11.0.0`, pinned in a new `.nvmrc` on 24.20.0, the current LTS line. The previous `>=18.0.0` was unsatisfiable, since the lockfile resolves packages requiring Node 20 and 22.
- `.coderabbit.yaml` now parses. Removed the top-level `commands` and `linked_issues` keys, which are not in the v2 schema, and the duplicated `reviews.path_filters` mapping key.

### Added
- CI workflow that builds the block assets and lints styles. The only checks were PHP syntax and CodeQL, so a broken webpack config or stylesheet could merge unnoticed. It uses GitHub's native `concurrency` with `cancel-in-progress`, keyed on `github.ref` so that fork pull requests sharing a branch name cannot cancel each other's checks.

### Removed
- `.github/workflows/check-php-syntax-errors.yml`. It pinned `overtrue/phplint@9.8`, whose published action image is missing `symfony/stopwatch` and dies before linting anything. The new CI workflow lints with plain `php -l` across 8.2, 8.3 and 8.4 instead, covering more PHP versions than phplint did.
- `.github/workflows/cancel.yml`. GitHub cancels superseded runs natively, and the workflow queried the API for every workflow id on each run.
- `.stylelintrc.json`. It extended `@humanmade/stylelint-config`, which is not installed, so `npm run lint:css` aborted with a `ConfigurationError` before linting anything, and it shadowed the `stylelint.config.js` that extends `@wordpress/stylelint-config`.

## [2.2](https://github.com/lightspeeddevelopment/to-specials/releases/tag/2.2) - 2026-07-29

### Added
- New `special-card` block pattern (`patterns/special-card.php`) — card layout for use in query loops displaying featured photo, title, price, duration, booking validity, and excerpt.
- `register_block_patterns()` method in `LSX_TO_Specials_Blocks` — auto-loads all `.php` files from the `/patterns/` directory and registers them as block patterns under the `lsx-tour-operator` namespace, skipping any already-registered keys.
- `register_multi_field_wrappers()` filter handler in `LSX_TO_Specials_Blocks` — registers two new multi-field wrapper groups for the `lsx_to_multi_field_wrappers` filter: `booking-validity` (groups `booking_validity_start` + `booking_validity_end`) and `pricing-booking-column` (groups `booking_validity_start`, `booking_validity_end`, and `price_type`) so these fields collapse when all values are empty.
- `price_type_filter()` in `LSX_TO_Specials_Frontend` — new `lsx_to_custom_field_query` filter that formats the `price_type` meta value for display: `per_person*` variants abbreviated to `P/P …`; `total_percentage` rendered as `% Off`; `none` hidden entirely.
- `single-special.html` and `archive-special.html` block templates rebuilt with full block markup, using the new `special-card` pattern for archive listings and a structured pricing/booking section on the single view.
- Hero section (cover block using the featured image, with a centered post title and a `lsx/post-meta` tagline binding) added above the sticky menu on `single-special.html`.
- Breadcrumbs `template-part` on `archive-special.html` and `single-special.html` replaced with a styled `wp:group` (primary background, medium font size, hover link colour) wrapping the `yoast-seo/breadcrumbs` block for consistent layout across templates.

### Updated
- Booking validity fields (`booking_validity_start`, `booking_validity_end`) field type changed from `date` to `text_date_timestamp` in both `config-special.php` and `post-types/special.json` for consistent Unix timestamp storage.
- Grid `columnCount` for `accommodation-related-special`, `destination-related-special`, and `tour-related-special` block variations increased from `2` to `3`.
- Related-post card blocks in the single-special template replaced with dedicated block patterns for improved maintainability.
- Sticky menu block on `single-special.html` restyled with active/hover background and text colours, a contrast background, and medium font size for improved accessibility and visual consistency.
- Spacing (`blockGap` and top/bottom padding using spacing presets) added to the Accommodation, Tours, Destinations, Reviews, and Team Members related-query sections on `single-special.html`.
- `README.txt` "Tested up to" bumped from 6.9 to 7.0; minor whitespace/formatting cleanup (trailing space on `Tags:`, missing FAQ heading delimiter).

### Fixed
- Duplicate `post-title` block removed from the Overview section of `single-special.html` (title now rendered once, in the new hero section).

### Removed
- `travel_dates` repeater field commented out in `config-special.php` (pending decision on storage format).
- Unused `load_plugin_textdomain()` action/method removed from the `LSX_TO_Specials` and `LSX_TO_Specials_Admin` constructors (WordPress core has auto-loaded plugin translations since 4.6).

### Security
- Added `if ( ! defined( 'ABSPATH' ) ) { exit; }` direct-access guards to `class-specials-schema.php`, `class-to-specials-admin.php`, `class-to-specials-frontend.php`, `class-to-specials-templates.php`, `class-to-specials.php`, `includes/metaboxes/config-special.php`, `includes/post-types/config-special.php`, `includes/taxonomies/config-special-type.php`, `includes/template-tags.php`, and `patterns/special-card.php`.
- Added `phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound` annotations to `the_content` filter calls in `class-to-specials-frontend.php` and `class-to-specials-schema.php` to address Plugin Check/WPCS findings.

### Schema Fixed
- Schema: `@type` simplified from `array( 'Offer' )` to a plain `'Offer'` string.
- Schema: `@id` updated from `#special` to `#/schema/offer/{id}` to avoid collisions on pages with multiple specials.
- Schema: `name` now uses `get_the_title( $post->ID )` instead of `$post->post_title` directly.
- Schema: `description` now uses `\lsx\schema\Helpers::strip_to_text()` on `apply_filters( 'the_content', … )` instead of a bare `wp_strip_all_tags()` on raw post content.
- Schema: meta queries changed from `get_the_ID()` to `$this->context->id` for consistency.
- Schema: duplicate `itemOffered` keys (tours and accommodation were silently overwriting each other) replaced with a new `add_items_offered()` method that merges both into a single value; tours typed as `TouristTrip`, accommodation as `LodgingBusiness`.
- Schema: duplicate `priceValidUntil` assignment removed.
- Schema: availability and price-validity dates now formatted as ISO 8601 via a new `add_availability()` helper that calls `\lsx\schema\Helpers::format_iso_date()`; fields are omitted when the date is empty.
- Schema: `get_price()` refactored — currency now resolved via `\lsx\schema\Helpers::get_currency()` instead of inline `tour_operator()` option lookup; price value normalised via `\lsx\schema\Helpers::normalise_price()`.
- Schema: `PriceSpecification` key corrected to lowercase `priceSpecification`; now outputs a properly typed `PriceSpecification` object with `@type`, `price`, `priceCurrency`, and `unitText` instead of a bare label string.

### Schema Added
- Schema: new `add_availability()` helper that reads a raw CMB2 date meta value, formats it as ISO 8601, and conditionally sets the given schema property.
- Schema: new `add_items_offered()` method that merges related tour and accommodation post IDs into a single `itemOffered` value without overwriting.

## [[2.1]](https://github.com/lightspeeddevelopment/to-specials/releases/tag/2.1) - 2025-01-13

### Description
This release introduces significant improvements to the template system, enhanced custom field configurations, and better Tour Operator 2.0 compatibility for the Special Offers plugin.

### Added
- New `LSX_TO_Specials_Templates` class for proper block template registration
- Block editor templates: `templates/archive-special.html` and `templates/single-special.html`
- Post field support for enhanced content management

### Updated
- Special metabox configurations for better CMB2 integration
- Field titles and descriptions for improved user guidance
- Post type labels for better clarity
- Plugin assets converted to PNG format (banners and icons)
- Language files (.po, .pot) with new strings and improved formatting
- Plugin version to 2.1 across all files

### Fixed
- Post type slug for single special offer template (changed from 'tour' to 'special')
- Travel dates field naming for consistency
- Template registration logic for block editor compatibility

### Removed
- Legacy `class-template-redirects.php` (189 lines removed)
- Unused `gulpfile.js` (51 lines removed)
- Obsolete template selection logic

### Changed
- Improved class structure and organization
- Simplified template loading system
- Enhanced field structure in metabox configurations

### Security
- Tested with WordPress 6.9
- Tested with PHP 8.0+
- Code quality improvements for better security

## [[2.0.0]](https://github.com/lightspeeddevelopment/to-reviews/releases/tag/2.0.0) - 2025-05-09

### Description
The following PR contains the code for the block updates and the removal of the legacy code.

### Added
- WordPress block editor support
- Tour Operator 2.0 Support.

### Updated
- Custom fields to CMB2 and its add-ons.
- WPCS warnings notices fixed.

### Removed
- Old PHP Templates, function and legacy template code.

### Security
- Tested with WordPress 6.8.1

## [[1.3.6]](https://github.com/lightspeeddevelopment/to-specials/releases/tag/1.3.6) - 2023-08-09

### Fixes
- Fixing the spacing above the "more" p tag.

### Security
- General testing to ensure compatibility with latest WordPress version (6.3).

## [[1.3.5]](https://github.com/lightspeeddevelopment/to-specials/releases/tag/1.3.5) - 2023-04-20

### Security
- General testing to ensure compatibility with latest WordPress version (6.2).

## [[1.3.4]](https://github.com/lightspeeddevelopment/to-specials/releases/tag/1.3.4) - 2022-12-23

### Security
- General testing to ensure compatibility with latest WordPress version (6.1.1).

## [[1.3.3]](https://github.com/lightspeeddevelopment/to-specials/releases/tag/1.3.3) - 2022-09-12

### Security
- General testing to ensure compatibility with latest WordPress version (6.0).

## [[1.3.2]](https://github.com/lightspeeddevelopment/to-specials/releases/tag/1.3.2) - 2020-01-15

### Added
- Allowing the block editor for the single specials description area.

### Updated
- Documentation and support links

### Security
- General testing to ensure compatibility with latest WordPress version (5.6).


## [[1.3.1]](https://github.com/lightspeeddevelopment/to-specials/releases/tag/1.3.1) - 2019-12-19

### Added
- Enabled the sorting of the gallery field.
- General testing to ensure compatibility with latest WordPress version (5.3).
- Checking compatibility with LSX 2.6 release.


## [[1.3.0]](https://github.com/lightspeeddevelopment/to-specials/releases/tag/1.3.0) - 2019-09-27

### Added
- Added in the option to disable the view more button on the widget template.
- Adding the .gitattributes file to remove unnecessary files from the WordPress version.
- Added in the Offer Schema to the plugin, which integrates with Yoast WordPress SEO.
- Enabled the sorting of the gallery field.

### Fixed
- Fix the double button on widget template.


## [[1.2.1]](https://github.com/lightspeeddevelopment/to-specials/releases/tag/1.2.1) - 2019-08-06

### Security
- Updating dependencies.


## [[1.1.0]]()

### Added
- Support LSX Theme 2.0 new designs.
- Added compatibility with LSX 2.0.
- Added compatibility with Tour Operator 1.
- New project structure.
- Updated the the way the post type registers to match the refactored TO plugin.
- Updated the registering of the metaboxes.

### Fixed
- Fixed scripts/styles loading order.
- Fixed small issues.
- Replaced 'global $tour_operators' by 'global $tour_operator'.


## [[1.0.4]]()

### Added
- Standardized the Gallery and Video fields.


## [[1.0.3]]()

### Added
- Fixed menu navigation improved.
- Metadata: calendar info moved to the next line.
- Metadata: term "price" change to "price from".
- Metadata: term “duration” added to duration meta.

### Fixed
- Make the addon compatible with the latest version from TO Search addon.
- API key and email grabbed from the correct settings tab.
- Added TO Search as subtab on LSX TO settings page.
- Code refactored to follow the latest Tour Operator plugin workflow.
- Small fixes on front-end fields.
- Fixed content_part filter for plugin and add-ons.


## [[1.0.2]]()

### Fixed
* Fix - Fixed all prefixes replaces (to_ > lsx_to_, TO_ > LSX_TO_).


## [[1.0.1]]()

### Fixed
- Reduced the access to server (check API key status) using transients.
- Made the API URLs dev/live dynamic using a prefix "dev-" in the API KEY.


## [[1.0.0]]()

### Added
- First Version
