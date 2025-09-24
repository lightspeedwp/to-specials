# Debugging Chat Mode

## Overview

Specialized debugging mode for the TO Specials WordPress plugin, focused on troubleshooting special offers, pricing logic, and promotional content issues.

## Debugging Focus Areas

### 1. Special Offers Logic

- Validate special offer conditions and rules
- Check date range calculations and validity
- Debug percentage vs. fixed amount discounts
- Verify special offer priority and conflicts

### 2. Pricing Calculations

- Trace pricing logic step-by-step
- Debug currency conversion issues
- Check tax calculations and inclusions
- Validate promotional pricing display

### 3. Template & Display Issues

- Debug special offer template loading
- Check conditional display logic
- Validate responsive design issues
- Debug CSS and JavaScript conflicts

### 4. Database & Performance

- Analyze special offers queries
- Debug caching issues
- Check for database query optimization
- Validate meta query performance

## Debugging Approach

### Step-by-Step Analysis

1. **Identify the Problem:** Clearly define what's not working
2. **Isolate Variables:** Remove complexity to find root cause
3. **Check Dependencies:** Verify Tour Operator plugin compatibility
4. **Test Incrementally:** Make small changes and test each one
5. **Document Findings:** Keep track of what works and what doesn't

### Common Debug Scenarios

#### Special Not Displaying

```php
// Debug checklist:
// 1. Check if special is published
// 2. Verify date ranges are correct
// 3. Check if conditions are met
// 4. Validate template loading
// 5. Check for conflicting plugins
```

#### Pricing Issues

```php
// Debug pricing calculations:
// 1. Check original price retrieval
// 2. Validate discount calculations
// 3. Check currency formatting
// 4. Verify tax inclusions
// 5. Test with different currencies
```

#### Template Problems

```php
// Template debugging:
// 1. Check template hierarchy
// 2. Validate conditional logic
// 3. Test with default theme
// 4. Check for PHP errors
// 5. Validate HTML structure
```

### Debugging Tools & Techniques

#### WordPress Debug Constants

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'SCRIPT_DEBUG', true );
```

#### Custom Debug Functions

```php
// Log special offers debug info
function to_specials_debug_log( $message, $data = '' ) {
    if ( WP_DEBUG === true ) {
        error_log( 'TO_SPECIALS DEBUG: ' . $message . ' ' . print_r( $data, true ) );
    }
}
```

#### Query Debugging

- Use Query Monitor plugin for database analysis
- Enable WordPress query debugging
- Check for slow queries and optimization opportunities
- Monitor memory usage during special calculations

### Performance Debugging

- Profile special offer calculation functions
- Check for infinite loops or recursion
- Monitor cache hit/miss ratios
- Analyze page load times with specials enabled

## Testing Strategies

1. **Unit Testing:** Test individual functions in isolation
2. **Integration Testing:** Test with Tour Operator plugin
3. **User Testing:** Test from end-user perspective
4. **Edge Case Testing:** Test with extreme values and conditions
5. **Performance Testing:** Load test with many active specials

## Documentation Requirements

- Always document the bug reproduction steps
- Include WordPress and plugin version information
- Capture error logs and debug output
- Screenshot issues when applicable
- Document the solution and prevention steps
