/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.php",
        "./recourses/views/**/*.php",
        "./resources/views/product/**/*.php",
        "./resources/views/components/**/*.php",
        "./resources/views/components/product/**/*.php",
        "./resources/views/components/sidenav/**/*.php",
        "./resources/views/components/category/**/*.php",
        "./resources/views/components/header/**/*.php",
        "./resources/views/components/product/buttons/**/*.php",
        "./resources/views/components/product/categories/**/*.php",
        "./resources/views/components/product/create/**/*.php",
        "./resources/views/components/product/popup/**/*.php",
        "./resources/views/property/**/*.php"
    ],
    theme: {
        screens: {
            laptop: "1280px",
            normal: "1920px",
            big: "2560px",
            enormous: "3840px",
        },
        extend: {
            screens: {
                laptop: "1280px",
                normal: "1920px",
                big: "2560px",
                enormous: "3840px",
            },
        },
    },
    safelist: [
        'w-[6.31rem]',
        'w-[78.85rem]',
        'w-[33.8rem]',
        'w-[5.688rem]',
        'w-[9.31rem]',
        'w-[10.5rem]',
        'w-[7.12rem]'
    ],
    plugins: [],
};
