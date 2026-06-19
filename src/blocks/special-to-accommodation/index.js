/**
 * Special to Accommodation Block Variation
 *
 * @since 2.2.0
 * @package TO_Specials
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerSpecialToAccommodationVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/special-to-accommodation',
            title: __('Special to Accommodation', 'to-specials'),
            icon: 'admin-home',
            category: 'lsx-tour-operator',
            description: __('Displays the accommodations connected to this special.', 'to-specials'),
            keywords: [__('accommodation', 'to-specials'), __('special', 'to-specials'), __('connection', 'to-specials'), __('lodging', 'to-specials')],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: { name: __('Special to Accommodation', 'to-specials') },
                className: 'lsx-to-accommodation-wrapper',
                layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'top' },
            },
            innerBlocks: [
                [
                    'core/group',
                    { layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'middle' } },
                    [['lsx-tour-operator/icons', { iconType: 'solid', iconName: 'accommodationIcon' }]],
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
                                        content: { source: 'lsx/post-connection', args: { key: 'accommodation_to_special' } },
                                    },
                                },
                                prefix: __('Accommodation:', 'to-specials'),
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
                            { name: 'lsx-tour-operator/icons', attributes: { iconType: 'solid', iconName: 'accommodationIcon' } },
                            { name: 'core/paragraph', attributes: { content: '<strong>' + __('Accommodation: ', 'to-specials') + '</strong>' + __('Safari Lodge', 'to-specials') } },
                        ],
                    },
                ],
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(['special'], ['special'], registerSpecialToAccommodationVariation);
    conditionalRegister();
});
