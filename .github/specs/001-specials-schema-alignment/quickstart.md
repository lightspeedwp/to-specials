# QA: Specials Schema 2.0 Alignment

How to verify TO-217 and the evidence captured during implementation.

## Automated checks

```bash
composer install
composer test
# or, with a tour-operator checkout elsewhere:
TO_CORE_PATH=/path/to/tour-operator vendor/bin/phpunit -c phpunit.xml.dist
```

Expected result: `OK (12 tests)`. Five of the tests fail against the pre-fix piece (`318fd4d`).

## Manual QA

1. Use a site with Tour Operator core 2.2+, Yoast SEO and this branch of TO Specials active, where single Special pages are **not** redirected (see the note below).
2. Populate a Special with: title, content, a price such as "R 12,500", a price type, booking validity start and end, one linked tour (linked twice) and one linked accommodation.
3. View the Special page source and copy the `yoast-schema-graph` JSON-LD.
4. Run the page URL through the [Schema.org Validator](https://validator.schema.org/) and the [Google Rich Results Test](https://search.google.com/test/rich-results).
5. Check that:
   - Exactly one `Offer` node is present, with string `@type` and `@id` ending `#/schema/offer/{ID}`.
   - The dates are `YYYY-MM-DD`, and `priceValidUntil` appears once.
   - `price` is numeric, and `priceSpecification` (lower-case `p`) is a `PriceSpecification` object.
   - `itemOffered` lists one `TouristTrip` and one `LodgingBusiness`, each once and each with a `url`.
   - The validators report 0 errors.
6. **Percentage**: set the price type to "Percentage Off Your Price". `price`, `priceCurrency`, `priceSpecification`, `category` and `availability` all disappear.
7. **Without Yoast**: deactivate Yoast SEO and confirm that a standalone `<script type="application/ld+json">` Offer graph is printed in `<head>`.
8. **Core 2.1**: activate with Tour Operator core 2.1. The page renders with no PHP errors and no Offer node.

## Evidence (local Studio site, 2026-10-09)

Environment: `beta.local`, Tour Operator 2.2.0 (`fix/itinerary-schema-fix-1143`, which has the legacy schema classes removed), Yoast SEO 27.6, PHP 8.4.

**Local constraint**: `sd-enhancements-2026` 301-redirects every single Special to `/specials/`, so the Offer node cannot be viewed in rendered HTML on this site. Evidence was captured by running the live piece through WP-CLI (`wp eval-file`) with a Yoast-shaped context against a QA Special created for this purpose: **ID 616, "Schema QA – Winter Safari Special"**. Its tour is linked twice on purpose.

**Before** (`update-2.2` baseline): with this core build the legacy base class is gone, so no Offer node is registered.

**After** (this branch):

```json
{
    "@type": "Offer",
    "@id": "http://beta.local/special/schema-qa-winter-safari-special/#/schema/offer/616",
    "name": "Schema QA – Winter Safari Special",
    "description": "Save on a winter safari in Thailand & stay an extra night free.",
    "url": "http://beta.local/special/schema-qa-winter-safari-special/",
    "mainEntityOfPage": { "@id": "http://beta.local/special/schema-qa-winter-safari-special/" },
    "offeredBy": { "@id": "http://beta.local/#organization" },
    "availabilityStarts": "2026-11-01",
    "availabilityEnds": "2027-02-28",
    "priceValidUntil": "2027-02-28",
    "price": "12500",
    "priceCurrency": "ZAR",
    "category": "Special",
    "availability": "https://schema.org/LimitedAvailability",
    "priceSpecification": { "@type": "PriceSpecification", "price": "12500", "priceCurrency": "ZAR", "unitText": "Per Person Sharing" },
    "itemOffered": [
        { "@type": "TouristTrip", "name": "Phuket – Krabi – Thailand – 14 Nights", "url": "http://beta.local/tour/phuket-krabi-thailand-14-nights/" },
        { "@type": "LodgingBusiness", "name": "Koh Yao Yai Village", "url": "http://beta.local/accommodation/koh-yao-yai-village/" }
    ]
}
```

Before the de-duplication fix, the same capture listed the tour twice, with `&#8211;` entities in its name.

**Still to do before sign-off**: steps 3–8 on `tour-operator.lightspeedwp.dev`, or on any site where single Specials are not redirected. Delete QA Special 616 from the local site when it is no longer needed.
