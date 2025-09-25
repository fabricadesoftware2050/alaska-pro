<template>
    <div class="divider"></div>
<h2 style="font-weight: bold;">Importar datos</h2>
<p style="font-size: 9pt; font-style: italic;">Se mostrarán los primeros 10 registros del archivo Excel para confirmar el formato de los datos, la primera fila debe contener el nombre del campo y orden de acuerdo a la tabla de datos, de la parte superior.</p>
<div id="drop-zone">Arrastra aquí tu archivo Excel o haz clic para seleccionar</div>
<input type="file" id="file-input" accept=".xlsx,.xls" hidden />

<div v-show="importData.length > 0" id="table-container" class="scrollable-table"></div>
<div v-if="importData.length > 0" style="margin-top: 20px; text-align: center;">
    <button @click="importFunction" class="button is-success">Confirmar Importación</button>
</div>
</template>
<script>
import { onMounted } from 'vue';

export default {
  props: {
    importData: {
      type: Object,
      required: true
    },
    importFunction: {
      type: Function,
      required: true
    }
  }
};

onMounted(() => {

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




})
</script>
<style>

    #drop-zone {
      background-color: #253e53;
      border: 3px dashed #00e0c7;
      border-radius: 10px;
      padding: 40px;
      text-align: center;
      color: #00e0c7;
      cursor: pointer;
      margin-top: 20px;
      margin-bottom: 20px;
    }
    #drop-zone.dragover ,#drop-zone:hover {
      background-color: #00DC82;
      color: white;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    table, th, td {
      border: 1px solid #ccc;
    }
    th, td {
      padding: 8px;
      text-align: left;
    }
    th {
      background: #f4f4f4;
    }



  </style>
