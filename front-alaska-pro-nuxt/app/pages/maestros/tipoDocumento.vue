<script setup>
definePageMeta({
    middleware:'auth'
})

import { watch, onMounted, ref } from 'vue'  // Importar ref
import { useRouter } from 'vue-router'
import axios from 'axios'
import { navigateTo } from 'nuxt/app'
import { ROLES, URL_BASE_API } from '~/utils/constants'
import { push } from 'notivue'
import PaginateListComponent from '~/components/PaginateListComponent.vue'
import LoadingAnimation from '~/components/LoadingAnimation.vue'
import ImportDataComponent from '~/components/ImportDataComponent.vue'
import { jwtDecode } from 'jwt-decode'

const error = ref('')
const tipoDocumentosList = ref([])
const importData = ref([])
const loading = ref(true)
const router = useRouter()

const token = ref()
const user = ref()
const token_type = ref()
const showForm = ref(false)

const formData = ref({
    id: '',
    codigo: '',
    nombre_corto: '',
    nombre: '',
    estado: 'ACTIVO'
})

const formDataFilter = ref({
    nombre: '',
    estado: ''
})


onMounted(() => {
token.value = localStorage.getItem('token')
token_type.value = localStorage.getItem('token_type')

    user.value = jwtDecode(token.value) ?? {}
    // Crear un <script> dinámicamente para Particles.js
  const script = document.createElement('script')
  script.src = 'https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js'
  script.onload = () => {
    // Inicializar partículas después de que se cargue el script
const dropZone = document.getElementById("drop-zone");
    const fileInput = document.getElementById("file-input");
    const tableContainer = document.getElementById("table-container");

    // Click en zona -> abrir file input
    dropZone.addEventListener("click", () => fileInput.click());

    // Drag Over
    dropZone.addEventListener("dragover", (e) => {
      e.preventDefault();
      dropZone.classList.add("dragover");
    });

    dropZone.addEventListener("dragleave", () => {
      dropZone.classList.remove("dragover");
    });

    // Drop
    dropZone.addEventListener("drop", (e) => {
      e.preventDefault();
      dropZone.classList.remove("dragover");
      handleFile(e.dataTransfer.files[0]);
    });

    // Input change
    fileInput.addEventListener("change", (e) => {
      handleFile(e.target.files[0]);
    });

    // Procesar archivo Excel
    function handleFile(file) {
      if (!file) return;
      const reader = new FileReader();
      reader.onload = (e) => {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: "array" });
        const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
        const json = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

        renderTable(json);
      };
      reader.readAsArrayBuffer(file);
    }

    // Renderizar tabla
    function renderTable(data) {
        importData.value = data
      let html = "<table>";
      data.forEach((row, i) => {
        if(i>10) return; // Mostrar solo los primeros 10 registros
        html += "<tr>";
        row.forEach((cell) => {
          html += i === 0 ? `<th>${cell ?? ""}</th>` : `<td>${cell ?? ""}</td>`;
        });
        html += "</tr>";
      });
      html += "</table>";
      tableContainer.innerHTML = html;
    }
}
document.body.appendChild(script)


getTipoDocumentos(URL_BASE_API+'/v1/tiposDocumentos')


})


const getTipoDocumentos = async (url) => {

  loading.value = true
  error.value = ''
  try {

    const { data } = await axios.get(url, {
      headers: {
        Authorization: `${token_type.value} ${token.value}`
      }
    })
    tipoDocumentosList.value = data
  } catch (err) {
    if(err.status===401){
        push.warning({ title: 'Upps!', message: 'Su sesión expiró' })

        navigateTo("/")
    }else{
        push.error({ title: 'Error!'||'Intente más tarde', message: err.message, timeout: 8000 })
    }
  } finally {
    loading.value = false
  }
}

const filterHandler = async () => {

  loading.value = true
  error.value = ''
  try {

    if(formDataFilter.value.nombre && formDataFilter.value.nombre.trim().length<2){
        error.value = 'El filtro debe tener al menos 2 caracteres'
        push.warning({ title: 'Filtro inválido!', message: error.value, duration: 5000 })
        loading.value = false
        return
    }
    const { data } = await axios.get(URL_BASE_API+"/v1/tiposDocumentos?query=true&nombre="+formDataFilter.value.nombre+"&estado="+formDataFilter.value.estado, {
      headers: {
        Authorization: `${token_type.value} ${token.value}`
      }
    })
    tipoDocumentosList.value = data
  } catch (err) {
    if(err.status===401){
        push.warning({ title: 'Upps!', message: 'Su sesión expiró' })

        navigateTo("/")
    }else{
        push.error({ title: 'Error!'||'Intente más tarde', message: err.message, timeout: 8000 })
    }
  } finally {
    loading.value = false
  }
}

