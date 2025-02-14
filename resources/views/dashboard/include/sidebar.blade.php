<aside class="main-sidebar">
  <!-- sidebar-->
  <section class="sidebar"> 
  
    <!-- sidebar menu-->
  <ul class="sidebar-menu" data-widget="tree">    
      <li class="@if($category_name == "Dashboard") active @endif">
        <a href="{{ url('/dashboard') }}">
          <i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>
        <span>Dashboard</span>
        </a>
      </li>

      {{-- <li class="@if($category_name == "Negara Asal") active @endif">
        <a href="{{ url('/negara-asal') }}">
          <i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>
        <span>Negara Asal</span>
        </a>
      </li> --}}

      @if(Auth::user()->level_user_id == 1)
      <li class="header">Pengaturan</li>
          <li class="treeview">
            <a href="#">
              <i class="icon-Chat-check"><span class="path1"></span><span class="path2"></span></i>
              <span>Pengaturan User</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-right pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">

              <li class="@if($category_name == "Level User") active @endif">
                <a href="{{ url('/dashboard/level-user') }}">
                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                    <span>Level User</span>
                </a>
              </li>
      
              <li class="@if($category_name == "User") active @endif">
                <a href="{{ url('/dashboard/user') }}">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span>User</span>
                </a>
              </li>

            </ul>
          </li>

          <li class="header">Data Master</li>
          <li class="treeview">
            <a href="#">
              <i class="icon-Layout-grid"><span class="path1"></span><span class="path2"></span></i>
              <span>Data Master</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-right pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
            <li class="@if($category_name == "PMA") active @endif">
              <a href="{{ url('/dashboard/pengaduan') }}">
                  <i class="fa fa-server" aria-hidden="true"></i>
                  <span> Pengaduan</span>
              </a>
            </li>
            <li class="@if($category_name == "PMA") active @endif">
              <a href="{{ url('/dashboard/form-pma') }}">
                  <i class="fa fa-book" aria-hidden="true"></i>
                  <span> Kategori Pengaduan</span>
              </a>
            </li>           
            </ul>
          </li>


          <li class="header">Laporan </li>
          <li class="@if($category_name == "Lokasi") active @endif">
            <a href="{{ url('/lokasi') }}">
              <i class="fa fa-file"><span class="path1"></span><span class="path2"></span></i>
            <span>Laporan </span>
            </a>
          </li>

          <li class="header">Profile</li>
 
            <li class="@if($category_name == "PMA") active @endif">
              <a href="{{ url('/dashboard/form-pma') }}">
                  <i class="fa fa-user" aria-hidden="true"></i>
                  <span> Profile</span>
              </a>
            </li>
            <li class="@if($category_name == "PMA") active @endif">
              <a href="{{ url('/dashboard/form-pma') }}">
                  <i class="ti-lock" aria-hidden="true"></i>
                  <span> Logout</span>
              </a>
            </li>    

          @endif
         

    </ul>
  </section>

</aside>
