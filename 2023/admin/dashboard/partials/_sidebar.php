<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
    <h3 class="brand-logo">OH&nbsp;</h3>
    <h3 class="brand-logo-mini">2023</h3>
    <!-- <a class="sidebar-brand brand-logo" href="index.php"><img src="assets/images/logo.svg" alt="logo" /></a>
    <a class="sidebar-brand brand-logo-mini" href="index.php"><img src="assets/images/logo-mini.svg" alt="logo" /></a> -->
  </div>
  <ul class="nav">
    <!-- <li class="nav-item profile">
      <div class="profile-desc">
        <div class="profile-pic">
          <div class="count-indicator">
            <img class="img-xs rounded-circle " src="assets/images/faces/face15.jpg" alt="">
            <span class="count bg-success"></span>
          </div>
          <div class="profile-name">
            <h5 class="mb-0 font-weight-normal">Henry Klein</h5>
            <span>Gold Member</span>
          </div>
        </div>
      </div>
    </li> -->
    <li class="nav-item nav-category">
      <span class="nav-link">Menu</span>
    </li>
    <!-- <li class="nav-item menu-items">
      <a class="nav-link" href="./index.php">
        <span class="menu-icon">
          <i class="mdi mdi-speedometer"></i>
        </span>
        <span class="menu-title">Dashboard</span>
      </a>
    </li> -->


    <?php 
      if($_SESSION['kategori']=="lk"){
        echo '<li class="nav-item menu-items">
          <a class="nav-link" href="./keteranganLK.php">
            <span class="menu-icon">
              <i class="mdi mdi-format-list-bulleted"></i>
            </span>
            <span class="menu-title">Keterangan LK</span>
          </a>
        </li>';
      }else if($_SESSION['kategori']=="ukm"){
        // echo '
      //   <li class="nav-item menu-items">
      //   <a class="nav-link" href="./keteranganUKM.php">
      //     <span class="menu-icon">
      //       <i class="mdi mdi-format-list-bulleted"></i>
      //     </span>
      //     <span class="menu-title">Keterangan UKM</span>
      //   </a>
      // </li>

      // <li class="nav-item menu-items">
      //   <a class="nav-link" href="./pertanyaanUKM.php">
      //     <span class="menu-icon">
      //       <i class="mdi mdi-format-list-bulleted"></i>
      //     </span>
      //     <span class="menu-title">Pertanyaan UKM</span>
      //   </a>
      // </li>';

      echo'<li class="nav-item menu-items">
        <a class="nav-link" href="./newsOH.php">
          <span class="menu-icon">
            <i class="mdi mdi-format-list-bulleted"></i>
          </span>
          <span class="menu-title">News</span>
        </a>
      </li>

      <li class="nav-item menu-items">
        <a class="nav-link" href="./list_pendaftarUKM.php">
          <span class="menu-icon">
            <i class="mdi mdi-format-list-bulleted"></i>
          </span>
          <span class="menu-title">List Pendaftar</span>
        </a>
      </li>';
      }
      if ($_SESSION['kategori']=='ukm'||$_SESSION['kategori']=='lk'){
        echo '<li class="nav-item menu-items">
        <a class="nav-link" href="./ganti_password.php">
          <span class="menu-icon">
            <i class="mdi mdi-format-list-bulleted"></i>
          </span>
          <span class="menu-title">Ganti Password</span>
        </a>
      </li>';
      
      }

      
      
      if ($_SESSION['kategori']=='panitia'){
        echo '<li class="nav-item menu-items">
        <a class="nav-link" href="./check_news.php">
          <span class="menu-icon">
            <i class="mdi mdi-format-list-bulleted"></i>
          </span>
          <span class="menu-title">Check News</span>
        </a>
      </li>';
      }
    ?>

    <!-- <li class="nav-item menu-items">
        <a class="nav-link" href="./give_point.php">
          <span class="menu-icon">
            <i class="mdi mdi-format-list-bulleted"></i>
          </span>
          <span class="menu-title">Give Point</span>
        </a>
      </li> -->
      
      
    <li class="nav-item menu-items">
      <a class="nav-link" href="../api/logout.php">
        <span class="menu-icon">
          <i class="mdi mdi-logout"></i>
        </span>
        <span class="menu-title">Logout</span>
      </a>
    </li>


    <!-- <li class="nav-item menu-items">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <span class="menu-icon">
          <i class="mdi mdi-laptop"></i>
        </span>
        <span class="menu-title">Basic UI Elements</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/ui-features/buttons.html">Buttons</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/ui-features/dropdowns.html">Dropdowns</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/ui-features/typography.html">Typography</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="pages/forms/basic_elements.html">
        <span class="menu-icon">
          <i class="mdi mdi-playlist-play"></i>
        </span>
        <span class="menu-title">Form Elements</span>
      </a>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="pages/tables/basic-table.html">
        <span class="menu-icon">
          <i class="mdi mdi-table-large"></i>
        </span>
        <span class="menu-title">Tables</span>
      </a>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="pages/charts/chartjs.html">
        <span class="menu-icon">
          <i class="mdi mdi-chart-bar"></i>
        </span>
        <span class="menu-title">Charts</span>
      </a>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="pages/icons/mdi.html">
        <span class="menu-icon">
          <i class="mdi mdi-contacts"></i>
        </span>
        <span class="menu-title">Icons</span>
      </a>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
        <span class="menu-icon">
          <i class="mdi mdi-security"></i>
        </span>
        <span class="menu-title">User Pages</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="auth">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/samples/blank-page.html"> Blank Page </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html"> 404 </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> 500 </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Login </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Register </a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="https://www.bootstrapdash.com/demo/corona-free/jquery/documentation/documentation.html">
        <span class="menu-icon">
          <i class="mdi mdi-file-document-box"></i>
        </span>
        <span class="menu-title">Documentation</span>
      </a>
    </li> -->
  </ul>
</nav>