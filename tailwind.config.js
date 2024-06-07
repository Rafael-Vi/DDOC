module.exports = {
  content: ["./src/**/*.{html,js,php}"],
  theme: {
    extend: {
      colors: {
        'dark_purple': {
          DEFAULT: '#242038',
          100: '#07060b',
          200: '#0e0d16',
          300: '#151321',
          400: '#1c192c',
          500: '#242038',
          600: '#463f6d',
          700: '#695ea2',
          800: '#9b93c1',
          900: '#cdc9e0'
        },
        'bittersweet': {
          DEFAULT: '#fe5f55',
          100: '#430500',
          200: '#860a01',
          300: '#c90e01',
          400: '#fe1f0f',
          500: '#fe5f55',
          600: '#fe7e75',
          700: '#fe9e97',
          800: '#ffbfba',
          900: '#ffdfdc'
        },
        'white': {
          DEFAULT: '#fefffe',
          100: '#006600',
          200: '#00cc00',
          300: '#33ff33',
          400: '#99ff99',
          500: '#fefffe',
          600: '#ffffff',
          700: '#ffffff',
          800: '#ffffff',
          900: '#ffffff'
        },
        'phlox': {
          DEFAULT: '#d63af9',
          100: '#31023b',
          200: '#620477',
          300: '#9206b2',
          400: '#c307ed',
          500: '#d63af9',
          600: '#de61fa',
          700: '#e688fb',
          800: '#eeb0fd',
          900: '#f7d7fe'
        },
        'hunyadi_yellow': {
          DEFAULT: '#f4ac45',
          100: '#3b2403',
          200: '#764707',
          300: '#b06b0a',
          400: '#eb8f0e',
          500: '#f4ac45',
          600: '#f6bb69',
          700: '#f8cc8e',
          800: '#fbddb4',
          900: '#fdeed9'
        }
      },
    },
    daisyui: {
      themes: [
        {
          ddark: {
            "primary": '#242038',
            "secondary": '#fe5f55',
            "accent": '#d63af9',
            "success": '#f4ac45',
            "warning": '#fe5f55',
            "neutral": '#242038',
            "base-100": '#07060b',
            "info": '#d63af9',
            "error": '#fe5f55',
          },
          dlight: {
            "primary": '#fefffe',
            "secondary": '#fe5f55',
            "accent": '#d63af9',
            "success": '#f4ac45',
            "warning": '#fe5f55',
            "neutral": '#fefffe',
            "base-100": '#ffffff',
            "info": '#d63af9',
            "error": '#fe5f55',
          },
        },
      ],
    },
  },
  plugins: [
    require("daisyui"),
  ],
};