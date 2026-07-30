/**
 * Special Booking Validity Block Variation
 *
 * @since 2.2.0
 * @package TO_Specials
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerSpecialBookingValidityVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/special-booking-validity',
            title: __('Special - Booking Validity', 'to-specials'),
            icon: 'calendar',
            category: 'lsx-tour-operator',
            description: __('Displays the booking validity dates for this special.', 'to-specials'),
            keywords: [__('booking', 'to-specials'), __('validity', 'to-specials'), __('dates', 'to-specials'), __('special', 'to-specials')],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: { name: __('Special - Booking Validity', 'to-specials') },
                className: 'lsx-booking-validity-wrapper',
                layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'top' },
            },
            innerBlocks: [
                [
                    'core/group',
                    { layout: { type: 'flex', flexWrap: 'nowrap' } },
                    [
                        [
                            'lsx-tour-operator/icons',
                            { iconType: 'solid', iconName: 'calendarIcon' },
                        ],
                    ],
                ],
                [
                    'core/group',
                    { layout: { type: 'flex', orientation: 'vertical', flexWrap: 'nowrap' } },
                    [
                        [
                            'core/paragraph',
                            {
                                metadata: {
                                    bindings: {
                                        content: { source: 'lsx/post-meta', args: { key: 'booking_validity_start' } },
                                    },
                                },
                                prefix: __('Valid From:', 'to-specials'),
                                prefixBold: true,
                            },
                        ],
                        [
                            'core/paragraph',
                            {
                                metadata: {
                                    bindings: {
                                        content: { source: 'lsx/post-meta', args: { key: 'booking_validity_end' } },
                                    },
                                },
                                prefix: __('Valid To:', 'to-specials'),
                                prefixBold: true,
                            },
                        ],
                    ],
                ],
            ],
            example: {
                innerBlocks: [
                    {
                        name: 'core/group',
                        attributes: { layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'middle' } },
                        innerBlocks: [
                            { name: 'lsx-tour-operator/icons', attributes: { iconType: 'solid', iconName: 'calendarIcon' } },
                            { name: 'core/paragraph', attributes: { content: '<strong>' + __('Valid From: ', 'to-specials') + '</strong>' + __('January 2025', 'to-specials') } },
                            { name: 'core/paragraph', attributes: { content: '<strong>' + __('Valid To: ', 'to-specials') + '</strong>' + __('March 2025', 'to-specials') } },
                        ],
                    },
                ],
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(['special'], ['special'], registerSpecialBookingValidityVariation);
    conditionalRegister();
});
