# Change log

## [2.2 — Schema] - 2026-07-29

### Fixed
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

### Added
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
