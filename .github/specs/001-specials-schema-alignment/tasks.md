# Tasks: Specials Schema 2.0 Alignment

**Input**: [spec.md](./spec.md), [plan.md](./plan.md)

## Phase 1 – Setup

- [x] T001 Add `phpunit.xml.dist`, `tests/php/bootstrap.php` and a `composer test` script

## Phase 2 – Foundational

- [x] T002 Delete the retired `classes/class-specials-schema.php` (FR-012)
- [x] T003 Rewrite `classes/class-to-specials-schema.php` as a self-contained piece with baseline-equivalent output (FR-001–FR-006, FR-009, FR-010)
- [x] T004 Register only when `lsx\schema\Helpers` exists, and print standalone JSON-LD when Yoast is inactive (FR-011)

## Phase 3 – User Story 1: valid Offer (P1)

- [x] T005 [US1] Percentage price type emits no price fields; price-type label fallback (FR-005, FR-006)
- [x] T006 [US1] Plain-text name (decoded entities)

## Phase 4 – User Story 2: identifiable offered items (P2)

- [x] T007 [US2] `itemOffered` with URL, published only, de-duplicated (FR-007, FR-008)

## Phase 5 – User Story 4: tests and QA (P3)

- [x] T008 [US4] `tests/php/SpecialSchemaTest.php` (FR-013)
- [x] T009 [US4] WP-CLI capture against QA Special 616 and QA steps in `quickstart.md` (FR-014) — WP-CLI capture done; rendered-page validator, no-Yoast and core 2.1 runs pending (see quickstart.md)

## Phase 6 – Polish

- [x] T010 PHPCS on changed files — no violations on changed lines (the repo phpcs.xml.dist excludes `wp-content/*`, so run it outside a WordPress install)
- [x] T011 `changelog.md` Unreleased entry
