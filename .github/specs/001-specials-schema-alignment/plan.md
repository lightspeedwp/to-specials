# Implementation Plan: Specials Schema 2.0 Alignment

**Branch**: `fix/to-217-specials-schema-alignment` | **Date**: 2026-10-09 | **Spec**: [spec.md](./spec.md)

## Summary

Rebuild the `Offer` schema piece as a self-contained class on the Tour Operator core 2.2 schema helpers, as was done for TO Team (TO-171) and TO Reviews (TO-216). Delete the retired legacy schema file, and close the remaining gaps: linked and de-duplicated `itemOffered`, percentage-discount pricing, and price-type labels. Add PHPUnit regression tests and QA evidence.

## Technical Context

**Language/Version**: PHP 7.2+; tests run on PHP 8.x with PHPUnit 10.5 (already in `require-dev`)
**Primary Dependencies**: Tour Operator core 2.2 (`lsx\schema\Helpers`); Yoast SEO graph API (optional)
**Testing**: PHPUnit with WordPress stubs (same harness as TO Team and TO Reviews); local QA via WP-CLI

## Research and Key Decisions

### R1 – Legacy base removed (same as TO-171 and TO-216)

Core `f62d3b5dd` deletes `LSX_TO_Schema_Graph_Piece` and `lsx\legacy\Schema_Utils`. `LSX_TO_Specials_Schema` becomes a plain class with an optional Yoast context, `is_needed()` and `generate()`, depending only on `lsx\schema\Helpers`. It is registered only when that class exists, and prints standalone JSON-LD when Yoast is inactive.

### R2 – Retired legacy file

`classes/class-specials-schema.php` declares a second `LSX_TO_Specials_Schema` class. It has a hard-coded telephone number, an undefined `$i` and a `Trip`/`ProfessionalService`/`Offer` multi-type node. Nothing requires it, and `git grep` finds no references. Delete it (FR-012).

### R3 – Pricing

| Case | Output |
| --- | --- |
| Price parses to a positive number, and type is not a percentage | `price` (normalised), `priceCurrency`, `category` "Special", `availability` `LimitedAvailability` |
| …and price type set (not "none") | plus `priceSpecification { @type PriceSpecification, price, priceCurrency, unitText: label }` |
| Price type `total_percentage` | No price fields; a percentage is not an amount, and `Offer` has no valid percentage-discount property |
| Price empty or unparseable | No price fields |

Price-type labels come from core's `lsx_to_get_price_type_label()`. When it returns an empty string, as it does for `per_person`, the piece falls back to its own labels, which mirror the field options.

### R4 – Offered items

`itemOffered` merges the tours (`TouristTrip`) and accommodation (`LodgingBusiness`) linked through `tour_to_special` and `accommodation_to_special`, read with `Helpers::get_meta_array()`. Each node is `{ @type, name, url }`, published only and de-duplicated. A single item is output as an object rather than a list.

### R5 – Related articles

The baseline output linked blog posts (`post_to_special`) as `subjectOf` via the legacy article builder. `subjectOf` is a valid `Thing` property, so it is kept. The output is reduced to published posts as `{ @type: Article, headline, url }`.

### R6 – Local QA constraint

On the local Studio site, `sd-enhancements-2026` 301-redirects every single Special to the archive, so the Offer node cannot be seen in rendered HTML there. Local evidence is captured by running the real piece through WP-CLI against a populated QA Special (ID 616, "Schema QA – Winter Safari Special"). Rendered-page validation happens on the QA site.

## Files Touched

```text
classes/class-to-specials-schema.php      # rewritten piece
classes/class-specials-schema.php         # deleted (retired legacy)
classes/class-to-specials.php             # guard + standalone output
tests/php/bootstrap.php, tests/php/SpecialSchemaTest.php, phpunit.xml.dist
composer.json                             # test script
changelog.md
.github/specs/001-specials-schema-alignment/quickstart.md
```
