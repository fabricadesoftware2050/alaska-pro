<template>
    <aside class="sidebar" aria-label="Sidebar principal">
  <div class="sidebar-inner">
    <div style="padding:0 1rem;">
      <div class="brand-compact">
        <div class="logo"><img src="/images/logo.png"></div>
      </div>
    </div>

    <div class="menu-scroll">
      <!-- Dashboard -->
      <div class="menu-group">
        <NuxtLink to="/home" class="menu-link px-3">
          <span class="menu-icon"><i class="fa-solid fa-chart-line"></i></span>
          <span class="label">Inicio</span>
        </NuxtLink>
      </div>

      <!-- Facturación submenu -->
      <div class="menu-group">
        <input id="m-fact" type="checkbox" class="submenu-toggle">
        <label for="m-fact" class="menu-toggle-label px-3">
          <span class="menu-icon"><i class="fa-solid fa-file-invoice"></i></span>
          <span class="label">Facturación</span>
          <span class="chev"><i class="fa-solid fa-chevron-down"></i></span>
        </label>
        <div class="submenu">
          <a href="#">Facturas</a>
                <NuxtLink to="/facturacion/cotizaciones" class="menu-link px-3">
                    Cotizaciones
                </NuxtLink>
          <a href="#">Órdenes de servicio</a>
        <NuxtLink to="/facturacion/facturasMasivasServiciospublicos">Facturas Masivas SP</NuxtLink>

        </div>
      </div>

      <!-- Clientes submenu -->
      <div class="menu-group">
        <input id="m-client" type="checkbox" class="submenu-toggle">
        <label for="m-client" class="menu-toggle-label px-3">
          <span class="menu-icon"><i class="fa-solid fa-users"></i></span>
          <span class="label">Clientes</span>
          <span class="chev"><i class="fa-solid fa-chevron-down"></i></span>
        </label>
        <div class="submenu">
          <a href="#">Suscriptores</a>
          <a href="#">Terceros</a>
          <a href="#">Contratos</a>
          <a href="#">Cartera</a>
        </div>
      </div>

      <!-- Operaciones submenu -->
      <div class="menu-group">
        <input id="m-ops" type="checkbox" class="submenu-toggle">
        <label for="m-ops" class="menu-toggle-label px-3">
          <span class="menu-icon"><i class="fa-solid fa-briefcase"></i></span>
          <span class="label">Operaciones</span>
          <span class="chev"><i class="fa-solid fa-chevron-down"></i></span>
        </label>
        <div class="submenu">
          <a href="#">Órdenes de servicio</a>
          <a href="#">Planificación</a>
          <a href="#">Inventario</a>
          <a href="#">Proveedores</a>
        </div>
      </div>

      <!-- Servicios -->
      <div class="menu-group">
        <a href="#" class="menu-link px-3">
          <span class="menu-icon"><i class="fa-solid fa-bolt"></i></span>
          <span class="label">Servicios</span>
        </a>
      </div>

      <!-- Medición -->
      <div class="menu-group">
        <a href="#" class="menu-link px-3">
          <span class="menu-icon"><i class="fa-solid fa-ruler-combined"></i></span>
          <span class="label">Medición</span>
        </a>
      </div>

      <!-- Reportes submenu -->
      <div class="menu-group">
        <input id="m-rep" type="checkbox" class="submenu-toggle">
        <label for="m-rep" class="menu-toggle-label px-3">
          <span class="menu-icon"><i class="fa-solid fa-chart-pie"></i></span>
          <span class="label">Reportes</span>
          <span class="chev"><i class="fa-solid fa-chevron-down"></i></span>
        </label>
        <div class="submenu">
          <a href="#">Consumo</a>
          <a href="#">Ingresos</a>
          <a href="#">Mora</a>
          <a href="#">Exportar CSV</a>
        </div>
      </div>

      <!-- Administración submenu -->
      <div class="menu-group">
        <input id="m-admin" type="checkbox" class="submenu-toggle">
        <label for="m-admin" class="menu-toggle-label px-3">
          <span class="menu-icon"><i class="fa-solid fa-cogs"></i></span>
          <span class="label">Administración</span>
          <span class="chev"><i class="fa-solid fa-chevron-down"></i></span>
        </label>
        <div class="submenu">
          <a href="#">Usuarios &amp; Roles</a>
          <a href="#">Integraciones</a>
          <a href="#">Auditoría</a>
          <a href="#">Ajustes del sistema</a>
        </div>
      </div>

      <!-- Importaciones -->
      <div class="menu-group">
        <input id="m-import" type="checkbox" class="submenu-toggle">
        <label for="m-import" class="menu-toggle-label px-3">
          <span class="menu-icon"><i class="fa-solid fa-database"></i></span>
          <span class="label">Datos Maestros</span>
          <span class="chev"><i class="fa-solid fa-chevron-down"></i></span>
        </label>
        <div class="submenu">
          <NuxtLink to="/maestros/tipoDocumento">Tipos de Documento</NuxtLink>
        </div>
      </div>

    </div>

    <div class="sidebar-footer px-4">
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <small>v1.2.0</small>
        <a href="#" title="Ayuda" style="color:var(--primary)"><i class="fa-solid fa-circle-question"></i></a>
      </div>
    </div>
  </div>
</aside>
</template>
