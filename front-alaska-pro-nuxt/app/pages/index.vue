<script setup>
import { onMounted, ref } from 'vue'  // Importar ref
import { useRouter } from 'vue-router'
import axios from 'axios'
import { push } from 'notivue'

const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)
const router = useRouter()

const submitLogin = async () => {
    loading.value = true
    error.value = ''

    const failedAttemptsKey = 'failed_login_attempts'
    const lockoutKey = 'lockout_until'
    const attemptsWindowKey = 'attempts_window'

    try {
        // Verificar si el usuario está bloqueado
        const lockoutUntil = localStorage.getItem(lockoutKey)
        if (lockoutUntil && new Date() < new Date(lockoutUntil)) {
            const remainingTime = Math.ceil((new Date(lockoutUntil) - new Date()) / 1000)
            error.value = `Demasiados intentos fallidos. Intenta nuevamente en ${remainingTime} segundos.`
            push.error({ title: 'Bloqueado', message: error.value, duration: 3000 })
            return
        }

        // Verificar si se excedieron los intentos por minuto
        const attemptsWindow = JSON.parse(localStorage.getItem(attemptsWindowKey)) || []
        const currentTime = new Date().getTime()
        const oneMinuteAgo = currentTime - 60 * 1000

        // Filtrar intentos dentro del último minuto
        const recentAttempts = attemptsWindow.filter(attempt => attempt > oneMinuteAgo)
        if (recentAttempts.length >= 5) {
            error.value = 'Demasiados intentos en un minuto. Intenta nuevamente más tarde.'
            push.error({ title: 'Límite alcanzado', message: error.value, duration: 3000 })
            return
        }

        const { data } = await axios.post(URL_BASE_API + '/v1/auth/login', {
            email: email.value,
            password: password.value
        })

        // Restablecer intentos fallidos y ventana de intentos en caso de éxito
        localStorage.removeItem(failedAttemptsKey)
        localStorage.removeItem(lockoutKey)
        localStorage.removeItem(attemptsWindowKey)

        // Guardar token en localStorage
        localStorage.setItem('token', data.access_token)
        localStorage.setItem('token_type', data.token_type)

        // Redirigir al dashboard
        router.push('/home')
    } catch (err) {
        // Incrementar intentos fallidos
        const failedAttempts = parseInt(localStorage.getItem(failedAttemptsKey) || '0', 10) + 1
        localStorage.setItem(failedAttemptsKey, failedAttempts)

        // Registrar intento en la ventana de intentos
        const attemptsWindow = JSON.parse(localStorage.getItem(attemptsWindowKey)) || []
        attemptsWindow.push(new Date().getTime())
        localStorage.setItem(attemptsWindowKey, JSON.stringify(attemptsWindow))

        if (failedAttempts >= 3) {
            // Bloquear usuario por 1 minuto
            const lockoutDuration = 60 * 1000 // 1 minuto en milisegundos
            const lockoutUntilTime = new Date(new Date().getTime() + lockoutDuration)
            localStorage.setItem(lockoutKey, lockoutUntilTime)
            error.value = 'Demasiados intentos fallidos. Intenta nuevamente en 60 segundos.'
            push.error({ title: 'Bloqueado', message: error.value, duration: 3000 })
        } else {
            error.value = err.response?.data?.message || 'Intenta nuevamente'
            push.error({ title: 'Upps!', message: error.value, duration: 3000 })
        }
    } finally {
        loading.value = false
    }
}



definePageMeta({ layout: false })


onMounted(() => {
  // Crear un <script> dinámicamente para Particles.js
  const script = document.createElement('script')
  script.src = 'https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js'
  script.onload = () => {
    // Inicializar partículas después de que se cargue el script
    if (window.particlesJS) {
      particlesJS("particles-js", {
        particles: {
          number: { value: 60, density: { enable: true, value_area: 800 } },
          color: { value: "#00E0C7" },
          shape: { type: "circle" },
          opacity: { value: 0.4 },
          size: { value: 5, random: true },
          move: { enable: true, speed: 5 }
        }
      })
    }
    loading.value = false // Ocultar loading cuando termine
  }
  document.body.appendChild(script)


})



