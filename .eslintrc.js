module.exports = {
  env: {
    browser: true,
    es2021: true,
  },
  extends: ['eslint:recommended', 'plugin:vue/essential'],
  parserOptions: {
    ecmaVersion: 12,
    sourceType: 'module',
  },
  plugins: ['vue'],
  rules: {},
  "globals": {
    "toast": true,
    "Swal": true,
    "Fire": true,
    "$": true,
    "stripe_key": true,
  }
}
