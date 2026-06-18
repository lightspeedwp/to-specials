/**
 * Special Price Block Variation
 *
 * @since 2.2.0
 * @package TO_Specials
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerSpecialPriceVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/special-price',
            title: __('Special Price', 'to-specials'),
            icon: 'money-alt',
            category: 'lsx-tour-operator',
            description: __('Displays the price for this special.', 'to-specials'),
            keywords: [__('price', 'to-specials'), __('cost', 'to-specials'), __('special', 'to-specials'), __('amount', 'to-specials')],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: { name: __('Special Price', 'to-specials') },
                className: 'lsx-special-price-wrapper',
                layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'top' },
            },
            innerBlocks: [
                [
                    'core/group',
                    { layout: { type: 'flex', flexWrap: 'nowrap' } },
                    [
                        [
                            'lsx-tour-operator/icons',
                            { iconType: 'solid', iconName: 'priceIcon' },
                        ],
                    ],
                ],
                [
                    'core/group',
                    { layout: { type: 'flex', flexWrap: 'nowrap' } },
                    [
                        [
                            'core/paragraph',
                            {
                                metadata: {
                                    bindings: {
                                        content: { source: 'lsx/post-meta', args: { key: 'price' } },
                                    },
                                },
                                prefix: __('Price:', 'to-specials'),
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
                            { name: 'lsx-tour-operator/icons', attributes: { iconType: 'solid', iconName: 'priceIcon' } },
                            { name: 'core/paragraph', attributes: { content: '<strong>' + __('Price: ', 'to-specials') + '</strong>' + '$1,500' } },
                        ],
                    },
                ],
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(['special'], ['special'], registerSpecialPriceVariation);
    conditionalRegister();
});
