/**
 * Special Tagline Block Variation
 *
 * @since 2.2.0
 * @package TO_Specials
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerSpecialTaglineVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/special-tagline',
            title: __('Special Tagline', 'to-specials'),
            icon: 'editor-textcolor',
            category: 'lsx-tour-operator',
            description: __('Displays the tagline for this special.', 'to-specials'),
            keywords: [__('tagline', 'to-specials'), __('special', 'to-specials'), __('subtitle', 'to-specials')],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: { name: __('Special Tagline', 'to-specials') },
                className: 'lsx-tagline-wrapper',
                layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'top' },
            },
            innerBlocks: [
                [
                    'core/paragraph',
                    {
                        metadata: {
                            bindings: {
                                content: { source: 'lsx/post-meta', args: { key: 'tagline' } },
                            },
                        },
                        prefix: __('Tagline:', 'to-specials'),
                        prefixBold: true,
                    },
                ],
            ],
            example: {
                innerBlocks: [
                    {
                        name: 'core/paragraph',
                        attributes: {
                            content: '<strong>' + __('Tagline: ', 'to-specials') + '</strong>' + __('Book now and save 20%', 'to-specials'),
                        },
                    },
                ],
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(['special'], ['special'], registerSpecialTaglineVariation);
    conditionalRegister();
});
