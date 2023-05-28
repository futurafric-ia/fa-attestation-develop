const defaultTheme = require('tailwindcss/defaultTheme')
const colors = require('tailwindcss/colors')

module.exports = {
  purge: {
    content: [
      './app/**/*.php',
      './resources/**/*.html',
      './resources/**/*.js',
      './resources/**/*.jsx',
      './resources/**/*.ts',
      './resources/**/*.tsx',
      './resources/**/*.php',
      './resources/**/*.vue',
    ],
  },
  theme: {
    extend: {
      maxHeight: {
        0: '0',
        xl: '36rem',
      },
      fontFamily: {
        sans: ['Inter', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        primary: {
          50: '#efebe8',
          100: '#e0d6d0',
          200: '#d0c2b9',
          300: '#c1ada1',
          400: '#b1998a',
          500: '#a18472',
          600: '#92705b',
          700: '#825b43',
          800: '#73472c',
          900: '#633214',
        },
        secondary: {
          50: '#eeefef',
          100: '#dedede',
          200: '#cdcece',
          300: '#bdbdbe',
          400: '#adadae',
          500: '#9c9d9d',
          600: '#8b8c8d',
          700: '#7a7b7d',
          800: '#6a6b6c',
          900: '#595a5c',
        },
        accent: {
          50: '#fefcf2',
          100: '#fcf8e5',
          200: '#fbf5d8',
          300: '#faf2cb',
          400: '#f9efbe',
          500: '#f7ebb0',
          600: '#f6e8a3',
          700: '#f5e596',
          800: '#f3e189',
          900: '#f2de7c',
        },
        info: colors.blue['400'],
        success: colors.green['400'],
        warning: colors.orange['400'],
        danger: colors.red['400'],
      },
    },
  },
  variants: {
    extend: {
      opacity: ['disabled'],
    },
  },
  plugins: [require('@tailwindcss/forms')],
}
