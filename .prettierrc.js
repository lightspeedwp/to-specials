/**
 * Prettier configuration for WordPress development
 *
 * @see https://prettier.io/docs/en/configuration.html
 */
module.exports = {
	// Use WordPress coding standards
	printWidth: 80,
	tabWidth: 4,
	useTabs: true,
	semi: true,
	singleQuote: true,
	quoteProps: 'as-needed',
	trailingComma: 'es5',
	bracketSpacing: true,
	bracketSameLine: false,
	arrowParens: 'always',
	endOfLine: 'lf',

	// Override for specific file types
	overrides: [
		{
			files: '*.json',
			options: {
				useTabs: false,
				tabWidth: 2,
			},
		},
		{
			files: '*.yml',
			options: {
				useTabs: false,
				tabWidth: 2,
			},
		},
		{
			files: '*.yaml',
			options: {
				useTabs: false,
				tabWidth: 2,
			},
		},
	],
};
