/**
 * Special Related Tour Block Variation
 *
 * Registers a block variation for displaying specials related to the current tour.
 * Only available on tour post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Specials
 */

import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';
import { __ } from '@wordpress/i18n';

function registerSpecialRelatedTourVariation() {
    wp.blocks.registerBlockVariation('core/group', {
        name: 'lsx-tour-operator/special-related-tour',
        title: __('Related Specials', 'to-specials'),
        icon: 'tag',
        description: __('Displays specials related to this tour.', 'to-specials'),
        category: 'lsx-tour-operator',
        keywords: [
            __('specials', 'to-specials'),
            __('tour', 'to-specials'),
            __('related', 'to-specials'),
            __('offers', 'to-specials'),
        ],
        attributes: {
            metadata: { name: __('Related Specials', 'to-specials') },
            className: 'lsx-special-related-tour-query-wrapper',
            align: 'full',
            layout: { type: 'constrained' },
            tagName: 'section',
        },
        innerBlocks: [
            [
                'core/group',
                { align: 'wide', layout: { type: 'flex', flexWrap: 'nowrap' } },
                [
                    ['core/separator', { style: { layout: { selfStretch: 'fill', flexSize: null } } }],
                    ['core/heading', { textAlign: 'center', content: __('Specials', 'to-specials'), level: 2 }],
                    ['core/separator', { style: { layout: { selfStretch: 'fill', flexSize: null } } }],
                ],
            ],
            [
                'core/group',
                { align: 'wide', layout: { type: 'constrained' } },
                [
                    [
                        'core/query',
                        {
                            metadata: { name: __('Related Specials Query - Tour', 'to-specials') },
                            query: { perPage: 8, postType: 'special', order: 'desc', orderBy: 'date' },
                            align: 'wide',
                        },
                        [
                            [
                                'core/post-template',
                                {
                                    className: 'lsx-special-related-tour-query',
                                    layout: { type: 'grid', columnCount: 2 },
                                },
                                [['core/pattern', { slug: 'lsx-tour-operator/special-card' }]],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        isActive: (blockAttributes) => {
            return (
                blockAttributes.className === 'lsx-special-related-tour-query-wrapper' ||
                (blockAttributes.className &&
                    blockAttributes.className.includes('lsx-special-related-tour-query-wrapper'))
            );
        },
    });
}

const conditionalRegister = registerForPostTypesAndTemplates(
    ['tour'],
    ['tour'],
    registerSpecialRelatedTourVariation
);

wp.domReady(conditionalRegister);
