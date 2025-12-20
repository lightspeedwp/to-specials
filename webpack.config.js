
/**
 * External dependencies
 */
const RemoveEmptyScriptsPlugin = require( 'webpack-remove-empty-scripts' );
const path = require( 'path' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry(),
		// CSS files
		'style': path.resolve( process.cwd(), 'assets/css/to-specials.scss' ),
		'admin': path.resolve( process.cwd(), 'assets/css/to-specials-admin.scss' ),

		// JavaScript files
		'admin-script': path.resolve( process.cwd(), 'assets/js/to-specials-admin.js' ),
		'frontend': path.resolve( process.cwd(), 'assets/js/to-specials.js' ),
	},

	plugins: [
		...defaultConfig.plugins,
		new RemoveEmptyScriptsPlugin(),
	],

	output: {
		...defaultConfig.output,
		path: path.resolve( process.cwd(), 'build' ),
	}
};