const deleteTipoDocumentos = async (id) => {

  if(!confirm("¿Está seguro de eliminar este tipo de documento?")) return;
  loading.value = true
  error.value = ''
  try {

    const response = await axios.delete(URL_BASE_API+'/v1/tiposDocumentos/'+id, {
      headers: {
        Authorization: `${token_type.value} ${token.value}`
      }
    })
    const { data } = response
    if(response.status===200){
        tipoDocumentosList.value.data = tipoDocumentosList.value.data.filter(item => item.id !== id)
        push.success({ title: 'Operación exitosa!', message: data.message})
    }


  } catch (err) {
        if(err.status===401){
        push.warning({ title: 'Upps!', message: 'Su sesión expiró' })

        navigateTo("/")
    }
    error.value = err.response?.data?.message || 'Acción no permitida'
  } finally {
    loading.value = false
  }
}

const importTipoDocumentos = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await axios.post(URL_BASE_API+'/v1/import_tiposDocumentos',{data:importData.value}, {
      headers: {
        Authorization: `${token_type.value} ${token.value}`
      }
    })
    const { data } = response
    if(response.status===200){
        tipoDocumentosList.value = data.tipos
        push.success({ title: 'Operación exitosa!', message: data.message, timeout: 8000 })
        importData.value = []
    }else{
        push.error({ title: 'Error!'||'Intente más tarde', message: data.message, timeout: 8000 })
    }


  } catch (err) {
        if(err.status===401){
        push.warning({ title: 'Upps!', message: 'Su sesión expiró' })

        navigateTo("/")
    }
    error.value = err.response?.data?.message || 'Acción no permitida'
  } finally {
    loading.value = false
  }
}

const submitHandler = async () => {
    loading.value = true
    error.value = ''

    if(!formData.value.nombre_corto || !formData.value.nombre || !formData.value.estado){
        error.value = 'Por favor complete todos los campos obligatorios'
        push.warning({ title: 'Campos obligatorios!', message: error.value, duration: 3000 })
        loading.value = false
        return
    }

    try {


    const response = await axios.post(URL_BASE_API + '/v1/tiposDocumentos',formData.value, {
      headers: {
        Authorization: `${token_type.value} ${token.value}`
      }
    })
    const { data } = response
    if(response.status===200 || response.status===201){
        tipoDocumentosList.value = data
        push.success({ title: 'Operación exitosa!', message: response.status===201?'Tipo de documento creado correctamente':'Tipo de documento actualizado correctamente', duration: 3000 })
        resetForm()
    }else{
        error.value = data.message || 'Respuesta desconocida del servidor'
        push.error({ title: 'Upps!', message: error.value, duration: 3000 })
    }

    } catch (err) {
        if(err.status===401){
            error.value = 'Credenciales inválidas'
            push.error({ title: 'Upps!', message: error.value, duration: 3000 })
        }else if(err.response?.data?.message.includes('UNIQUE')){
            error.value = 'Ya existe un tipo de documento con estos datos'
            push.error({ title: 'Duplicado!', message: error.value, duration: 3000 })
        }else{
            error.value = err.response?.data?.message || 'Intenta nuevamente'
            push.error({ title: 'Upps!', message: error.value, duration: 3000 })
        }
    } finally {
        loading.value = false
    }
}

const resetForm = () => {
    formData.value = { codigo: '', nombre_corto: '', nombre: '', estado: 'ACTIVO' }
    formDataFilter.value = { nombre: '', estado: '' }
    showForm.value = false
}

const editData = (data) => {
  formData.value = data
  showForm.value = true

  // Buscar el contenedor y scrollear
  const main = document.querySelector('.main')
  if (main) {
    main.scrollTo({
      top: 0,
      behavior: 'smooth'
    })
  }
}

watch(showForm, (visible) => {
  if (!visible) {
    resetForm()
  }
})

