/**
 * Special Duration Block Variation
 *
 * @since 2.2.0
 * @package TO_Specials
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerSpecialDurationVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/special-duration',
            title: __('Special Duration', 'to-specials'),
            icon: 'clock',
            category: 'lsx-tour-operator',
            description: __('Displays the duration for this special.', 'to-specials'),
            keywords: [__('duration', 'to-specials'), __('length', 'to-specials'), __('days', 'to-specials'), __('special', 'to-specials')],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: { name: __('Special Duration', 'to-specials') },
                className: 'lsx-special-duration-wrapper',
                layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'top' },
            },
            innerBlocks: [
                [
                    'core/group',
                    { layout: { type: 'flex', flexWrap: 'nowrap' } },
                    [
                        [
                            'lsx-tour-operator/icons',
                            { iconType: 'solid', iconName: 'durationIcon' },
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
                                        content: { source: 'lsx/post-meta', args: { key: 'duration' } },
                                    },
                                },
                                prefix: __('Duration:', 'to-specials'),
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
                            { name: 'lsx-tour-operator/icons', attributes: { iconType: 'solid', iconName: 'durationIcon' } },
                            { name: 'core/paragraph', attributes: { content: '<strong>' + __('Duration: ', 'to-specials') + '</strong>' + '7 days' } },
                        ],
                    },
                ],
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(['special'], ['special'], registerSpecialDurationVariation);
    conditionalRegister();
});
