# Block Development — TO Specials

All blocks use the `lsx-tour-operator/` namespace and are registered as **block variations** via `wp.blocks.registerBlockVariation()`.

## Block Reference

### Featured

| Block Name | Variation | Registration | Description |
|---|---|---|---|
| `lsx-tour-operator/featured-special` | `core/group` | Global | Query loop for featured specials |

### Related — Same Type

| Block Name | Post Types | Templates | className |
|---|---|---|---|
| `lsx-tour-operator/special-related-special` | `special` | `special` | `lsx-special-related-special-query-wrapper` |

### Related — Cross Type

| Block Name | Post Types | Templates | className |
|---|---|---|---|
| `lsx-tour-operator/special-related-destination` | `destination` | `destination`, `country`, `region` | `lsx-special-related-destination-query-wrapper` |
| `lsx-tour-operator/special-related-accommodation` | `accommodation` | `accommodation` | `lsx-special-related-accommodation-query-wrapper` |
| `lsx-tour-operator/special-related-tour` | `tour` | `tour` | `lsx-special-related-tour-query-wrapper` |

### Post Meta

All scoped to `special` post type and template.

| Block Name | Binding Key | Element |
|---|---|---|
| `lsx-tour-operator/special-tagline` | `tagline` | `core/paragraph` with `lsx/post-meta` |
| `lsx-tour-operator/special-price` | `price` | `core/group` with icon + `core/paragraph` |
| `lsx-tour-operator/special-price-type` | `price_type` | `core/paragraph` with `lsx/post-meta` |
| `lsx-tour-operator/special-duration` | `duration` | `core/group` with icon + `core/paragraph` |
| `lsx-tour-operator/special-booking-validity` | `booking_validity_start`, `booking_validity_end` | `core/group` with two `core/paragraph` |

### Post Connection

All scoped to `special` post type and template.

| Block Name | Connection Key | Icon |
|---|---|---|
| `lsx-tour-operator/accommodation-to-special` | `accommodation_to_special` | `accommodationIcon` |
| `lsx-tour-operator/destination-to-special` | `destination_to_special` | `destinationIcon` |
| `lsx-tour-operator/tour-to-special` | `tour_to_special` | `tourIcon` |
| `lsx-tour-operator/team-to-special` | `team_to_special` | `teamIcon` |

### Gallery

| Block Name | Source | Registration |
|---|---|---|
| `lsx-tour-operator/special-gallery` | `lsx/gallery` | `special` post type only |

## Conditional Registration

Blocks use `registerForPostTypesAndTemplates(postTypes, templates, registerFn)` from `@utils/conditional-block-registration.js` to limit insertion to relevant post type edit screens. Featured blocks are registered globally.

## Binding Sources

| Source | Use |
|---|---|
| `lsx/post-meta` | Binds `core/paragraph` content to a CMB2 meta field via `args.key` |
| `lsx/post-connection` | Binds `core/paragraph` content to a connected post via `args.key` |
| `lsx/gallery` | Binds `core/gallery` images to the post gallery meta |

## Adding a New Block

1. Create `src/blocks/{block-name}/block.json` with `"editorScript": "file:index.js"` and `"textdomain": "to-specials"`
2. Create `src/blocks/{block-name}/index.js` with the variation registration
3. Run `npm run build`
