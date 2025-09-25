<script setup>
import { Notivue, Notification, push } from 'notivue'
</script>
<template>
  <NuxtLayout>
    <NuxtPage :key="$route.fullPath" />
  </NuxtLayout>
   <Notivue v-slot="item">
    <Notification :item="item" />
  </Notivue>
</template>
<style>
[v-cloak] {
  display: none !important;
}
</style>
