<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('home')}}">
      <div class="sidebar-brand-text mx-3">Sistem Informasi <sup>Persediaan</sup></div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <li class="nav-item {{set_active('home')}}">
      <a class="nav-link" href="{{route('home')}}">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->
  @role('admin')
  <div class="sidebar-heading">
      User Data
  </div>

  <!-- Nav Item - Pages Collapse Menu -->
  <li class="nav-item {{set_active('user.*')}}">
    <a class="nav-link" href="{{route('user.index')}}">
        <i class="fas fa-fw fa-table"></i>
        <span>Data User</span></a>
  </li>
  


  {{-- <li class="nav-item">
    <a class="nav-link" href="tables.html">
        <i class="fas fa-fw fa-table"></i>
        <span>Role User</span>
    </a>
  </li> --}}
 
  <!-- Divider -->
  <hr class="sidebar-divider">

  <div class="sidebar-heading">
    Database
  </div>

  
  <li class="nav-item">
    <a class="nav-link" href="{{route('backup')}}">
        <i class="fas fa-fw fa-table"></i>
        <span>Backup Database</span>
    </a>
  </li>
  @endrole
  
  @role('operator')
  <div class="sidebar-heading">
    Master Data
  </div>
  <li class="nav-item {{set_active('kategori.index')}}">
    <a class="nav-link" href="{{route('kategori.index')}}">
        <i class="fas fa-fw fa-table"></i>
        <span>Kategori Barang</span>
    </a>
  </li>

  <li class="nav-item {{set_active('satuan.index')}}">
    <a class="nav-link" href="{{route('satuan.index')}}">
        <i class="fas fa-fw fa-table"></i>
        <span>UoM</span>
    </a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <div class="sidebar-heading">
    Data Barang
  </div>

  <li class="nav-item {{set_active('barang.*')}}">
    <a class="nav-link" href="{{route('barang.index')}}">
        <i class="fas fa-fw fa-table"></i>
        <span>Barang</span>
    </a>
  </li>

  <li class="nav-item {{set_active('barang-masuk.*')}}">
    <a class="nav-link" href="{{route('barang-masuk.index')}}">
        <i class="fas fa-fw fa-table"></i>
        <span>Barang Masuk</span>
    </a>
  </li>

  <li class="nav-item {{set_active('barang-keluar.*')}}">
    <a class="nav-link" href="{{route('barang-keluar.index')}}">
        <i class="fas fa-fw fa-table"></i>
        <span>Barang Keluar</span>
    </a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <div class="sidebar-heading">
    Laporan
  </div>
  
  <li class="nav-item {{set_active('stock')}}">
    <a class="nav-link" href="{{route('stock')}}">
        <i class="fas fa-fw fa-table"></i>
        <span>Laporan Stok Barang</span>
    </a>
  </li>

  <li class="nav-item {{set_active('barang-masuk')}}">
    <a class="nav-link" href="{{route('barang-masuk')}}">
        <i class="fas fa-fw fa-table"></i>
        <span>Laporan Barang Masuk</span>
    </a>
  </li>

  <li class="nav-item {{set_active('barang-keluar')}}">
    <a class="nav-link" href="{{route('barang-keluar')}}">
        <i class="fas fa-fw fa-table"></i>
        <span>Laporan Barang Keluar</span>
    </a>
  </li>

  @endrole
  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>