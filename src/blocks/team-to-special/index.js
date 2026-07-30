/**
 * Team to Special Block Variation
 *
 * @since 2.2.0
 * @package TO_Specials
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerTeamToSpecialVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/team-to-special',
            title: __('Team to Special', 'to-specials'),
            icon: 'admin-users',
            category: 'lsx-tour-operator',
            description: __('Displays the team members connected to this special.', 'to-specials'),
            keywords: [__('team', 'to-specials'), __('special', 'to-specials'), __('connection', 'to-specials'), __('guide', 'to-specials')],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: { name: __('Team to Special', 'to-specials') },
                className: 'lsx-team-to-special-wrapper',
                layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'top' },
            },
            innerBlocks: [
                [
                    'core/group',
                    { layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'middle' } },
                    [['lsx-tour-operator/icons', { iconType: 'solid', iconName: 'teamIcon' }]],
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
                                        content: { source: 'lsx/post-connection', args: { key: 'team_to_special' } },
                                    },
                                },
                                prefix: __('Guide:', 'to-specials'),
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
                            { name: 'lsx-tour-operator/icons', attributes: { iconType: 'solid', iconName: 'teamIcon' } },
                            { name: 'core/paragraph', attributes: { content: '<strong>' + __('Guide: ', 'to-specials') + '</strong>' + __('John Safari Guide', 'to-specials') } },
                        ],
                    },
                ],
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(['special'], ['special'], registerTeamToSpecialVariation);
    conditionalRegister();
});
