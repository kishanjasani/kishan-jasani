import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal Dependencies.
 */
import edit from './edit';
import metadata from './block.json';

import './style.scss';

registerBlockType( metadata.name, {
	edit,

	save: () => {
		return null;
	},
} );