</script>
<template>
    <LoadingAnimation v-if="loading" />
    <button @click="showForm= !showForm" :title="showForm?'Cerrar':'Agregar nuevo registro'" :style="showForm?'background-color:#ff000069':''" class="fab"><i :class="showForm?'fa-solid fa-close':'fa-solid fa-plus'"></i></button>

    <section v-show="showForm" class="form-section">
    <h2 class="title is-5 has-text-centered mb-5">Registrar Tipo de Documento</h2>

    <form @submit.prevent="submitHandler">
        <div class="box">

            <div class="columns is-multiline">
            <!-- Código -->
            <div class="column is-2">
                <div class="field">
                <label class="label">Código</label>
                <div class="control has-icons-left">
                    <input v-model="formData.codigo" class="input" type="text" placeholder="(Opcional)" >
                    <span class="icon is-small is-left">
                    <i class="fas fa-barcode"></i>
                    </span>
                </div>
                </div>
            </div>

            <!-- Nombre corto -->
            <div class="column is-2">
                <div class="field">
                <label class="label">Nombre corto</label>
                <div class="control has-icons-left">
                    <input v-model="formData.nombre_corto" class="input" type="text" placeholder="Ej: NIT, CC..." required>
                    <span class="icon is-small is-left">
                    <i class="fas fa-tag"></i>
                    </span>
                </div>
                </div>
            </div>

            <!-- Nombre -->
            <div class="column is-5">
                <div class="field">
                <label class="label">Nombre completo</label>
                <div class="control has-icons-left">
                    <input class="input" v-model="formData.nombre" type="text" placeholder="Ej: Número de Identificación Tributaria" required>
                    <span class="icon is-small is-left">
                    <i class="fas fa-file-alt"></i>
                    </span>
                </div>
                </div>
            </div>

            <!-- Estado -->
            <div class="column is-3">
                <div class="field">
                <label class="label">Estado</label>
                <div class="control has-icons-left">
                    <div class="select is-fullwidth">
                    <select v-model="formData.estado" required>
                        <option value="" disabled selected>Seleccione</option>
                        <option>ACTIVO</option>
                        <option>INACTIVO</option>
                    </select>
                    </div>
                    <span class="icon is-small is-left">
                    <i class="fas fa-toggle-on"></i>
                    </span>
                </div>
                </div>
            </div>
            </div>

            <!-- Botones -->
            <div class="field is-grouped is-justify-content-center mt-4">
            <p class="control">
                <button type="submit" class="button is-primary">
                <span class="icon"><i class="fas fa-save"></i></span>
                <span>Guardar</span>
                </button>
            </p>
            <p class="control">
                <button @click="resetForm" class="button is-light">
                <span class="icon"><i class="fas fa-refresh"></i></span>
                <span>Limpiar</span>
                </button>
            </p>


            </div>
        </div>
    </form>
    <ImportDataComponent :import-data="importData" :import-function="importTipoDocumentos" />


  </section>



  <!-- Table -->
  <section class="table-card">
   <!-- Filters -->

    <form @submit.prevent="filterHandler">
        <div style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end">
            <div style="width:65%;min-width:160px">
                <label class="is-size-7 has-text-weight-semibold">Nombre o código</label>
                <input v-model="formDataFilter.nombre" class="input" placeholder="Ingrese un nombre, código o palabra clave">
            </div>
            <div style="width:20%">
                <label class="is-size-7 has-text-weight-semibold">Estado</label>
                <div class="select is-fullwidth">
                <select v-model="formDataFilter.estado">
                    <option value="">Todos</option>
                    <option>ACTIVO</option>
                    <option>INACTIVO</option>
                </select>
                </div>
            </div>
            <div style="flex:none">
                <button type="submit" class="button is-primary" id="filterBtn">
                <i class="fa-solid fa-filter"></i> Filtrar
                </button>
            </div>
        </div>
    </form>

    <table class="table is-fullwidth is-hoverable">
      <thead>
        <tr>
          <th>Código</th>
          <th>Nombre corto</th>
          <th>Nombre</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="da in tipoDocumentosList.data" :class="{ 'is-inactive': da.estado==='INACTIVO' }" :key="da.id">
          <td>{{da.codigo}}</td>
          <td>{{da.nombre_corto}}</td>
          <td>{{da.nombre}}</td>
          <td>
            <span
              class="tag-status "
              :class="{
                'tag-pagada': da.estado === 'ACTIVO',
                'tag-vencida': da.estado === 'INACTIVO'
              }"
            >
              {{da.estado}}
            </span>
          </td>
          <td>
            <button @click="editData(da)" class="button is-small is-info mx-1"><i class="fa-solid fa-eye"></i></button>
            <button v-if="user.role===ROLES.ADMIN" @click="deleteTipoDocumentos(da.id)" class="button is-small is-danger mx-1"><i class="fa-solid fa-trash"></i></button>
          </td>
        </tr>
        <tr v-if="tipoDocumentosList?.data?.length===0">
          <td colspan="5" class="has-text-centered">No hay datos para mostrar</td>

        </tr>

      </tbody>
    </table>
  <!-- Paginación UI -->
  <PaginateListComponent :my-list="tipoDocumentosList" :get-data-func="getTipoDocumentos" />

 </section>
</template>


