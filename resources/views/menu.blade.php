<!-- Sidebar Menu -->
<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
    <!-- Add icons to the links using the .nav-icon class
          with font-awesome or any other icon font library -->

    <li class="nav-item">
      <a href="{{ url('/') }}" class="nav-link @if(Request::segment(2) == '' && Request::segment(1) != 'change_photo') active @endif">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Dashboard</p>
      </a>
    </li>
    
    @if(auth()->user()->level == "admin")
    <li class="nav-item @if(Request::segment(2) == 'kabupaten' || Request::segment(2) == 'kecamatan' || Request::segment(2) == 'desa' || Request::segment(2) == 'tps') menu-open @endif">
      <a href="#" class="nav-link @if(Request::segment(2) == 'kabupaten' || Request::segment(2) == 'kecamatan' || Request::segment(2) == 'desa' || Request::segment(2) == 'tps') active @endif">
        <i class="nav-icon fas fa-archive"></i>
        <p>
          Master Data
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{ url('admin/kabupaten') }}" class="nav-link @if(Request::segment(2) == 'kabupaten') active @endif">
            <i class="far fa-circle nav-icon"></i>
            <p>Kabupaten</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('admin/kecamatan') }}" class="nav-link @if(Request::segment(2) == 'kecamatan') active @endif">
            <i class="far fa-circle nav-icon"></i>
            <p>Kecamatan</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('admin/desa') }}" class="nav-link @if(Request::segment(2) == 'desa') active @endif">
            <i class="far fa-circle nav-icon"></i>
            <p>Desa / Kelurahan</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('admin/tps') }}" class="nav-link @if(Request::segment(2) == 'tps') active @endif">
            <i class="far fa-circle nav-icon"></i>
            <p>TPS</p>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item">
      <a href="{{ url('admin/partai') }}" class="nav-link @if(Request::segment(2) == 'partai') active @endif">
        <i class="fas fa-regular fa-flag nav-icon"></i>
        <p>Partai</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ url('admin/kandidat') }}" class="nav-link @if(Request::segment(2) == 'kandidat') active @endif">
        <i class="fas fa-user-tie nav-icon"></i>
        <p>Kandidat</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ url('admin/users') }}" class="nav-link @if(Request::segment(2) == 'users') active @endif">
        <i class="nav-icon fas fa-users"></i>
        <p>Users</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ url('admin/pemilih') }}" class="nav-link @if(Request::segment(2) == 'pemilih') active @endif">
        <i class="nav-icon fas fa-solid fa-person-booth"></i>
        <p>Pemilih Pasti</p>
      </a><i class="fa-solid fa-check-to-slot"></i>
    </li><i class="fa-light fa-boxes-stacked"></i>

    <li class="nav-item">
      <a href="{{ url('admin/suara') }}" class="nav-link  @if(Request::segment(2) == 'suara') active @endif">
        <i class="nav-icon fas fa-solid fa-box-tissue"></i>
        <p>Suara TPS</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ url('admin/rekap_suara') }}" class="nav-link  @if(Request::segment(2) == 'rekap_suara') active @endif">
        <i class="nav-icon fas fa-solid fa-box-tissue"></i>
        <p>Rekapitulasi Suara</p>
      </a>
    </li>
    @endif

    @if(auth()->user()->level == "kabupaten")
    <li class="nav-item @if(Request::segment(2) == 'kecamatan' || Request::segment(2) == 'desa' || Request::segment(2) == 'tps') menu-open @endif">
      <a href="#" class="nav-link @if(Request::segment(2) == 'kecamatan' || Request::segment(2) == 'desa' || Request::segment(2) == 'tps') active @endif">
        <i class="nav-icon fas fa-archive"></i>
        <p>
          Master Data
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{ url('kabupaten/kecamatan') }}" class="nav-link @if(Request::segment(2) == 'kecamatan') active @endif">
            <i class="far fa-circle nav-icon"></i>
            <p>Kecamatan</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('kabupaten/desa') }}" class="nav-link @if(Request::segment(2) == 'desa') active @endif">
            <i class="far fa-circle nav-icon"></i>
            <p>Desa / Kelurahan</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('kabupaten/tps') }}" class="nav-link @if(Request::segment(2) == 'tps') active @endif">
            <i class="far fa-circle nav-icon"></i>
            <p>TPS</p>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item">
      <a href="{{ url('kabupaten/users') }}" class="nav-link  @if(Request::segment(2) == 'users') active @endif">
        <i class="nav-icon fas users fa-users"></i>
        <p>Users</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ url('kabupaten/pemilih') }}" class="nav-link @if(Request::segment(2) == 'pemilih') active @endif">
        <i class="nav-icon fas fa-solid fa-person-booth"></i>
        <p>Pemilih Pasti</p>
      </a><i class="fa-solid fa-check-to-slot"></i>
    </li><i class="fa-light fa-boxes-stacked"></i>

    <li class="nav-item">
      <a href="{{ url('kabupaten/suara') }}" class="nav-link  @if(Request::segment(2) == 'suara') active @endif">
        <i class="nav-icon fas fa-solid fa-box-tissue"></i>
        <p>Suara TPS</p>
      </a>
    </li>
    @endif

    @if(auth()->user()->level == "kecamatan")
    <li class="nav-item @if(Request::segment(2) == 'kecamatan' || Request::segment(2) == 'desa' || Request::segment(2) == 'tps') menu-open @endif">
      <a href="#" class="nav-link @if(Request::segment(2) == 'kecamatan' || Request::segment(2) == 'desa' || Request::segment(2) == 'tps') active @endif">
        <i class="nav-icon fas fa-archive"></i>
        <p>
          Master Data
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{ url('kecamatan/desa') }}" class="nav-link @if(Request::segment(2) == 'desa') active @endif">
            <i class="far fa-circle nav-icon"></i>
            <p>Desa / Kelurahan</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('kecamatan/tps') }}" class="nav-link @if(Request::segment(2) == 'tps') active @endif">
            <i class="far fa-circle nav-icon"></i>
            <p>TPS</p>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item">
      <a href="{{ url('kecamatan/users') }}" class="nav-link  @if(Request::segment(2) == 'users') active @endif">
        <i class="nav-icon fas users fa-users"></i>
        <p>Users</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ url('kecamatan/suara') }}" class="nav-link  @if(Request::segment(2) == 'suara') active @endif">
        <i class="nav-icon fas fa-solid fa-box-tissue"></i>
        <p>Suara TPS</p>
      </a>
    </li>
    @endif

    @if(auth()->user()->level == "desa")
    <li class="nav-item @if( Request::segment(2) == 'tps') menu-open @endif">
      <a href="#" class="nav-link @if( Request::segment(2) == 'tps') active @endif">
        <i class="nav-icon fas fa-archive"></i>
        <p>
          Master Data
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{ url('desa/tps') }}" class="nav-link @if(Request::segment(2) == 'tps') active @endif">
            <i class="far fa-circle nav-icon"></i>
            <p>TPS</p>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item">
      <a href="{{ url('desa/users') }}" class="nav-link  @if(Request::segment(2) == 'users') active @endif">
        <i class="nav-icon fas users fa-users"></i>
        <p>Users</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ url('desa/suara') }}" class="nav-link  @if(Request::segment(2) == 'suara') active @endif">
        <i class="nav-icon fas fa-solid fa-box-tissue"></i>
        <p>Suara TPS</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ url('desa/pemilih') }}" class="nav-link @if(Request::segment(2) == 'pemilih') active @endif">
        <i class="nav-icon fas fa-solid fa-person-booth"></i>
        <p>Pemilih Pasti</p>
      </a>
    </li>
    @endif

    @if(auth()->user()->level == "tps")

    <li class="nav-item">
      <a href="{{ url('tps/suara') }}" class="nav-link  @if(Request::segment(2) == 'suara') active @endif">
        <i class="nav-icon fas fa-solid fa-box-tissue"></i>
        <p>Suara TPS</p>
      </a>
    </li>
    @endif
    <li class="nav-item">
      <a href="{{ url('change_photo') }}" class="nav-link  @if(Request::segment(1) == 'change_photo') active @endif">
        <i class="nav-icon fas fa-solid fa-image"></i>
        <p>Ganti Foto Profil</p>
      </a>
    </li>
  </ul>
</nav>