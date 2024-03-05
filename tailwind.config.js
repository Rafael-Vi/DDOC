module.exports = {
    theme: {
        extend: {
            colors: {
                Dark: {
                    "primary": '#b900ff',
                    "secondary": '#f46800',
                    "accent": '#007cff',
                    "neutral": '#080306',
                    "base-100": '#18242a',
                    "info": '#00e0fd',
                    "success": '#00c400',
                    "warning": '#e90000',
                    "error": '#ff7e7e',
                },
            },
        },
    },
    plugins: [
        require("daisyui"),
    ],
};