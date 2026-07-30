# TO Specials

The Tour Operator Special Offers extension adds the `special` post type with full Gutenberg block support for displaying time-sensitive deals on your site.

## Requirements

- [Tour Operator Plugin](https://touroperator.solutions/) (parent plugin)
- WordPress 6.7+
- PHP 8.0+

## Installation

1. Ensure the Tour Operator Plugin is installed and activated.
2. Upload or install the `to-specials` plugin.
3. Activate via **Plugins → Installed Plugins**.

## Post Type

**Slug:** `special`

Post meta fields:

| Field | Key | Type |
|---|---|---|
| Tagline | `tagline` | text |
| Price | `price` | text |
| Price Type | `price_type` | text |
| Duration | `duration` | text |
| Booking Validity Start | `booking_validity_start` | text |
| Booking Validity End | `booking_validity_end` | text |

## Blocks

See [block-development.md](block-development.md) for a full block reference.

## Building

```bash
npm install
npm run build
```

## Support

[LightSpeed support form](https://lightspeedwp.agency/lsx/support/)
