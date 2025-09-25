<script setup>
definePageMeta({
    middleware:'auth'
})

import { onMounted, ref } from 'vue'  // Importar ref
import { useRouter } from 'vue-router'
import axios from 'axios'
import { navigateTo } from 'nuxt/app'
import { URL_BASE_API } from '~/utils/constants'
import { push } from 'notivue'


const error = ref('')
const facturasList = ref([])
const loading = ref(true)
const router = useRouter()


onMounted(() => {
  // Crear un <script> dinámicamente para Particles.js
  const script = document.createElement('script')
  script.src = 'https://cdn.jsdelivr.net/npm/chart.js'
  script.onload = () => {
    const months = ['Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre'];
const sidebar = document.querySelector('body');
document.getElementById('sidebarToggle').addEventListener('click', () => {
  sidebar.classList.toggle('sidebar-collapsed');
});

// Theme toggle
document.getElementById('themeToggle').addEventListener('click', () => {
  if (sidebar.classList.contains('dark')) {
    sidebar.classList.remove('dark');
    sidebar.classList.add('light');
  } else {
    sidebar.classList.remove('light');
    sidebar.classList.add('dark');
  }
});

// Search filter button (placeholder)
document.getElementById('filterBtn').addEventListener('click', () => {
  alert('Filtrando registros...');
});
  // Pagos
  new Chart(document.getElementById('paymentsChart'), {
    type: 'bar',
    data: {
      labels: months,
      datasets: [{
        label: 'Pagos ($)',
        data: [1200000, 1500000, 1100000, 1800000, 1300000, 1600000],
        backgroundColor: '#00E0C7',
        borderRadius: 6
      }]
    },
    options: {
      responsive:true,
      plugins:{ legend:{display:false} },
      scales:{
        y:{ beginAtZero:true, ticks:{ callback: value => '$' + value.toLocaleString() } },
        x:{ grid:{ display:false } }
      }
    }
  });

  // Consumos
  new Chart(document.getElementById('consumptionChart'), {
    type: 'line',
    data: {
      labels: months,
      datasets: [{
        label: 'Consumo (m3)',
        data: [320, 280, 350, 300, 290, 310],
        borderColor: '#007BFF',
        borderWidth: 3,
        tension: 0.3,
        fill: false,
        pointBackgroundColor: '#fff',
        pointBorderColor: '#007BFF',
        pointRadius: 5
      }]
    },
    options: {
      responsive:true,
      plugins:{ legend:{display:false} },
      scales:{
        y:{ beginAtZero:true, ticks:{ callback: value => value + '  m3' } },
        x:{ grid:{ display:false } }
      }
    }
  });
  }
  // Sidebar toggle

  document.body.appendChild(script)

  getFacturas(URL_BASE_API+'/v1/facturas')
})

const getFacturas = async (url) => {

  loading.value = true
  error.value = ''

  try {
    const token = localStorage.getItem('token')
    const token_type = localStorage.getItem('token_type')
    const { data } = await axios.get(url, {
      headers: {
        Authorization: `${token_type} ${token}`
      }
    })
    facturasList.value = data
    console.log(data)

    // Redirigir al dashboard
    //router.push('/home')
  } catch (err) {
    console.log(err)
    if(err.status===401){
        push.warning({ title: 'Upps!', message: 'Su sesión expiró' })

        navigateTo("/")
    }
    error.value = err.response?.data?.message || 'Acción no permitida'
  } finally {
    loading.value = false
  }
}
</script>
<template>
    <LoadingAnimation v-if="loading" />





  <!-- Filters -->
  <section class="filters">
    <div style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end">
      <div style="flex:1;min-width:180px">
        <label class="is-size-7 has-text-weight-semibold">Cliente</label>
        <input id="fCliente" class="input" placeholder="Ej: Empresa ABC">
      </div>
      <div style="width:200px;min-width:160px">
        <label class="is-size-7 has-text-weight-semibold">Contrato</label>
        <input id="fContrato" class="input" placeholder="Ej: CT-2025-001">
      </div>
      <div style="width:160px">
        <label class="is-size-7 has-text-weight-semibold">Mes</label>
        <div class="select is-fullwidth"><select id="fMes"><option value="">Todos</option><option>Septiembre</option><option>Agosto</option><option>Julio</option></select></div>
      </div>
      <div style="width:160px">
        <label class="is-size-7 has-text-weight-semibold">Estado</label>
        <div class="select is-fullwidth">
          <select id="fEstado">
            <option value="">Todos</option>
            <option value="pagada">Pagada</option>
            <option value="pendiente">Pendiente</option>
            <option value="vencida">Vencida</option>
          </select>
        </div>
      </div>
      <div style="flex:none">
        <button class="button is-primary" id="filterBtn">
          <i class="fa-solid fa-filter"></i> Filtrar
        </button>
      </div>
    </div>
  </section>

  <!-- Table -->
  <section class="table-card">
    <table class="table is-fullwidth is-hoverable">
      <thead>
        <tr>
          <th>Factura</th>
          <th>Cliente</th>
          <th>Contrato</th>
          <th>Fecha</th>
          <th>Valor</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="f in facturasList.data">
          <td>F-{{f.id}}</td>
          <td>{{f.cliente}}</td>
          <td>CT-{{f.contrato}}</td>
          <td>{{f.fecha_generada}}</td>
          <td>{{ new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP' }).format(f.valor) }}</td>
          <td>
            <span
              class="tag-status "
              :class="{
                'tag-pagada': f.estado === 'Pagada',
                'tag-pendiente': f.estado === 'Pendiente',
                'tag-vencida': f.estado === 'Vencida'
              }"
            >
              {{f.estado}}
            </span>
          </td>
          <td>
            <button class="button is-small is-info"><i class="fa-solid fa-eye"></i></button>
            <button class="button is-small is-success"><i class="fa-solid fa-download"></i></button>
          </td>
        </tr>

      </tbody>
    </table>
  <!-- Paginación UI -->
  <nav class="pagination-material" aria-label="pagination">
    <button class="prev" @click="getFacturas(facturasList.first_page_url)" :disabled="facturasList.current_page<=1">Primera</button>
    <button class="prev" @click="getFacturas(facturasList.prev_page_url)"  :disabled="facturasList.prev_page_url==null"><i class="fa-solid fa-chevron-left"></i> Anterior</button>
    <div class="pages">
      <button @click="getFacturas(facturasList.path+'?page='+(facturasList.current_page - 1))" v-if="facturasList.current_page - 1 > 0">{{ facturasList.current_page - 1 }}</button>
      <button class="is-current">{{ facturasList.current_page }}</button>
      <button @click="getFacturas(facturasList.path+'?page='+(facturasList.current_page + 1))" v-if="facturasList.current_page + 1 <= facturasList.last_page">{{ facturasList.current_page + 1 }}</button>
    </div>
    <button class="next" @click="getFacturas(facturasList.next_page_url)"  :disabled="facturasList.next_page_url==null">Siguiente <i class="fa-solid fa-chevron-right"></i></button>
    <button class="prev" @click="getFacturas(facturasList.last_page_url)" :disabled="facturasList.current_page>=facturasList.last_page">Última</button>
  </nav>
  <p class="pagination-material">Página: {{ facturasList.from }} a la {{ facturasList.to }} de {{ facturasList.total }} registros</p>
</section>
</template>
