/**
 * Special Gallery Block Variation
 *
 * Registers a gallery block variation scoped to the special post type.
 * Only available on special post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Specials
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerSpecialGalleryVariation = () => {
        wp.blocks.registerBlockVariation('core/gallery', {
            name: 'lsx-tour-operator/special-gallery',
            title: __('Special - Gallery', 'to-specials'),
            icon: 'format-gallery',
            category: 'lsx-tour-operator',
            description: __('Display the gallery images for this special.', 'to-specials'),
            keywords: [__('gallery', 'to-specials'), __('images', 'to-specials'), __('special', 'to-specials'), __('photos', 'to-specials')],
            attributes: {
                metadata: {
                    name: __('Special - Gallery', 'to-specials'),
                    bindings: {
                        content: { source: 'lsx/gallery' },
                    },
                },
                linkTo: 'none',
                sizeSlug: 'thumbnail',
            },
            innerBlocks: [
                ['core/image', { sizeSlug: 'large', url: lsxToEditor.assetsUrl + 'blocks/placeholder.png' }],
                ['core/image', { sizeSlug: 'large', url: lsxToEditor.assetsUrl + 'blocks/placeholder.png' }],
                ['core/image', { sizeSlug: 'large', url: lsxToEditor.assetsUrl + 'blocks/placeholder.png' }],
            ],
            isDefault: false,
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(['special'], ['special'], registerSpecialGalleryVariation);
    conditionalRegister();
});
