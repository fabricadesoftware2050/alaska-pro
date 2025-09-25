

<template>

    <div v-cloak class="light">



<!-- SIDEBAR -->
<SideBar />

<!-- TOPBAR -->
<TopBar />

<!-- MAIN -->
<main  id="main" class="main">
    <slot />
</main>
<FooterInfo />
</div>

</template>

<style>

:root{
  --primary:#00E0C7;
  --secondary:#253e53;
  --bg-light:#f6f9fb;
  --card-light:#ffffff;
  --text-dark:#0f172a;
  --muted:#64748b;
  --sidebar-width:260px;
  --sidebar-collapsed:80px;
  --fab-size: 60px;
--fab-color: #00e0c68d; /* tu color principal */
--fab-hover: #00c0a9;
}

.scrollable-table {
    margin-top: 5px;;
        max-height: 600px;   /* límite vertical */
        overflow-y: auto;    /* scroll vertical */
        overflow-x: auto;    /* scroll horizontal si se necesita */

        border-radius: 6px;
        padding: 0.1rem;
        background: #fff;    /* fondo para separar */
        min-width: 100%;        /* asegura que ocupe todo el ancho */
        border-collapse: collapse;
        white-space: nowrap;    /* evita que el texto se parta */
        }
.is-inactive {
  background-color: #e5e5e5 !important; /* Color de fondo rojo claro */
  color: #a0a0a0 !important; /* Color de texto rojo oscuro */
}
.fab {
      position: fixed;
      bottom: 50px;
      right: 20px;
      width: var(--fab-size);
      height: var(--fab-size);
      border-radius: 50%;
      background: var(--fab-color);
      color: white;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      cursor: pointer;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      transition: transform 0.2s ease, background 0.2s ease;
        z-index: 1000;
    }

    .fab:hover {
      background: var(--fab-hover);
      transform: scale(1.1);
    }

    .fab:active {
      transform: scale(0.95);
    }
/* Divider */
    .divider {
      height: 1px;
      background: #dedddd;
      margin: 1.5rem 0;
    }



