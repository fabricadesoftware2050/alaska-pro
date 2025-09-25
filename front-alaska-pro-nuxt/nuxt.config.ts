import { defineNuxtConfig } from "nuxt/config";

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({


    compatibilityDate: '2025-07-15',
    devtools: { enabled: true },
    css: ['~/assets/css/main.css'],

  app: {
        pageTransition: { name: 'page', mode: 'out-in' }
,
    head: {
      title: 'Alaska Pro - Servicios Públicos Domiciliarios',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' }
      ],
      link: [
        { rel: 'icon', type: 'image/x-icon', href: 'images/icono.png' },
        // Bulma
        { rel: 'stylesheet', href: 'https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css' },
        // Material Icons
        { rel: 'stylesheet', href: 'https://fonts.googleapis.com/icon?family=Material+Icons' },
        { rel: 'stylesheet', href: 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css' },
        // Google Fonts Poppins
        { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap' }
      ],
      script: [
        // Particles.js
        //{ src: 'https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js', defer: true }
      ],
    },


  },
  modules: ['notivue/nuxt'],
  notivue: {
    position: 'top-right',
    limit: 4,
    enqueue: true,
    avoidDuplicates: true,
    notifications: {
      global: {
        duration: 4000
      }
    }
  },
  css: [
    'notivue/notification.css', // Only needed if using built-in <Notification />
    'notivue/animations.css' // Only needed if using default animations
  ],

})
