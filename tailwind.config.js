/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./public/assets/js/product/**/*.js",
        "./resources/**/*.php",
        "./recourses/views/**/*.php",
        "./resources/views/product/**/*.php",
        "./resources/views/components/**/*.php",
        "./resources/views/components/product/**/*.php",
        "./resources/views/components/sidenav/**/*.php",
        "./resources/views/components/category/**/*.php",
        "./resources/views/components/header/**/*.php",
        "./resources/views/components/product/buttons/**/*.php",
        "./resources/views/components/product/categories/*.php",
        "./resources/views/components/product/create/**/*.php",
        "./resources/views/components/product/create/categories/*.php",
        "./resources/views/components/product/create/stepOne/*.php",
        "./resources/views/components/product/create/stepTwo/*.php",
        "./resources/views/components/product/create/stepThree/*.php",
        "./resources/views/components/product/create/stepFour/*.php",
        "./resources/views/components/product/create/stepFive/*.php",
        "./resources/views/components/product/create/stepSix/*.php",
        "./resources/views/components/product/popup/**/*.php",
        "./resources/views/property/**/*.php",
        "./resources/views/vendor/pagination/**/*.php",
        "./resources/views/vendor/**/*.php",
    ],
    theme: {
        screens: {
            basic: "1280px",
            hd: "1920px",
            uhd: "2560px",
            shd: "3840px",
        },
        extend: {
            screens: {
                basic: "1280px",
                hd: "1920px",
                uhd: "2560px",
                shd: "3840px",
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
