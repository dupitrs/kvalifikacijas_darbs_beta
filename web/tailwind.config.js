/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html", "./src/**/*.{vue,js,ts,jsx,tsx}"],
  theme: {
    extend: {
      fontFamily: {
        sans: ["'Plus Jakarta Sans'", "ui-sans-serif", "system-ui"],
      },
      colors: {
        mpp: {
          red: "#C71616",
          orange: "#F66B1A",
          teal: "#08A398",
          mint: "#B3EFEB",
          green: "#ADE9A1",
          ink: "#0F172A",     // tumšais teksts
          paper: "#F6FBFA",   // fons
        },
      },
      boxShadow: {
        soft: "0 10px 30px rgba(15, 23, 42, 0.10)",
      },
    },
  },
  plugins: [],
};
