/**
 * Special Price Type Block Variation
 *
 * @since 2.2.0
 * @package TO_Specials
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerSpecialPriceTypeVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/special-price-type',
            title: __('Special - Price Type', 'to-specials'),
            icon: 'tag',
            category: 'lsx-tour-operator',
            description: __('Displays the price type for this special.', 'to-specials'),
            keywords: [__('price type', 'to-specials'), __('per person', 'to-specials'), __('special', 'to-specials')],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: { name: __('Special - Price Type', 'to-specials') },
                className: 'lsx-price-type-wrapper',
                layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'top' },
            },
            innerBlocks: [
                [
                    'core/paragraph',
                    {
                        metadata: {
                            bindings: {
                                content: { source: 'lsx/post-meta', args: { key: 'price_type' } },
                            },
                        },
                        prefix: __('Price Type:', 'to-specials'),
                        prefixBold: true,
                    },
                ],
            ],
            example: {
                innerBlocks: [
                    {
                        name: 'core/paragraph',
                        attributes: {
                            content: '<strong>' + __('Price Type: ', 'to-specials') + '</strong>' + __('Per Person Sharing', 'to-specials'),
                        },
                    },
                ],
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(['special'], ['special'], registerSpecialPriceTypeVariation);
    conditionalRegister();
});