/* Paginación estilo Material solo UI */
.pagination-material {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;
  margin-top: 1rem;
  flex-wrap: wrap;
}
button {
  cursor: pointer !important;
  box-shadow: 0 1px 3px var(--primary) !important;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

button:hover {
  transform: scale(1.1); /* Zoom del 10% */
  box-shadow: 0 4px 10px var(--primary) !important; /* Aumenta sombra al hacer hover */
}

.button.is-info{
    background-color: var(--secondary) !important;
    color: #fff !important;
    border: none !important;
    border-radius: 26px !important;
    padding: 10px 12px !important;
    cursor: pointer !important;
    margin-left: 3px !important;
    margin-right: 3px !important;
}

.button.is-success{
    color: #fff !important;
    border: none !important;
    border-radius: 26px !important;
    padding: 10px 12px !important;
    cursor: pointer !important;
    margin-left: 3px !important;
    margin-right: 3px !important;
}

.button.is-danger{
    color: #fff !important;
    border: none !important;
    border-radius: 26px !important;
    padding: 10px 12px !important;
    cursor: pointer !important;
    margin-left: 15px !important;
    margin-right: 3px !important;
}
.button.is-warning, .button.is-primary{
    color: #fff !important;
    border: none !important;
    border-radius: 26px !important;
    padding: 10px 12px !important;
    cursor: pointer !important;
    margin-left: 3px !important;
    margin-right: 3px !important;
}

.pagination-material button {
  background: var(--secondary);
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 6px 12px;
  cursor: pointer;
  transition: background 0.2s;
}

.pagination-material button:hover {
  background: #00c0a9;
}

.pages button {
  background: transparent;
  color: #00e0c7;
  border: 1px solid #00e0c7;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  font-size: 0.9rem;
  margin: 0 2px;
}

.pages button.is-current {
  background: #00e0c7;
  color: #fff;
}

.footer-gov {
  width: 100%;
  background: #f6f9fb;
  border-top: 1px solid #e6eef5;
  padding: 0.5rem 2rem;
  position: fixed;      /* <--- Aquí lo hacemos fijo */
  bottom: 0;            /* <--- Pegado al fondo */
  left: 0;              /* <--- Alineado a la izquierda */
  font-family: "Poppins", sans-serif;
  color: #0f172a;
  font-size: 0.875rem;
  z-index: 100;         /* <--- Siempre visible sobre otros elementos */
}

body.dark .footer-gov {
  background: #0b1420;
  border-top-color: rgba(255,255,255,0.05);
  color: #cfeaf0;
}

.footer-content {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  align-items: center;
}

.footer-left p {
  margin: 0.2rem 0;
}

.footer-left .muted {
  color: #64748b;
  font-size: 0.8rem;
}

body.dark .footer-left .muted {
  color: #94a3b8;
}

.footer-right p {
  margin: 0.2rem 0;
}

.footer-right a {
  color: var(--primary-color, #00E0C7);
  text-decoration: none;
  margin-right: 0.5rem;
}

.footer-right a:hover {
  text-decoration: underline;
}
/* Global */
html,body{height:100%;font-family:"Poppins",system-ui,Segoe UI,Roboto,Helvetica,Arial;margin:0;}
body.light{background:var(--bg-light);color:var(--text-dark);}
body.dark{background:#071222;color:#e6eef6;}

/* Topbar */
.topbar{
  position:fixed; top:0; left:var(--sidebar-width); right:0;
  height:64px; display:flex; align-items:center; justify-content:space-between;
  padding:0 1.25rem; background:var(--card-light); border-bottom:1px solid #e6eef5; z-index:50;
  transition:left .24s ease, background .2s ease, color .2s ease;
}
body.dark .topbar{background:#0b1420; border-bottom-color: rgba(255,255,255,0.04);}

.brand{display:flex;gap:1rem;align-items:center;}
.brand .logo{width:44px;height:44px; display:flex;align-items:center;justify-content:center; color:#fff; font-weight:700; border-radius:8px; background:linear-gradient(135deg,var(--primary),#00c0a9); box-shadow:0 6px 18px rgba(0,224,199,0.15);}
.brand h1{font-size:1.05rem;margin:0;font-weight:600;color:inherit;}

/* Sidebar */
.sidebar{
  position:fixed; top:0; left:0; bottom:0; width:var(--sidebar-width); padding:1.25rem 0;
  background: linear-gradient(180deg,#ffffff,#f8fbfc); border-right:1px solid #e6eef5;
  display:flex; flex-direction:column; gap:1rem; transition: width .24s ease, background .2s;
  z-index:60;
}
body.dark .sidebar{background: linear-gradient(180deg,#081620,#06121a); border-right-color: rgba(255,255,255,0.04);}

.sidebar .menu-scroll{overflow:auto; flex:1; padding:0 .5rem;}
.menu-link, .menu-toggle-label{
  display:flex;align-items:center;gap:.9rem;padding:.6rem .8rem;border-radius:8px;
  color:var(--text-dark);text-decoration:none;font-weight:500;transition:background .15s,color .15s;
}
body.dark .menu-link, body.dark .menu-toggle-label{color:#cfeaf0;}
.menu-link:hover, .menu-toggle-label:hover{background: rgba(0,224,199,0.06); color: var(--primary);}
.menu-icon{width:32px; display:inline-flex;align-items:center;justify-content:center;color:var(--secondary);}

.submenu-toggle{display:none;}
.submenu{max-height:0; overflow:hidden; transition:max-height .28s ease; padding-left:.75rem;}
.submenu a{display:block;padding:.45rem .8rem;border-radius:6px;color:var(--muted); font-size:.92rem;transition:padding-left .2s, background .2s;}
.submenu a:hover{background: var(--secondary); color:var(--primary); padding-left:1.2rem;}
.submenu-toggle:checked + .menu-toggle-label + .submenu{max-height:480px;}
.submenu-toggle:checked + .menu-toggle-label .chev{transform:rotate(180deg);color:var(--primary);}

/* Sidebar footer */
.sidebar-footer{padding:1rem;border-top:1px solid rgba(0,0,0,0.04);font-size:.85rem;color:var(--muted);}
body.dark .sidebar-footer{border-top-color: rgba(255,255,255,0.04);}

/* Collapsed sidebar */
body.sidebar-collapsed .sidebar{width:var(--sidebar-collapsed);}
body.sidebar-collapsed .topbar{left:var(--sidebar-collapsed);}
body.sidebar-collapsed .main{margin-left:var(--sidebar-collapsed);}
body.sidebar-collapsed .menu-link span.label,
body.sidebar-collapsed .menu-toggle-label span.label{display:none;}
body.sidebar-collapsed .menu-toggle-label .chev{display:none;}
body.sidebar-collapsed .submenu{display:none !important;}

/* Topbar controls */
.topbar .controls{display:flex;gap:.75rem;align-items:center;}
.topbar .search{width:320px;min-width:160px;}
.topbar .icon-btn{background:transparent;border:0;padding:.45rem .6rem;border-radius:8px;cursor:pointer;}
.topbar .user-compact{display:flex;gap:.65rem;align-items:center;}

/* Main content */
.main{margin-left:var(--sidebar-width);margin-top:64px;padding:1.25rem;transition:margin-left .24s;}
.metrics{display:grid; grid-template-columns: repeat(3,1fr); gap:1rem; margin-bottom:1rem;}
.metric{
  background:var(--card-light);
  padding:1rem; border-radius:12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
  transition: transform .2s, box-shadow .2s;
}
.metric:hover{
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(0,0,0,0.12);
}
body.dark .metric{background:#071726; box-shadow:none;}
.metric .kpi{font-size:1.3rem; font-weight:700;}

/* Filters */
section{background:var(--card-light);padding:1rem;border-radius:12px;margin-bottom:1rem;}
body.dark section{background:#071726;}

/* Table */
.table-card{
  background:var(--card-light);padding:1rem;border-radius:12px;
  box-shadow:0 6px 18px rgba(12,24,50,0.04);
  overflow:auto;
}
body.dark .table-card{background:#071726;}
.table-card table{
  border-collapse: separate; border-spacing:0; border-radius:12px; overflow:hidden;
}
.table-card thead{
  background: linear-gradient(90deg,#00e0c7,#00c0a9); color:#fff; font-weight:600;
}
.table-card tbody tr:hover{background: rgba(0,224,199,0.06);}

/* Tags */
.tag-status{padding:.2rem .5rem; border-radius:6px; font-size:.75rem; font-weight:600;}
.tag-pagada{background:#d1f7ef;color:#008f76;}
.tag-pendiente{background:#fff4d1;color:#b18900;}
.tag-vencida{background:#ffd1d1;color:#b10000;}
.sidebar {
    overflow-y: auto; /* Habilita el scroll vertical */
    scrollbar-width: thin; /* Ancho del scroll para Firefox */
    scrollbar-color: #00E0C7 #f1f1f1; /* Colores para Firefox */
}

.sidebar::-webkit-scrollbar {
    width: 10px; /* Ancho del scroll */
}

.sidebar::-webkit-scrollbar-track {
    background: #f1f1f1; /* Fondo del track */
    border-radius: 10px;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #00E0C7; /* Color del scroll */
    border-radius: 10px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: #00b8a5; /* Hover */
}


/* Responsive */
@media (max-width: 1024px){
  .topbar .search{display:none;}
  .metrics{grid-template-columns:repeat(1,1fr);}
}
.main {
  flex: 1;
  overflow-y: auto; /* ✅ habilita scroll vertical */
  height: 100vh;    /* ocupa toda la altura de la ventana */
  padding-bottom: 120px;
  background-color: #253e53;
}
/* ===== Scrollbar personalizado ===== */
.main::-webkit-scrollbar {
  width: 10px;              /* ancho del scroll */
}

.main::-webkit-scrollbar-track {
  background: #f1f1f1;       /* fondo del track */
  border-radius: 10px;
}

.main::-webkit-scrollbar-thumb {
  background: #00E0C7;       /* color del scroll */
  border-radius: 10px;
}

.main::-webkit-scrollbar-thumb:hover {
  background: #00b8a5;       /* hover */
}

/* Firefox */
.main {
  scrollbar-width: thin;              /* ancho del scroll */
  scrollbar-color: #00E0C7 #f1f1f1;   /* thumb color | track color */
}
.box {
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.input, .select select {
  border-radius: 8px;
  border: 1px solid #d1d5db;
  transition: all 0.2s ease;
}

.input:focus, .select select:focus {
  border-color: #00E0C7;
  box-shadow: 0 0 0 0.2rem rgba(0,224,199,0.25);
}

.label {
  font-weight: 600;
  color: #374151;
}

</style>
