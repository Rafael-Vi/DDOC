module.exports = {
  content: ["./src/**/*.{html,js,php}"],
  theme: {
    extend: {
      colors: {
        // your other colors...
      },
    },
    daisyui: {
      themes: [
        {
          ddark: {
            "primary": '#090c08',
            "secondary": '#f4ac32', // hunyadi_yellow
            "accent": '#682d63', // palatinate
            "success": '#72a98f', // cambridge_blue
            "warning": '#e84855', // red_(crayola)
            "neutral": '#080306',
            "base-100": '#18242a',
            "info": '#00e0fd',
            "error": '#ff7e7e',
          },
          dlight: {
            "primary": '#F2F4FF', // Replaced with your desired color
            "secondary": '#f4ac32', // hunyadi_yellow
            "accent": '#682d63', // palatinate
            "success": '#72a98f', // cambridge_blue
            "warning": '#e84855', // red_(crayola)
            "neutral": '#f6f6f6',
            "base-100": '#ffffff',
            "info": '#00e0fd',
            "error": '#ff7e7e',
          },
        },
      ],
    },
  },
  plugins: [
    require("daisyui"),
  ],
};