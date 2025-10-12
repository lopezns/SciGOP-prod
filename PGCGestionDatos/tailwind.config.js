/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        coffee: {
          50: '#fdf8f3',
          100: '#f8eddf', 
          200: '#f0d7bf',
          300: '#e6bb93',
          400: '#d69665',
          500: '#cb7944',
          600: '#bd6139',
          700: '#9d4c31',
          800: '#7e3f2d',
          900: '#663426',
        },
        cream: {
          50: '#fefcf9',
          100: '#fdf7f0',
          200: '#faeadc',
          300: '#f5d5be',
          400: '#eeb896',
          500: '#e59969',
          600: '#d27b47',
          700: '#b16239',
          800: '#905234',
          900: '#78462f',
        }
      },
      fontFamily: {
        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
        'display': ['Poppins', 'ui-sans-serif', 'system-ui'],
      }
    },
  },
  plugins: [],
}
