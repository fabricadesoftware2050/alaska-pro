<template>
    <LoadingAnimation v-if="loading" />
    <button @click="showForm= !showForm" :title="showForm?'Cerrar':'Agregar nuevo registro'" :style="showForm?'background-color:#ff000069':''" class="fab"><i :class="showForm?'fa-solid fa-close':'fa-solid fa-plus'"></i></button>

    <section v-show="showForm" class="form-section">
    <h2 class="title is-5 has-text-centered mb-5">Registrar Empresa</h2>

    <form @submit.prevent="submitHandler">
        <div class="box">
            <div class="columns is-multiline">
                <!-- NIT -->
                <div class="column is-3">
                    <div class="field">
                        <label class="label">NIT</label>
                        <div class="control has-icons-left">
                            <input v-model="formData.nit" class="input" type="text" placeholder="Ingrese NIT" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-id-card"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Razón Social -->
                <div class="column is-6">
                    <div class="field">
                        <label class="label">Razón Social</label>
                        <div class="control has-icons-left">
                            <input v-model="formData.razon_social" class="input" type="text" placeholder="Ingrese razón social" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-building"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Siglas -->
                <div class="column is-3">
                    <div class="field">
                        <label class="label">Siglas</label>
                        <div class="control has-icons-left">
                            <input v-model="formData.siglas" class="input" type="text" placeholder="Ingrese siglas">
                            <span class="icon is-small is-left">
                                <i class="fas fa-font"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Representante Legal -->
                <div class="column is-6">
                    <div class="field">
                        <label class="label">Representante Legal</label>
                        <div class="control has-icons-left">
                            <input v-model="formData.nombre_representante_legal" class="input" type="text" placeholder="Nombre del representante legal">
                            <span class="icon is-small is-left">
                                <i class="fas fa-user-tie"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contador -->
                <div class="column is-6">
                    <div class="field">
                        <label class="label">Nombre Contador</label>
                        <div class="control has-icons-left">
                            <input v-model="formData.nombre_contador" class="input" type="text" placeholder="Nombre del contador">
                            <span class="icon is-small is-left">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Matrícula Contador -->
                <div class="column is-6">
                    <div class="field">
                        <label class="label">Matrícula Contador</label>
                        <div class="control has-icons-left">
                            <input v-model="formData.matricula_contador" class="input" type="text" placeholder="Número de matrícula">
                            <span class="icon is-small is-left">
                                <i class="fas fa-id-badge"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Revisor Fiscal -->
                <div class="column is-6">
                    <div class="field">
                        <label class="label">Nombre Revisor Fiscal</label>
                        <div class="control has-icons-left">
                            <input v-model="formData.nombre_revisor_fiscal" class="input" type="text" placeholder="Nombre del revisor fiscal">
                            <span class="icon is-small is-left">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Matrícula Revisor Fiscal -->
                <div class="column is-6">
                    <div class="field">
                        <label class="label">Matrícula Revisor Fiscal</label>
                        <div class="control has-icons-left">
                            <input v-model="formData.matricula_revisor_fiscal" class="input" type="text" placeholder="Número de matrícula">
                            <span class="icon is-small is-left">
                                <i class="fas fa-id-badge"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Logo -->
                <div class="column is-6">
                    <div class="field">
                        <label class="label">Logo de la empresa</label>
                        <div class="control">
                            <input type="file" @change="handleFileUpload" accept="image/*" class="input">
                        </div>
                    </div>
                </div>

                <!-- Estado -->
                <div class="column is-6">
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
</template>
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
const importData = ref([])
const loading = ref(true)
const router = useRouter()

const token = ref()
const user = ref()
const token_type = ref()
const showForm = ref(false)

const formData = ref({
    id: '',
    nit: '',
    razon_social: '',
    siglas: '',
    nombre_representante_legal: '',
    nombre_contador: '',
    matricula_contador: '',
    nombre_revisor_fiscal: '',
    matricula_revisor_fiscal: '',
    url_logo: '',
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
    // Crear un <script> dinámicamente
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


getEmpresa(URL_BASE_API+'/v1/empresas/1')


})

const submitHandler = async () => {
    loading.value = true
    error.value = ''

    if(!formData.value.razon_social || !formData.value.nit || !formData.value.nombre_representante_legal){
        error.value = 'Por favor complete todos los campos obligatorios'
        push.warning({ title: 'Campos obligatorios!', message: error.value, duration: 3000 })
        loading.value = false
        return
    }

    try {
    let CURRENT_URL=URL_BASE_API + '/v1/empresas'
    let response={}
    if(formData.value.id>0){
        CURRENT_URL+= '/'+formData.value.id
        response = await axios.put(CURRENT_URL,formData.value, {
            headers: {
                Authorization: `${token_type.value} ${token.value}`
            }
        })
    }else{
        response = await axios.post(CURRENT_URL,formData.value, {
            headers: {
                Authorization: `${token_type.value} ${token.value}`
            }
        })
    }

    const { data } = response
    if(response.status===200 || response.status===201){
        tipoDocumentosList.value = data
        push.success({ title: 'Operación exitosa!', message: response.status===201?'Item creado correctamente':'Item actualizado correctamente', duration: 3000 })
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





watch(showForm, (visible) => {
  if (!visible) {
    resetForm()
  }
})

</script>
