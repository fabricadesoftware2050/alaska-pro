<template>
    <!-- Loading modal -->
<div id="loadingModal">
  <div class="loader-container">
   <!-- Spinner -->
    <div class="loader"></div>
  </div>
</div>
</template>
<style>
/* Modal fullscreen */
#loadingModal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
background: linear-gradient(
  to bottom,
  #00e0c65b,
  #253e537b
);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  flex-direction: column;
}

/* Contenedor del logo y spinner */
.loader-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}



/* Loader animado (círculo giratorio) */
.loader {
  border: 5px solid #00e0c7;
  border-top: 5px solid #253e53; /* Color principal */
  border-radius: 50%;
  width: 44px;
  height: 44px;
  animation: spin 400ms linear infinite;
}

/* Animación giratoria */
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
