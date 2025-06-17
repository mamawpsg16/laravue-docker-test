// tailwind.config.js
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
    // Remove this line: "./src/assets/css/**/*.css", 
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}