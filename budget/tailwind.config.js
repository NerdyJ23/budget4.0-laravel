import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
	safelist: [{
		pattern: /grid-cols-./,
		variants: ['xs', 'sm', 'md', 'lg', 'xl', '2xl']
	}],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
			keyframes: {
				"shake-horizontal": {
					'0%, 100%': {
						transform: 'translate(0px, 0px)'
					},
					'10%, 90%': {
						transform: 'translate(1px, 0px)'
					},
					'20%, 80%': {
						transform: 'translate(-1px, 0px)'
					},
					'30%, 70%': {
						transform: 'translate(2px, 0px)'
					},
					'40%, 60%': {
						transform: 'translate(-2px, 0px)'
					},
					'50%': {
						transform: 'translate(0px, 0px)'
					}
				},
				"hop": {
					'25%': {
						transform: 'translateY(-2px)'
					},
					'100%': {
						transform: 'translateY(0px)'
					},
				},
			},
			animation: {
				"shake-horizontal": "shake-horizontal 1s 1",
				"hop-once": "hop .25s 1"
			}
        },
    },

    plugins: [forms],
};
