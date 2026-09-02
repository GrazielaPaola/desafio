import { defineConfig } from '#q-app'

export default defineConfig(() => ({
  boot: [],

  extras: ['roboto-font', 'material-icons'],

  build: {
    vueRouterMode: 'history',
    vitePlugins: [
      [
        'vite-plugin-checker',
        {
          eslint: {
            lintCommand: 'eslint -c ./eslint.config.js "./src*/**/*.{js,mjs,cjs,vue}"',
            useFlatConfig: true,
          },
        },
        { server: false },
      ],
    ],
  },

  devServer: {
    port: 9000,
    open: false,
  },

  framework: {
    lang: 'pt-BR',
    config: {
      notify: {
        position: 'top-right',
        timeout: 3000,
      },
    },
    plugins: ['Notify', 'Dialog'],
  },

  animations: [],
}))
