/**
 * Special to Tour Block Variation
 *
 * @since 2.2.0
 * @package TO_Specials
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerSpecialToTourVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/special-to-tour',
            title: __('Special to Tour', 'to-specials'),
            icon: 'location-alt',
            category: 'lsx-tour-operator',
            description: __('Displays the tours connected to this special.', 'to-specials'),
            keywords: [__('tour', 'to-specials'), __('special', 'to-specials'), __('connection', 'to-specials'), __('itinerary', 'to-specials')],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: { name: __('Special to Tour', 'to-specials') },
                className: 'lsx-to-tour-wrapper',
                layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'top' },
            },
            innerBlocks: [
                [
                    'core/group',
                    { layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'middle' } },
                    [['lsx-tour-operator/icons', { iconType: 'solid', iconName: 'tourIcon' }]],
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
                                        content: { source: 'lsx/post-connection', args: { key: 'tour_to_special' } },
                                    },
                                },
                                prefix: __('Tour:', 'to-specials'),
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
                            { name: 'lsx-tour-operator/icons', attributes: { iconType: 'solid', iconName: 'tourIcon' } },
                            { name: 'core/paragraph', attributes: { content: '<strong>' + __('Tour: ', 'to-specials') + '</strong>' + __('Big Five Safari', 'to-specials') } },
                        ],
                    },
                ],
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(['special'], ['special'], registerSpecialToTourVariation);
    conditionalRegister();
});
