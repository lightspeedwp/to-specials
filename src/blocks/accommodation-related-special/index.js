/**
 * Accommodation Related Special Block Variation
 *
 * Registers a block variation for displaying specials related to the current accommodation.
 * Only available on accommodation post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Specials
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerAccommodationRelatedSpecialVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/accommodation-related-special',
            title: __('Related Specials', 'to-specials'),
            icon: 'tag',
            description: __('Displays specials related to this accommodation.', 'to-specials'),
            category: 'lsx-tour-operator',
            keywords: [
                __('specials', 'to-specials'),
                __('accommodation', 'to-specials'),
                __('related', 'to-specials'),
                __('offers', 'to-specials'),
            ],
            attributes: {
                metadata: {
                    name: __('Related Specials', 'to-specials'),
                },
                className: 'lsx-accommodation-related-special-query-wrapper',
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
                            { style: { layout: { selfStretch: 'fill', flexSize: null } } },
                        ],
                        [
                            'core/heading',
                            {
                                textAlign: 'center',
                                content: __('Specials', 'to-specials'),
                                level: 2,
                            },
                        ],
                        [
                            'core/separator',
                            { style: { layout: { selfStretch: 'fill', flexSize: null } } },
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
                                    name: __('Related Specials Query', 'to-specials'),
                                },
                                query: {
                                    perPage: 8,
                                    postType: 'special',
                                    order: 'desc',
                                    orderBy: 'date',
                                },
                                align: 'wide',
                            },
                            [
                                [
                                    'core/post-template',
                                    {
                                        className: 'lsx-accommodation-related-special-query',
                                        layout: { type: 'grid', columnCount: 2 },
                                    },
                                    [
                                        [
                                            'core/pattern',
                                            { slug: 'lsx-tour-operator/special-card' },
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            example: {
                innerBlocks: [
                    {
                        name: 'core/group',
                        attributes: {
                            align: 'wide',
                            layout: { type: 'flex', flexWrap: 'nowrap' },
                        },
                        innerBlocks: [
                            {
                                name: 'core/separator',
                                attributes: { style: { layout: { selfStretch: 'fill', flexSize: null } } },
                            },
                            {
                                name: 'core/heading',
                                attributes: {
                                    textAlign: 'center',
                                    content: __('Specials', 'to-specials'),
                                    level: 2,
                                },
                            },
                            {
                                name: 'core/separator',
                                attributes: { style: { layout: { selfStretch: 'fill', flexSize: null } } },
                            },
                        ],
                    },
                    {
                        name: 'core/group',
                        attributes: { align: 'wide', layout: { type: 'constrained' } },
                        innerBlocks: [
                            {
                                name: 'core/group',
                                attributes: {
                                    className: 'lsx-accommodation-related-special-query',
                                    layout: { type: 'grid', columnCount: 2 },
                                },
                                innerBlocks: [
                                    {
                                        name: 'core/group',
                                        attributes: {
                                            className: 'is-style-shadow-sm',
                                            style: { spacing: { blockGap: '0px', padding: { top: '0px', bottom: '0px', left: '0px', right: '0px' } }, border: { radius: '8px' } },
                                            backgroundColor: 'base',
                                            layout: { type: 'constrained' },
                                        },
                                        innerBlocks: [
                                            {
                                                name: 'core/group',
                                                attributes: { style: { spacing: { padding: { top: '5px', bottom: '0px', left: '5px', right: '5px' } } }, layout: { type: 'constrained' } },
                                                innerBlocks: [
                                                    { name: 'core/heading', attributes: { textAlign: 'center', content: __('Summer Safari Deal', 'to-specials'), level: 3, fontSize: 'small', style: { spacing: { margin: { top: '0', bottom: '0' } } } } },
                                                    { name: 'core/paragraph', attributes: { content: __('Save 20% on 7-night safari packages. Valid for travel June–August.', 'to-specials'), style: { spacing: { padding: { left: '5px', right: '5px' } } } } },
                                                ],
                                            },
                                        ],
                                    },
                                    {
                                        name: 'core/group',
                                        attributes: {
                                            className: 'is-style-shadow-sm',
                                            style: { spacing: { blockGap: '0px', padding: { top: '0px', bottom: '0px', left: '0px', right: '0px' } }, border: { radius: '8px' } },
                                            backgroundColor: 'base',
                                            layout: { type: 'constrained' },
                                        },
                                        innerBlocks: [
                                            {
                                                name: 'core/group',
                                                attributes: { style: { spacing: { padding: { top: '5px', bottom: '0px', left: '5px', right: '5px' } } }, layout: { type: 'constrained' } },
                                                innerBlocks: [
                                                    { name: 'core/heading', attributes: { textAlign: 'center', content: __('Honeymoon Package', 'to-specials'), level: 3, fontSize: 'small', style: { spacing: { margin: { top: '0', bottom: '0' } } } } },
                                                    { name: 'core/paragraph', attributes: { content: __('Complimentary room upgrade and romantic dinner for two on your first night.', 'to-specials'), style: { spacing: { padding: { left: '5px', right: '5px' } } } } },
                                                ],
                                            },
                                        ],
                                    },
                                ],
                            },
                        ],
                    },
                ],
            },
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(
        ['accommodation'],
        ['accommodation'],
        registerAccommodationRelatedSpecialVariation
    );
    conditionalRegister();
});
