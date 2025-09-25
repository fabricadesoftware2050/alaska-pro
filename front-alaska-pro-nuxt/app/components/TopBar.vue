<script setup>
import axios from 'axios'
import { jwtDecode } from 'jwt-decode'
import { push } from 'notivue'
import { navigateTo } from 'nuxt/app'
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
const router = useRouter()

const user = ref({})


const token = ref()
const loading = ref(false)
const error = ref('')
const token_type = ref()

const logOut = async () => {
    loading.value = true
    error.value = ''

    try {


        const response = await axios.get(URL_BASE_API + '/v1/auth/logout', {
            headers: {
                Authorization: `${token_type.value} ${token.value}`
            }
        })
        if(response.status === 200){
            push.success({ title: 'Cierre seguro', message: 'Sesión cerrada de forma segura', duration: 3000 })
        }


    } catch (err) {

        error.value = err.response?.data?.message || 'Intenta nuevamente'
        push.error({ title: 'Upps!', message: error.value, duration: 3000 })

    } finally {
        router.push('/')
        localStorage.clear()
        loading.value = false
    }
}

onMounted(() => {
  if (import.meta.client) {
    token.value = localStorage.getItem('token')
    token_type.value = localStorage.getItem('token_type')

    user.value = jwtDecode(token.value) ?? {}
  }else{
    push.warning({ title: 'Upps!', message: 'Su sesión expiró' })
    localStorage.clear()
    navigateTo('/')
  }


})
</script>

<template>
        <LoadingAnimation v-if="loading" />
    <header class="topbar" role="banner">
  <div class="brand">
    <button id="sidebarToggle" class="button is-white icon-btn" aria-label="Toggle menu">
      <i class="fa-solid fa-bars"></i>
    </button>
    <div style="display:flex;flex-direction:column;">
      <div style="font-weight:600;color:var(--secondary)"><strong>MÓDULO:</strong> SERVICIOS PÚBLICOS DOMICILIARIOS</div>
      <div style="font-size:.78rem;color:var(--muted); text-transform: uppercase;">EMPRESA: <strong>AGUAS DE RÍO QUITO</strong> | Periodo: <strong>Septiembre 2025</strong></div>
    </div>
  </div>

  <div class="controls">


    <div style="display:flex;gap:.5rem;align-items:center">

      <div class="user-compact" style="display:flex;align-items:center;gap:.6rem;margin-left:.5rem">
        <div style="text-align:right">
          <div style="font-weight:600">{{ user.name??'??' }}</div>
          <div style="font-size:.78rem;color:var(--muted)">Cargo: {{user.company_position}} | Rol: {{ user.role??'??' }}</div>
        </div>
      <button id="themeToggle" class="button is-light" title="Cambiar tema"><i class="fa-solid fa-moon"></i></button>
        <div @click="logOut" class="avatar" style="cursor: pointer;;width:44px;height:44px;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#ff0000;font-weight:700">
           <button title="Cerrar sesión" id="themeToggle" class="button is-danger" ><i class="fa-solid fa-sign-out"></i></button>
        </div>
      </div>
    </div>
  </div>
</header>
</template>
