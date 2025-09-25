<template>
    <!-- Paginación UI -->
  <nav class="pagination-material" aria-label="pagination">
    <button class="prev" @click="getDataFunc(myList.first_page_url)" :disabled="myList.current_page<=1">Primera</button>
    <button class="prev" @click="getDataFunc(myList.prev_page_url)"  :disabled="myList.prev_page_url==null"><i class="fa-solid fa-chevron-left"></i> Anterior</button>
    <div class="pages">
      <button @click="getDataFunc(myList.path+'?page='+(myList.current_page - 1))" v-if="myList.current_page - 1 > 0">{{ myList.current_page - 1 }}</button>
      <button class="is-current">{{ myList.current_page }}</button>
      <button @click="getDataFunc(myList.path+'?page='+(myList.current_page + 1))" v-if="myList.current_page + 1 <= myList.last_page">{{ myList.current_page + 1 }}</button>
    </div>
    <button class="next" @click="getDataFunc(myList.next_page_url)"  :disabled="myList.next_page_url==null">Siguiente <i class="fa-solid fa-chevron-right"></i></button>
    <button class="prev" @click="getDataFunc(myList.last_page_url)" :disabled="myList.current_page>=myList.last_page">Última</button>
  </nav>
  <p class="pagination-material">Mostrando {{ myList.from }} - {{ myList.to }} de {{ myList.total }} registros</p>
</template>

<script>
export default {
  props: {
    myList: {
      type: Object,
      required: true
    },
    getDataFunc: {
      type: Function,
      required: true
    }
  }
};
</script>
