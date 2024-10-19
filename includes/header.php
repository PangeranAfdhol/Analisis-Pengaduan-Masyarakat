<div class="container">
      <a href="./" class="navbar-brand">
        <img src="dist/img/logo1.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">DESA TANDUN</span>
      </a>

      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="./" class="nav-link">Home</a>
          </li>
		  <?php
		  if($level == "Admin"){ ?>
		  <li class="nav-item dropdown">
			<a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Data Master</a>
			<ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
			
			  <li><a href="index.php?page=stopword" class="dropdown-item">Data Stopword</a></li>
			  <li><a href="index.php?page=data-klasifikasi" class="dropdown-item">Data Klasifikasi</a></li>
			  <li><a href="index.php?page=data-latih" class="dropdown-item">Data Training</a></li>
			  <li class="dropdown-divider"></li>
			  <li><a href="index.php?page=pengguna" class="dropdown-item">Daftar Pengguna</a></li>
			</ul>
		  </li>
		  <li class="nav-item dropdown">
			<a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Opsi Pengembang</a>
			<ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
			
			  <li><a href="index.php?page=simulasi" class="dropdown-item">Simulasi Rumus</a></li>
			</ul>
		  </li>
		  
		  <?php }else{ ?>
		  <li class="nav-item">
            <a href="index.php?page=tentang" class="nav-link"></a>
          </li>
		  
		  <?php } ?>
        </ul>

        
      </div>

      <!-- Right navbar links -->
      <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link" href="includes/logout.php">
            <i class="fas fa-user"></i> Selamat Datang, <?php echo $_SESSION['nama'];?> - Logout
          </a>
          
        </li>
       
      </ul>
    </div>