</script>
<template>

<LoadingAnimation v-if="loading" />

  <div v-cloak class="container-login">
    <!-- Lado izquierdo -->
   <section class="left">
  <!-- Fondo con partículas -->
  <div id="particles-js"></div>

  <!-- Contenido sobre las partículas -->
  <div class="left-content">
    <h1 class="title is-3 has-text-white">Sistema de Facturación</h1>
    <p class="subtitle is-6 has-text-light mb-5">
      Generación masiva de facturas para Superservicios Colombia
    </p>

    <!-- Lista de beneficios -->
    <ul class="benefits">
      <li>
        <i class="fa-solid fa-file-invoice-dollar"></i>
        Facturación electrónica integrada con la DIAN
      </li>
      <li>
        <i class="fa-solid fa-users"></i>
        Gestión centralizada de clientes y contratos
      </li>
      <li>
        <i class="fa-solid fa-chart-line"></i>
        Reportes y métricas en tiempo real
      </li>
      <li>
        <i class="fa-solid fa-lock"></i>
        Seguridad avanzada con autenticación
      </li>
      <li>
        <i class="fa-solid fa-headset"></i>
        Soporte 24/7 para tu operación
      </li>
    </ul>
  </div>
</section>


    <!-- Lado derecho -->
<section class="right">
  <div class="card-login box">
    <!-- Logo -->
    <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 1rem;">
      <img style="max-width: 70%; height: auto;" src="/images/logo.png" alt="Logo Sistema">
    </div>

    <!-- Formulario -->
    <div class="card-body">
      <form @submit.prevent="submitLogin">
        <div class="material-field">
          <label class="label is-small">Correo electrónico</label>
          <div class="control has-icons-left">
            <input
              class="input"
              type="email"
              v-model="email"
              placeholder="usuario@empresa.com"
              required
            >
            <span class="icon is-small is-left">
              <i class="material-icons">email</i>
            </span>
          </div>
        </div>

        <div class="material-field">
          <label class="label is-small">Contraseña</label>
          <div class="control has-icons-left">
            <input
              class="input"
              v-model="password"
              type="password"
              placeholder="********"
              required
            >
            <span class="icon is-small is-left">
              <i class="material-icons">lock</i>
            </span>
          </div>
        </div>

        <div class="login-actions" style="margin-top: 0.5rem; display: flex; justify-content: flex-end;">
          <a class="is-size-7" href="#">¿Olvidaste tu contraseña?</a>
        </div>

        <div style="margin-top: 1.5rem;">
          <button
            type="submit"
            :disabled="loading"
            class="button btn-primary is-fullwidth"
          >
            Acceder
          </button>
        </div>

        <div class="divider"></div>

        <p
          v-if="error"
          class="has-text-centered is-size-7 error"
          style="margin-top: .9rem; color: #dc2626;"
        >
          {{ error }}
        </p>
      </form>
    </div>

    <!-- Footer dentro de la tarjeta -->
    <div style="text-align: center; font-size: .75rem; color: #64748b;">
      <span>Powered by</span>
      <a  href="https://agenciacristal.com" target="_blank"><strong style="color: #00E0C7;"> AGENCIA CRISTAL</strong></a>
    </div>
  </div>
</section>

  </div>

</template>
<style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Poppins', sans-serif;
      overflow: hidden;
    }
.error {
  color: red;
}
.left {
  position: relative;
  width: 100%;
  min-height: 100vh;
  color: #fff;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
}

#particles-js {
    position: absolute;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #00e0c7, #000428),
                            url('https://www.epm.com.co/content/dam/epm/clientes-y-usuarios/servicio-al-cliente/t%C3%BA-factura/nueva-factura-epm/img-2-popup.jpg') no-repeat center center/cover;
    opacity: 0.8; /* Makes the background image very transparent */
    top: 0;
    left: 0;
    z-index: 1;
}

