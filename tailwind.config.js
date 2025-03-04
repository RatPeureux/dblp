/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [
        "html/**/*.php",
        "html/**/*.html",
        "views/**/*.php",
        "views/**/*.html",
	],
  theme: {
      extend: {
          fontFamily: {
              'cormorant': ['Cormorant-Bold'],
              'sans': ['Poppins'],
          },
          fontSize: {
              'small': ['14px'],
              'h1': ['32px'],
              'h2': ['24px'],
              'h3': ['20px'],
              'h4': ['18px'],
              'PACT': ['35px', {
                  letterSpacing: '0.2em',
              }],
          },
          colors: {
              'rouge-logo': '#EA4335',
              'primary': '#F2771B',
              'secondary': '#0a0035',
              'base100': '#F1F3F4',
              'base200': '#E0E0E0',
              'base300': '#CCCCCC',
              'neutre': '#000',
              'gris': '#828282',
              'blur': "#F1F3F4",
          },
          spacing: {
              '1/6': '16%',
          },
          animation: {
              'expand-width': 'expandWidth 1s ease-out forwards',
              scale: 'scale 1s ease-in-out infinite',

          },
          keyframes: {
              expandWidth: {
                  '0%': { width: '100%' },
                  '100%': { width: '0%' },
              },
              scale: {
                '0%, 100%': { transform: 'scale(1.01)' },
                '50%': { transform: 'scale(0.99)' },
              },
          },
          boxShadow: {
              'custom': '0 0 12px 12px rgba(210, 210, 210, 0.5)',
          }
      },
  },
  plugins: [],
}


