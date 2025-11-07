/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./index.html", "./src/**/*.{vue,ts,tsx}"],
  theme: {
    extend: {},
  },
  plugins: [],
};
 // tailwind.config.js  (ja tev ir .ts – saturs tas pats)
export default {
  content: ['./index.html','./src/**/*.{vue,ts,js}'],
  theme: {
    extend: {
      colors: {
        mpp: {
          bg: '#F6F4EF',
          dark: '#2B2A28',
          primary: '#4F7942',
          secondary: '#C5A572',
          accent: '#E4CFA3',
          error: '#E24A4A',
        },
      },
      fontFamily: {
        display: ['"Josefin Sans"', 'ui-sans-serif', 'system-ui'],
        body: ['"Josefin Sans"', 'ui-sans-serif', 'system-ui'],
      },
      boxShadow: { soft: '0 10px 24px rgba(0,0,0,0.08)' },
      borderRadius: { 'xl2': '1.25rem' },
    },
  },
  plugins: [],
}
