module.exports = {
    theme: {
        extend: {
            colors: {
                'gold-100': '#fbf1de',
                'gold-200': '#efd69d',
                'gold-300': '#e7c97c',
                'gold-400': '#debc5b',
                'gold-500': '#d4af37',
                'gold-600': '#ae8f30',
                'gold-700': '#897129',
                'gold-800': '#655421',
                'gold-900': '#251f11'
            },
            width: {
                '1/7': '14.2857143%',
                '2/7': '28.5714286%',
                '3/7': '42.8571429%',
                '4/7': '57.1428571%',
                '5/7': '71.4285714%',
                '6/7': '85.7142857%'
            }
        }
    },
    variants: {
        backgroundColor: ['responsive', 'odd', 'even', 'hover', 'focus'],
        borderWidth: ['responsive', 'last', 'hover', 'focus']
    },
    plugins: []
};
