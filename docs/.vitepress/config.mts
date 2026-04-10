import { defineConfig } from 'vitepress'

export default defineConfig({
  base: '/laravel-lang-sync-inertia/',
  title: 'Laravel Lang Sync Inertia',
  description: 'Bridge Laravel translation files to Inertia.js apps with first-class Vue and React support.',
  head: [
    ['meta', { name: 'author', content: 'Er Amit Gupta' }],
    ['meta', { name: 'keywords', content: 'Laravel, Inertia.js, Vue, React, TypeScript, translations, localization, i18n' }],
    ['meta', { name: 'robots', content: 'index, follow' }],
    ['meta', { property: 'og:type', content: 'website' }],
    ['meta', { property: 'og:title', content: 'Laravel Lang Sync Inertia' }],
    ['meta', { property: 'og:description', content: 'Bridge Laravel translation files to Inertia.js apps with first-class Vue and React support.' }],
    ['meta', { property: 'og:site_name', content: 'Laravel Lang Sync Inertia' }],
    ['meta', { property: 'og:image', content: 'https://avatars.githubusercontent.com/u/72160684?v=4&size=256' }],
    ['meta', { name: 'twitter:card', content: 'summary_large_image' }],
    ['meta', { name: 'twitter:title', content: 'Laravel Lang Sync Inertia' }],
    ['meta', { name: 'twitter:description', content: 'Bridge Laravel translation files to Inertia.js apps with first-class Vue and React support.' }],
    ['meta', { name: 'twitter:image', content: 'https://avatars.githubusercontent.com/u/72160684?v=4&size=256' }],
    [
      'link',
      {
        rel: 'icon',
        href: 'https://avatars.githubusercontent.com/u/72160684?v=4&size=64'
      }
    ]
  ],
  cleanUrls: true,
  lastUpdated: true,
  themeConfig: {
    sidebar: [
      { text: 'Overview', link: '/' },
      { text: 'Introduction', link: '/introduction' },
      { text: 'Installation', link: '/installation' },
      { text: 'Config', link: '/config' },
      {
        text: 'Framework Guides',
        items: [
          { text: 'Laravel', link: '/laravel' },
          { text: 'Vue', link: '/vue' },
          { text: 'React', link: '/react' }
        ]
      },
      {
        text: 'Reference',
        items: [
          { text: 'API Helpers', link: '/api' },
          { text: 'Export to JSON', link: '/exporting' },
          { text: 'Contributing', link: '/contributing' }
        ]
      }
    ],
    search: {
      provider: 'local'
    },
    socialLinks: [
      { icon: 'github', link: 'https://github.com/eramitgupta/laravel-lang-sync-inertia' }
    ],
    footer: {
      message: 'MIT License. Copyright Er Amit Gupta',
    },
    outline: {
      level: [2, 3],
      label: 'On this page'
    },
    docFooter: {
      prev: 'Previous page',
      next: 'Next page'
    }
  }
})
