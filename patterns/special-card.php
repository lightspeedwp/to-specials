<?php

/**
 * Specials Card Pattern
 *
 * A card layout for displaying specials in query loops.
 * Includes featured photo, name, role, tagline, contact details and social links.
 *
 * @package    TO_Special
 * @subpackage Patterns
 * @since      2.2.0
 * @version    2.2.0
 */

// phpcs:ignoreFile PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage

return array(
	'title'         => __( 'Special Card', 'to-special' ),
	'description'   => __( 'A card layout for displaying specials in query loops with featured photo, title, price, duration, booking validity and excerpt.', 'to-special' ),
	'categories'    => array( 'lsx-tour-operator' ),
	'keywords'      => array(
		__( 'special', 'to-special' ),
		__( 'price', 'to-special' ),
		__( 'duration', 'to-special' ),
		__( 'validity', 'to-special' ),
		__( 'card', 'to-special' ),
	),
	'postTypes'     => array( 'wp_template' ),
	'blockTypes'    => array( 'core/post-template' ),
	'templateTypes' => array( 'archive', 'single', 'archive-special', 'single-tour', 'single-accommodation', 'single-destination' ),
	'viewportWidth' => 400,
	'content'       => '<!-- wp:group {"metadata":{"name":"Special Card"},"className":"overflow-hidden is-style-shadow-sm","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"},"ariaLabel":"Special Card"} -->
<div aria-label="Special Card" class="wp-block-group overflow-hidden is-style-shadow-sm"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"3/2","linkTarget":"_blank"} /-->

<!-- wp:group {"metadata":{"name":"Content"},"style":{"spacing":{"padding":{"right":"var:preset|spacing|30","left":"var:preset|spacing|30"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><!-- wp:group {"metadata":{"name":"Special Title"},"className":"center-vertically","style":{"dimensions":{"minHeight":"3.75rem"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group center-vertically" style="min-height:3.75rem"><!-- wp:post-title {"textAlign":"center","level":3,"isLink":true,"style":{"elements":{"link":{":hover":{"color":{"text":"var:preset|color|primary-700"}}}}},"fontSize":"large"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Special Information"},"className":"lsx-tour-info","style":{"spacing":{"padding":{"left":"var:preset|spacing|20","right":"var:preset|spacing|20","top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"},"blockGap":"0"},"border":{"top":{"width":"1px"},"bottom":{"width":"1px"}}},"fontSize":"medium","layout":{"type":"default"},"ariaLabel":"Special details"} -->
<div aria-label="Special details" class="wp-block-group lsx-tour-info has-medium-font-size" style="border-top-width:1px;border-bottom-width:1px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)"><!-- wp:group {"metadata":{"name":"Price Row"},"className":"lsx-price-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group lsx-price-wrapper"><!-- wp:group {"className":"lsx-info-label","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group lsx-info-label"><!-- wp:lsx-tour-operator/icons {"iconName":"priceIcon"} /-->

<!-- wp:paragraph {"style":{"spacing":{"padding":{"top":"0.125rem","bottom":"0.125rem"}}}} -->
<p style="padding-top:0.125rem;padding-bottom:0.125rem">From:</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"lsx/post-meta","args":{"key":"price"}}},"name":"Price"},"className":"lsx-info-value","style":{"spacing":{"padding":{"top":"0.125rem","bottom":"0.125rem"}}}} -->
<p class="lsx-info-value" style="padding-top:0.125rem;padding-bottom:0.125rem"></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Duration Row"},"className":"lsx-duration-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group lsx-duration-wrapper"><!-- wp:group {"className":"lsx-info-label","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group lsx-info-label"><!-- wp:lsx-tour-operator/icons {"iconName":"durationIcon"} /-->

<!-- wp:paragraph {"style":{"spacing":{"padding":{"top":"0.125rem","bottom":"0.125rem"}}}} -->
<p style="padding-top:0.125rem;padding-bottom:0.125rem">Duration:</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"lsx-info-value","style":{"spacing":{"padding":{"top":"0.125rem","bottom":"0.125rem"},"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group lsx-info-value" style="padding-top:0.125rem;padding-bottom:0.125rem"><!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"lsx/post-meta","args":{"key":"duration"}}},"name":"Duration"}} -->
<p></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Days</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Validity Row"},"className":"lsx-booking-validity-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group lsx-booking-validity-wrapper"><!-- wp:group {"className":"lsx-info-label","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group lsx-info-label"><!-- wp:lsx-tour-operator/icons {"iconType":"solid","iconName":"bookingValidityIcon"} /-->

<!-- wp:paragraph {"style":{"spacing":{"padding":{"top":"0.125rem","bottom":"0.125rem"}}}} -->
<p style="padding-top:0.125rem;padding-bottom:0.125rem">Valid until:</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"lsx/post-meta","args":{"key":"booking_validity_end"}}},"name":"Booking Validity End"},"className":"lsx-info-value","style":{"spacing":{"padding":{"top":"0.125rem","bottom":"0.125rem"}}}} -->
<p class="lsx-info-value" style="padding-top:0.125rem;padding-bottom:0.125rem"></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Special Text Content"},"style":{"spacing":{"padding":{"right":"var:preset|spacing|20","left":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)"><!-- wp:post-excerpt {"showMoreOnNewLine":false,"excerptLength":40,"className":"line-clamp-4","fontSize":"medium"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary","width":100,"metadata":{"name":"Permalink"},"style":{"border":{"radius":"0px"}}} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-primary-background-color has-background wp-element-button" href="#permalink" style="border-radius:0px">View Special</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
);