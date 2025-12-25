/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/resources/views/**/*.blade.php",
    "./src/resources/js/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        main: '#8A7458',
        main2: '#46392A',
        base_color: '#FFFAF5',
        text: '#201200',
        accent: '#E4C9A8',
        form: '#FFFFFF',
        placeholder: '#666666',
        favorite: '#4F4232',
        star: '#F6D264',
        notification: '#FF4D4D',
        notification2: '#F3F0ED',
      },
    },
  },
  plugins: [],
}
