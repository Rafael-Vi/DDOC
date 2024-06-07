module.exports = {
  content: ["./src/**/*.{html,js,php}"],
  theme: {
    extend: {
      colors: {
        'primary': '#242038',
        'secondary': '#fe5f55',
        'accent': '#d63af9',
        'success': '#f4ac45',
        'warning': '#fe5f55',
        'neutral': '#242038',
        'base-100': '#07060b',
        'info': '#d63af9',
        'error': '#fe5f55',
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