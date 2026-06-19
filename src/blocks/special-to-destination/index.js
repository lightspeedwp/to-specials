/**
 * Special to Destination Block Variation
 *
 * @since 2.2.0
 * @package TO_Specials
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerSpecialToDestinationVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/special-to-destination',
            title: __('Special to Destination', 'to-specials'),
            icon: 'admin-site',
            category: 'lsx-tour-operator',
            description: __('Displays the destinations connected to this special.', 'to-specials'),
            keywords: [__('destination', 'to-specials'), __('special', 'to-specials'), __('connection', 'to-specials'), __('location', 'to-specials')],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: { name: __('Special to Destination', 'to-specials') },
                className: 'lsx-to-destination-wrapper',
                layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'top' },
            },
            innerBlocks: [
                [
                    'core/group',
                    { layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'middle' } },
                    [['lsx-tour-operator/icons', { iconType: 'solid', iconName: 'destinationIcon' }]],
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
                                        content: { source: 'lsx/post-connection', args: { key: 'destination_to_special' } },
                                    },
                                },
                                prefix: __('Destination:', 'to-specials'),
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
                            { name: 'lsx-tour-operator/icons', attributes: { iconType: 'solid', iconName: 'destinationIcon' } },
                            { name: 'core/paragraph', attributes: { content: '<strong>' + __('Destination: ', 'to-specials') + '</strong>' + __('Cape Town', 'to-specials') } },
                        ],
                    },
                ],
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(['special'], ['special'], registerSpecialToDestinationVariation);
    conditionalRegister();
});
