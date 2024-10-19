<?php
session_start();
//error_reporting(0);
if(isset($_SESSION['user'])){
	$level = $_SESSION['level'];
	?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Analisis Pengaduan Msyarakat</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="dist/css/jquery.dataTables.min.css">
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md navbar-light navbar-teal">
    <?php
		include "includes/header.php";
	?>
  </nav>
  <!-- /.navbar -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<?php
	include "includes/config.php";
	if(isset($_REQUEST['page'])){
		$page = $_REQUEST['page'].".php";
		$path = "view/".$page;
		if(file_exists($path)) {
			include "view/".$page;
		}
	}else{
		include "view/home.php";
	}
	?>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
	<div class="container">
		<div class="float-right d-none d-sm-inline">
		  Pelayanan Pengaduan Masyarakat
		</div>
		<!-- Default to the left -->
		<strong>Copyright &copy; 2023  <a href="https://www.instagram.com/whywhyyyyy_/?hl=id">Abu Bakar</a></strong></br>
	</div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script>
$(function () {
    $('.tabledata').DataTable();
  });
</script>
</body>
</html>
<?php }else{ ?>
<script>
document.location = "login.php";
</script>
<?php } ?>