<script setup>
definePageMeta({
    middleware:['auth','rate']
})

import { onMounted, ref } from 'vue'  // Importar ref
import { useRouter } from 'vue-router'
import axios from 'axios'
import { URL_BASE_API } from '~/utils/constants'
import { navigateTo } from 'nuxt/app'
import { push } from 'notivue'
import PaginateListComponent from '~/components/PaginateListComponent.vue'


const error = ref('')
const facturasList = ref([])
const loading = ref(false)
const router = useRouter()
const rateLimit=ref(false);


const token = ref()
const token_type = ref()


onMounted(() => {
token.value = localStorage.getItem('token')
token_type.value = localStorage.getItem('token_type')

    rateLimit.value=Boolean(localStorage.getItem('rateLimit'));
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

    const { data } = await axios.get(url, {
      headers: {
        Authorization: `${token_type.value} ${token.value}`
      }
    })
    facturasList.value = data
    console.log(data)

    // Redirigir al dashboard
    //router.push('/home')
  } catch (err) {
    console.log(err)
    if(err.status===401){
        push.warning({ title: 'Upps!', message:  'Su sesión expiró',duration:3000 })

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
     <!-- Metrics -->
  <section  class="metrics" aria-label="Indicadores principales">
    <div class="metric">
      <div style="display:flex;justify-content:space-between;align-items:center">
        <div>
          <div class="is-size-7 has-text-grey">Facturas generadas</div>
          <div class="kpi">120</div>
        </div>
        <div style="font-size:1.8rem;color:var(--primary)"><i class="fa-solid fa-file-invoice"></i></div>
      </div>
    </div>

    <div class="metric">
      <div style="display:flex;justify-content:space-between;align-items:center">
        <div>
          <div class="is-size-7 has-text-grey">Ingresos (mes)</div>
          <div class="kpi">$38.500.000</div>
        </div>
        <div style="font-size:1.8rem;color:var(--primary)"><i class="fa-solid fa-wallet"></i></div>
      </div>
    </div>

    <div class="metric">
      <div style="display:flex;justify-content:space-between;align-items:center">
        <div>
          <div class="is-size-7 has-text-grey">Pendientes</div>
          <div class="kpi">18</div>
        </div>
        <div style="font-size:1.8rem;color:var(--primary)"><i class="fa-solid fa-clock"></i></div>
      </div>
    </div>
  </section>

  <!-- Gráficas lado a lado -->
<section class="table-card" style="margin-top:1rem; display:flex; gap:1rem; flex-wrap:wrap;">
  <!-- Pagos -->
  <div style="flex:1; min-width:300px;">
    <h3 class="title is-6">Pagos últimos 6 meses</h3>
    <canvas id="paymentsChart" height="180"></canvas>
  </div>

  <!-- Consumos -->
  <div style="flex:1; min-width:300px;">
    <h3 class="title is-6">Consumos últimos 6 meses</h3>
    <canvas id="consumptionChart" height="180"></canvas>
  </div>
</section>




  <!-- Filters -->
  <section class="filters" style="margin-top:8px">
    <div style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
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
    <PaginateListComponent :my-list="facturasList" :get-data-func="getFacturas" />



</section>
</template>
