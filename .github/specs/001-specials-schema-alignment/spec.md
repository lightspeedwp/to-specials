# Feature Specification: Specials Schema 2.0 Alignment

**Feature Branch**: `fix/to-217-specials-schema-alignment` (based on `update-2.2`)

**Created**: 2026-10-09

**Status**: Draft

**Tracking**: [TO-217](https://linear.app/lightspeedwp/issue/TO-217/align-specials-schema-with-approved-schema-20-mappings) · [lightspeedwp/to-specials#199](https://github.com/lightspeedwp/to-specials/issues/199)

**Approved mapping**: [Tour Operator – Structured Data › Specials (audit)](https://docs.google.com/document/d/1UVfrdgR1o2A-5YuyMbY6Bxevy3EXYfOkj-p6dwXFxMA/edit?tab=t.z62l5z96uy46#heading=h.k91s13xhe806)

**Input**: "Align the Specials extension schema output with the approved Tour Operator Schema 2.0 mappings and fix the current Offer schema defects, so Special posts emit a valid `Offer` node without duplicate-key overwrites or an invalid price specification."

## Context: Baseline on `update-2.2`

The earlier `feature/1143-schema-2.2-specials-alignment` work has already been merged into `update-2.2`. The audit below compares each TO-217 task with the current baseline, so this spec covers only the remaining work and the evidence needed to close the ticket.

| TO-217 task | Baseline status |
| --- | --- |
| `@type` is the string `Offer` | ✅ Done |
| `@id` is unique (`#/schema/offer/{post_id}`) | ✅ Done |
| `name` from filtered title | ✅ Done |
| `description` from filtered, HTML-stripped content | ✅ Done |
| `url` + `mainEntityOfPage` kept | ✅ Done |
| `offeredBy` from the site organisation | ✅ Done |
| `availabilityStarts` / `availabilityEnds` as ISO 8601 dates | ✅ Done |
| Single `priceValidUntil`, ISO 8601 | ✅ Done |
| Normalised numeric `price` + `priceCurrency` fallback | ✅ Done |
| Tours and accommodation merged into one `itemOffered` (`TouristTrip` / `LodgingBusiness`) | ✅ Done. Gap: items carry only a name, with no link and no de-duplication (see FR-007, FR-008) |
| `priceSpecification` correctly cased and structured | ✅ Done |
| Availability/category kept only where valid | ✅ Done (only emitted alongside a valid price) |
| Image via the shared schema utility | ✅ Done |
| Tests / manual validation for one populated Special | ❌ Not done |

Other gaps found during the audit:

- A **retired legacy file** (`classes/class-specials-schema.php`) still ships in the plugin. It declares the same class name as the live schema piece, hard-codes a telephone number, references an undefined variable and emits a conflicting multi-type node. It is not loaded today, but if anything requires it, the site will fail with a fatal error or emit incorrect data.
- **Core version dependency**: the live schema piece relies on shared schema helpers that ship only with Tour Operator core 2.2+. The loader checks only for the older base class, which core 2.1 also provides. A site running this extension with core 2.1 would hit a fatal error on every Special page.

## User Scenarios & Testing *(mandatory)*

### User Story 1 – Search engines read a valid Offer for each Special (Priority: P1)

A travel operator publishes a Special Offer with a price, booking window and linked tours/accommodation. When a search engine or AI assistant crawls the page, it finds one well-formed `Offer` node. The node describes the deal accurately, links to every product it applies to, and passes structured data validation with no errors.

**Why this priority**: This is the outcome TO-217 exists for. Invalid or contradictory Offer markup can make a Special page ineligible for rich results and misinform AI trip planners.

**Independent Test**: Load one fully populated Special on the QA site and run its URL through the Schema.org Validator and Google Rich Results Test. The Offer node must validate with zero errors and match every row of the approved mapping.

**Acceptance Scenarios**:

1. **Given** a Special with title, content, price, price type, booking validity start/end and one linked tour plus one linked accommodation, **When** the page is validated, **Then** exactly one `Offer` node is reported with string `@type`, a unique `@id`, `name`, `description`, `url`, `offeredBy`, `availabilityStarts`, `availabilityEnds`, `priceValidUntil`, numeric `price`, `priceCurrency`, a structured `priceSpecification`, and an `itemOffered` list containing one `TouristTrip` and one `LodgingBusiness`.
2. **Given** the same Special, **When** the JSON-LD is inspected, **Then** every date is a valid ISO 8601 date and `priceValidUntil` appears exactly once.
3. **Given** a Special whose content contains HTML, shortcodes or blocks, **When** the page is validated, **Then** `description` is plain text with no markup.

---

### User Story 2 – Offered products are identifiable and not duplicated (Priority: P2)

An AI trip planner reading a Special needs to know exactly which tours and lodges the deal covers, and to follow those links to the full product pages.

**Why this priority**: `itemOffered` entries that carry only a name are weak signals. Duplicate entries, for example when the same tour is linked twice, add noise.

**Independent Test**: Link the same tour to a Special twice and add one accommodation. Validate the page and confirm `itemOffered` contains exactly two entries, each with a resolvable URL.

**Acceptance Scenarios**:

1. **Given** a Special linked to tour A twice and accommodation B once, **When** the page is validated, **Then** `itemOffered` contains exactly two entries: A (`TouristTrip`) and B (`LodgingBusiness`).
2. **Given** a linked tour or accommodation, **When** its `itemOffered` entry is inspected, **Then** it includes the product's public URL.
3. **Given** a linked product that is unpublished, trashed or deleted, **When** the page is validated, **Then** that product is not listed in `itemOffered`.

---

### User Story 3 – Sites never break because of Specials schema (Priority: P2)

A site owner updates the Specials extension before updating Tour Operator core, or a developer accidentally loads a retired file. Their Special pages must keep rendering.

**Why this priority**: A fatal error on the front end is far more damaging than missing structured data.

**Independent Test**: Activate the extension alongside Tour Operator core 2.1 and Yoast SEO. Load a Special page and confirm it renders with no PHP errors, and that the Specials Offer node is simply absent.

**Acceptance Scenarios**:

1. **Given** Tour Operator core older than 2.2, **When** a Special page loads, **Then** the page renders normally, no PHP fatal or warning is raised, and no Specials Offer node is emitted.
2. **Given** the plugin package, **When** its files are listed, **Then** the retired legacy Specials schema file is no longer present.

---

### User Story 4 – QA evidence exists for sign-off (Priority: P3)

A reviewer approving the PR needs repeatable evidence that the output matches the approved mapping.

**Why this priority**: TO-217's acceptance criteria require validation "against the agreed structured data QA approach". Without recorded evidence, the ticket cannot be closed.

**Independent Test**: Follow the documented QA steps on the QA site and compare the captured JSON-LD with the expected output.

**Acceptance Scenarios**:

1. **Given** the documented QA procedure, **When** a reviewer follows it, **Then** they can reproduce a validator run with zero errors for the reference Special.
2. **Given** the repository's automated checks, **When** they run, **Then** at least one automated check fails if `@type` becomes an array, `priceValidUntil` is duplicated, `priceSpecification` is mis-cased, or tour/accommodation items overwrite each other.

### Edge Cases

- **No price, or an unparseable price** such as "Price on request": omit `price`, `priceCurrency`, `priceSpecification`, `category` and `availability`, and still emit the rest of the Offer.
- **Price type is empty or "none"**: omit `priceSpecification` and keep `price`/`priceCurrency`.
- **Invalid or partial booking dates**: omit only the affected date property. Never emit a malformed date.
- **Booking validity end before start**: emit both as stored, and flag it for content QA rather than silently swapping them. Recorded as an assumption.
- **No linked tours or accommodation**: omit `itemOffered` entirely.
- **Exactly one linked product**: `itemOffered` is a single object, not a one-item list.
- **Currency not configured in Tour Operator settings**: use the documented fallback currency.
- **Yoast SEO inactive, or no site organisation configured**: omit `offeredBy`. No errors.
- **Special in draft or preview**: no Offer node is emitted for non-public posts, matching Yoast behaviour.

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: Each public Special page MUST emit exactly one `Offer` node with a string `@type` of `Offer` and an `@id` unique to that Special.
- **FR-002**: The Offer MUST include `name` and `description` taken from the filtered title and filtered, markup-free content, plus `url` and `mainEntityOfPage`.
- **FR-003**: The Offer MUST include `offeredBy` that references the site organisation whenever one is configured.
- **FR-004**: Booking validity start and end MUST map to `availabilityStarts` and `availabilityEnds`, and booking validity end MUST map to a single `priceValidUntil`. All three MUST be valid ISO 8601 dates, or be omitted.
- **FR-005**: The price MUST be emitted as a normalised numeric value with `priceCurrency` from Tour Operator settings, falling back to a documented default.
- **FR-006**: When a price type is set, the Offer MUST include a correctly cased, structured `priceSpecification` object carrying price, currency and the human-readable price-type label.
- **FR-007**: Linked tours and accommodation MUST be merged into one `itemOffered` value, typed `TouristTrip` and `LodgingBusiness` respectively. Neither list may overwrite the other, duplicate links MUST be removed, and only published products may be included.
- **FR-008**: Each `itemOffered` entry MUST include the product's public URL, in addition to its name and type.
- **FR-009**: `category` and `availability` MUST only be emitted when a valid price is present.
- **FR-010**: The image MUST continue to be emitted through the shared schema image utility.
- **FR-011**: The Specials schema piece MUST only register when the Tour Operator core schema helpers it depends on are available. Otherwise it MUST register nothing and raise no errors.
- **FR-012**: The retired legacy Specials schema file MUST be removed from the plugin.
- **FR-013**: The plugin MUST include at least one automated regression check that covers FR-001, FR-004 (single `priceValidUntil`), FR-006 and FR-007.
- **FR-014**: The PR MUST include documented manual QA evidence for one fully populated Special: validator results and the captured JSON-LD.
- **FR-015**: Output for tours, accommodation, destinations, reviews and team MUST NOT change as a result of this work.

### Key Entities

- **Special (post)**: A time-limited deal. Attributes: title, content, featured image, price, price type, booking validity start/end. Relationships: linked tours and linked accommodation.
- **Offer (schema node)**: The structured-data representation of a Special.
- **Offered item**: A tour (`TouristTrip`) or accommodation (`LodgingBusiness`) that the Special applies to, identified by name, type and URL.
- **Site organisation**: The Organization node supplied by Yoast SEO and referenced as `offeredBy`.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: The reference Special on the QA site returns **0 errors** in both the Schema.org Validator and the Google Rich Results Test, with no warnings for any property this spec covers.
- **SC-002**: **100%** of the rows in the approved Specials mapping are present and correctly typed in the captured JSON-LD for the reference Special.
- **SC-003**: A Special linked to N distinct published products lists exactly N `itemOffered` entries, for every N from 0 to 10 tested.
- **SC-004**: **0** PHP fatal errors, warnings or notices on Special pages across the tested combinations: core 2.1 and 2.2, with Yoast SEO active and inactive.
- **SC-005**: The automated regression check fails when any one of the FR-013 defects is reintroduced, and passes on the delivered code.

## Assumptions

- **Base branch**: Work branches from `update-2.2` and the PR targets `update-2.2`, as TO-217's delivery decision specifies. `update-2.2` is currently fully contained in `develop`, so no merge conflicts are expected.
- **Mapping authority**: The "Specials (audit)" tab of the Structured Data document is the approved mapping. The older "Schema (old reference)" section, which lists `priceType` and `eligibleDuration`, is superseded and out of scope.
- **QA approach**: The agreed structured data QA approach is the Schema.org Validator plus the Google Rich Results Test, run against a fully populated Special on `tour-operator.lightspeedwp.dev`.
- **Date-order errors** are content problems. The schema reports dates as entered and does not reorder or drop them.
- **Fallback currency** is the existing core helper default. This spec does not introduce a new fallback.
- **`availability` value** stays `LimitedAvailability`. Expiry-aware availability, such as marking expired Specials as discontinued, is a possible future enhancement and out of scope here.
- **Cross-node `@id` linking**: Referencing the core `TouristTrip`/`LodgingBusiness` node IDs instead of URLs needs core changes and is out of scope. URLs (FR-008) are the agreed linking mechanism for now.
- **Core defect noted, not fixed here**: The core helper that builds offered/reviewed items discards its own de-duplication result. FR-007 can be met within this extension. A matching core fix should be raised separately against `tour-operator`.

### Out of Scope

- Tour, Accommodation and Destination schema in the core Tour Operator plugin.
- Team schema (TO-171) and Reviews schema (TO-216).
- Archive, page-selector and gallery schema.
