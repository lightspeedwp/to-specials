/**
 * Special Related Special Block Variation
 *
 * Registers a block variation for displaying other specials.
 * Only available on special post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Specials
 */

import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';
import { __ } from '@wordpress/i18n';

function registerSpecialRelatedSpecialVariation() {
    wp.blocks.registerBlockVariation('core/group', {
        name: 'lsx-tour-operator/special-related-special',
        title: __('Related Specials', 'to-specials'),
        icon: 'tag',
        description: __('Displays other specials from the site.', 'to-specials'),
        category: 'lsx-tour-operator',
        keywords: [
            __('specials', 'to-specials'),
            __('related', 'to-specials'),
            __('similar', 'to-specials'),
            __('offers', 'to-specials'),
        ],
        attributes: {
            metadata: {
                name: __('Related Specials', 'to-specials'),
            },
            className: 'lsx-related-special-query-wrapper',
            align: 'full',
            layout: {
                type: 'constrained',
            },
            tagName: 'section',
        },
        innerBlocks: [
            [
                'core/group',
                {
                    align: 'wide',
                    layout: { type: 'flex', flexWrap: 'nowrap' },
                },
                [
                    [
                        'core/separator',
                        {
                            style: {
                                layout: { selfStretch: 'fill', flexSize: null },
                            },
                        },
                    ],
                    [
                        'core/heading',
                        {
                            textAlign: 'center',
                            content: __('Related Specials', 'to-specials'),
                            level: 2,
                        },
                    ],
                    [
                        'core/separator',
                        {
                            style: {
                                layout: { selfStretch: 'fill', flexSize: null },
                            },
                        },
                    ],
                ],
            ],
            [
                'core/group',
                { align: 'wide', layout: { type: 'constrained' } },
                [
                    [
                        'core/query',
                        {
                            metadata: {
                                name: __('Related Special Query', 'to-specials'),
                            },
                            query: {
                                perPage: 8,
                                postType: 'special',
                                order: 'asc',
                                orderBy: 'date',
                            },
                            align: 'wide',
                        },
                        [
                            [
                                'core/post-template',
                                {
                                    className: 'lsx-special-related-special-query',
                                    layout: {
                                        type: 'grid',
                                        columnCount: 3,
                                    },
                                },
                                [
                                    [
                                        'core/pattern',
                                        {
                                            slug: 'lsx-tour-operator/special-card',
                                        },
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        isActive: (blockAttributes) => {
            return (
                blockAttributes.className === 'lsx-related-special-query-wrapper' ||
                (blockAttributes.className &&
                    blockAttributes.className.includes('lsx-related-special-query-wrapper'))
            );
        },
    });
}

const conditionalRegister = registerForPostTypesAndTemplates(
    ['special'],
    ['special'],
    registerSpecialRelatedSpecialVariation
);

wp.domReady(conditionalRegister);
