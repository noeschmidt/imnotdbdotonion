/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php", "./src/*.php", "./lang/*.php", "./lang/*.html", "./js/custom.min.js", "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        'white': '#FFFFFF',
        'black': '#000000',
        'red-shade': {
          30: '#FFFAFA',
          40: '#FFE5E5',
          50: '#FFCCCC',
          60: '#FF9999',
          70: '#FF3333',
          80: '#FF1919',
          90: '#FF0000',
          100: '#E50000',
        },
        'black-shade': {
          30: '#4D4D4D',
          40: '#404040',
          50: '#333333',
          60: '#262626',
          70: '#1F1F1F',
          80: '#1A1A1A',
          90: '#141414',
          100: '#0F0F0F',
        },
        'grey-shade': {
          30: '#FCFCFD',
          40: '#F7F7F8',
          50: '#F1F1F3',
          60: '#E4E4E7',
          70: '#BFBFBF',
          80: '#B3B3B3',
          90: '#A6A6A6',
          100: '#999999',
        },
      },
      fontFamily: {
        'outfit': ['Outfit', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('flowbite/plugin'),
  ],
}

