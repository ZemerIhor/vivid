module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './vendor/lunarphp/stripe-payments/resources/views/**/*.blade.php',
    ],
    safelist: [
        'grid-cols-1', 'grid-cols-2', 'grid-cols-3',
        'grid-cols-4', 'grid-cols-5', 'grid-cols-6',
        'grid-cols-7', 'grid-cols-8', 'grid-cols-9',
        'grid-cols-10', 'grid-cols-11', 'grid-cols-12',
        'w-[80%]',
        'w-[98vw]',
        'mx-[1vw]',
        'mx-[2%]',
        'shrink-0',
        'min-w-[98vw]',
        'min-w-[80vw]',
        'md:flex-row',
  'md:flex-row-reverse'
    ],
    theme: {
        extend: {},
    },
    plugins: [require('@tailwindcss/forms')],
};
