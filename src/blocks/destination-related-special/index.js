/**
 * Destination Related Special Block Variation
 *
 * Registers a block variation for displaying destinations related to the current special.
 * Only available on special post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Specials
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerDestinationRelatedSpecialVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/destination-related-special',
            title: __('Related Destination', 'to-specials'),
            icon: 'tag',
            description: __('Displays destination related to this special.', 'to-specials'),
            category: 'lsx-tour-operator',
            keywords: [
                __('destination', 'to-specials'),
                __('special', 'to-specials'),
                __('related', 'to-specials'),
                __('offers', 'to-specials'),
            ],
            attributes: {
                metadata: {
                    name: __('Related Destination', 'to-specials'),
                },
                className: 'lsx-destination-related-special-query-wrapper',
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
                                content: __('Related Destination', 'to-specials'),
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
                                    name: __('Related Destination Query', 'to-specials'),
                                },
                                query: {
                                    perPage: 8,
                                    postType: 'destination',
                                    order: 'desc',
                                    orderBy: 'date',
                                },
                                align: 'wide',
                            },
                            [
                                [
                                    'core/post-template',
                                    {
                                        className: 'lsx-destination-related-special-query',
                                        layout: { type: 'grid', columnCount: 2 },
                                    },
                                    [
                                        [
                                            'core/pattern',
                                            { slug: 'lsx-tour-operator/destination-card' },
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
                                    content: __('Related Destination', 'to-specials'),
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
                                    className: 'lsx-destination-related-special-query',
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
                                                    { name: 'core/heading', attributes: { textAlign: 'center', content: __('Kenya', 'to-specials'), level: 3, fontSize: 'small', style: { spacing: { margin: { top: '0', bottom: '0' } } } } },
                                                    { name: 'core/paragraph', attributes: { content: __('Explore the wildlife-rich plains of Kenya, part of this special offer.', 'to-specials'), style: { spacing: { padding: { left: '5px', right: '5px' } } } } },
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
                                                    { name: 'core/heading', attributes: { textAlign: 'center', content: __('Botswana', 'to-specials'), level: 3, fontSize: 'small', style: { spacing: { margin: { top: '0', bottom: '0' } } } } },
                                                    { name: 'core/paragraph', attributes: { content: __('Discover the Okavango Delta, included in this special itinerary.', 'to-specials'), style: { spacing: { padding: { left: '5px', right: '5px' } } } } },
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
        ['special'],
        ['special'],
        registerDestinationRelatedSpecialVariation
    );
    conditionalRegister();
});