.left-content {
  position: relative;
  z-index: 2;
  max-width: 500px;
  text-align: left;
}

.left-content h1 {
  font-weight: 700;
  margin-bottom: 1rem;
}

.left-content p {
  font-size: 1.1rem;
  margin-bottom: 2rem;
}

.benefits {
  list-style: none;
  padding: 0;
  margin: 0;
}

.benefits li {
  margin-bottom: 1rem;
  font-size: 1rem;
  display: flex;
  align-items: center;
  gap: 10px;
}

.benefits i {
  color: #00e0c7;
  font-size: 1.2rem;
}

    .container-login {
      display: flex;
      height: 100vh;
    }

    /* Lado izquierdo */
    .left {
      flex: 1;
      position: relative;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      overflow: hidden;
    }

    .left::before {
      content: "";
      position: absolute;
      inset: 0;
      background: url("https://www.epm.com.co/content/dam/epm/clientes-y-usuarios/servicio-al-cliente/t%C3%BA-factura/nueva-factura-epm/img-2-popup.jpg") no-repeat center center/cover;
      filter: brightness(0.45);
      z-index: 1;
    }

    #particles-js {
      position: absolute;
      inset: 0;
      z-index: 2;
    }

    .left-content {
      position: relative;
      z-index: 3;
      padding: 2rem;
    }

    .left-content h1 {
      font-size: 2rem;
      font-weight: 600;
    }

    .left-content p {
      margin-top: 0.8rem;
      font-size: 1rem;
      opacity: 0.9;
    }

    /* Lado derecho */
    .right {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f9fafb;
      padding: 2rem;
      border-left: solid 3px #00e0c7;
    }

    .card-login {
      width: 100%;
      max-width: 420px;
      border-radius: 14px;
      box-shadow: 0 8px 22px rgba(0,0,0,0.08);
      overflow: hidden;
      border: none;
      background: white;
    }

    .card-header {
      background: transparent;
      padding: 1.5rem;
      border-bottom: 1px solid #f0f0f0;
    }

    .card-header h2 {
      margin: 0;
      font-weight: 600;
      color: #003d4d;
    }

    .card-header p {
      margin-top: .25rem;
      font-size: 0.85rem;
      color: #6b7280;
    }

    .card-body {
      padding: 2rem;
    }

    /* Campos estilo material */
    .material-field {
      margin-bottom: 1.5rem;
    }

    .material-field .control input {
      border: none;
      border-bottom: 2px solid #d1d5db;
      border-radius: 0;
      box-shadow: none;
      padding: 0.85rem 0.5rem 0.5rem 2.2rem;
      background: transparent;
      font-size: 0.95rem;
      transition: border-color 0.3s;
    }

    .material-field .control input:focus {
      border-bottom-color: #00E0C7;
      box-shadow: none;
      outline: none;
    }

    .material-field .icon {
      left: 0.4rem;
      top: 0.65rem;
      color: #94a3b8;
    }

    /* Acciones login */
    .login-actions {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: .5rem;
      font-size: 0.85rem;
    }

    .login-actions a {
      color: #00E0C7;
      text-decoration: none;
    }

    /* Botón */
    .btn-primary {
      background: #00E0C7;
      color: white;
      border: none;
      border-radius: 8px;
      padding: 0.85rem 1rem;
      font-weight: 600;
      font-size: 0.95rem;
      transition: 0.3s ease;
    }

    .btn-primary:hover {
      background: #00c0a9;
    }

    /* Divider */
    .divider {
      height: 1px;
      background: #f0f0f0;
      margin: 1.5rem 0;
    }

    /* Responsive */
    @media (max-width: 900px) {
      .container-login {
        flex-direction: column;
      }
      .left {
        height: 40vh;
      }
      .right {
        height: 60vh;
      }
    }
  </style>
