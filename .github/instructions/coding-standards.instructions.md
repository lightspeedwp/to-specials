---
description: 'Coding standards and review guidelines for TO Specials plugin development'
applyTo: '**.php, **.js, **.ts, **.css, **.scss, **.md'
---

# Coding Standards Instructions

## WordPress Coding Standards for TO Specials Plugin

### PHP Standards

Follow WordPress PHP Coding Standards (WPCS) strictly:

#### Naming Conventions

- **Classes:** Use `TO_Specials_` prefix with Pascal_Case
- **Functions:** Use `to_specials_` prefix with snake_case
- **Variables:** Use snake_case (`$special_data`, `$deal_type`)
- **Constants:** Use UPPER_SNAKE_CASE (`TO_SPECIALS_VERSION`)

#### Code Structure

```php
<?php
/**
 * Class description
 *
 * @package TO_Specials
 * @since 1.0.0
 */
class TO_Specials_Display {

    /**
     * Method description
     *
     * @param int $special_id Special ID.
     * @return string Special HTML output.
     */
    public function display_special( $special_id ) {
        // Implementation
    }
}
```

#### Security Best Practices

1. **Sanitization:** Always sanitize input data

   ```php
   $special_title = sanitize_text_field( $_POST['special_title'] );
   ```

2. **Validation:** Validate all data before processing

   ```php
   if ( ! is_numeric( $discount ) || $discount < 0 || $discount > 100 ) {
       return false;
   }
   ```

3. **Escaping:** Escape all output

   ```php
   echo esc_html( $special_title );
   echo wp_kses_post( $special_content );
   ```

4. **Nonces:** Use nonces for form submissions
   ```php
   wp_nonce_field( 'to_specials_submit', 'to_specials_nonce' );
   ```

#### Database Operations

- Use `$wpdb->prepare()` for all queries
- Prefix all custom tables with `$wpdb->prefix`
- Use WordPress database functions when possible

#### Hooks and Filters

- Prefix all hooks with `to_specials_`
- Document all hooks with proper DocBlocks
- Provide examples in hook documentation

### File Organization

- One class per file
- Use autoloading for classes
- Keep functions in separate includes
- Organize by functionality, not file type

### Documentation

- All functions must have DocBlocks
- Use @since tags for version tracking
- Include @param and @return documentation
- Add inline comments for complex logic

### Performance

- Cache expensive operations
- Use transients for temporary data
- Minimize database queries
- Optimize for mobile and slow connections

## Code Review Guidelines

The following instructions are only to be applied when performing a code review.

### README updates

* [ ] The new file should be added to the `README.md`.

### Prompt file guide

**Only apply to files that end in `.prompt.md`**

* [ ] The prompt has markdown front matter.
* [ ] The prompt has a `mode` field specified of either `agent` or `ask`.
* [ ] The prompt has a `description` field.
* [ ] The `description` field is not empty.
* [ ] The `description` field value is wrapped in single quotes.
* [ ] The file name is lower case, with words separated by hyphens.
* [ ] Encourage the use of `tools`, but it's not required.
* [ ] Strongly encourage the use of `model` to specify the model that the prompt is optimised for.

### Instruction file guide

**Only apply to files that end in `.instructions.md`**

* [ ] The instruction has markdown front matter.
* [ ] The instruction has a `description` field.
* [ ] The `description` field is not empty.
* [ ] The `description` field value is wrapped in single quotes.
* [ ] The file name is lower case, with words separated by hyphens.
* [ ] The instruction has an `applyTo` field that specifies the file or files to which the instructions apply. If they wish to specify multiple file paths they should formated like `'**.js, **.ts'`.

### Chat Mode file guide

**Only apply to files that end in `.chatmode.md`**

* [ ] The chat mode has markdown front matter.
* [ ] The chat mode has a `description` field.
* [ ] The `description` field is not empty.
* [ ] The `description` field value is wrapped in single quotes.
* [ ] The file name is lower case, with words separated by hyphens.
* [ ] Encourage the use of `tools`, but it's not required.
* [ ] Strongly encourage the use of `model` to specify the model that the chat mode is optimised for.