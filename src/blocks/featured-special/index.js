/**
 * Featured Special Block Variation
 *
 * Registers a block variation for displaying featured specials.
 * Available across all post types and templates.
 *
 * @since 2.2.0
 * @package TO_Specials
 */

import { __ } from '@wordpress/i18n';

wp.domReady(() => {
    wp.blocks.registerBlockVariation('core/group', {
        name: 'lsx-tour-operator/featured-special',
        title: __('Featured Specials', 'to-specials'),
        icon: 'tag',
        description: __('Displays Specials with the Featured tag.', 'to-specials'),
        category: 'lsx-tour-operator',
        keywords: [
            __('featured', 'to-specials'),
            __('specials', 'to-specials'),
            __('offers', 'to-specials'),
            __('deals', 'to-specials'),
        ],
        attributes: {
            metadata: {
                name: 'Featured Special',
            },
            className: 'lsx-featured-query-wrapper',
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
                            content: __('Featured Specials', 'to-specials'),
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
                                name: 'Featured Special Query',
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
                                    className: 'lsx-featured-special-query',
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
        isActive: (blockAttributes, variationAttributes) => {
            return blockAttributes.className === variationAttributes.className;
        },
    });
});
