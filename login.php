<?php
session_start();
include "includes/config.php";
if(isset($_POST['userid'])){
	if($_POST["vercode"] != $_SESSION["vercode"] OR $_SESSION["vercode"]=='')  {
			?>
			<script>
				alert("Kode Keamanan Salah !");
				document.location="login.php";
			</script>
			<?php
		} else {
		$userid = $_POST['userid'];
		$passwd = $_POST['password'];
		
		$sql = $conn->query("SELECT * FROM m_user WHERE idpengguna = '$userid' AND password = '$passwd'");
		$cek = $sql->num_rows;
			if($cek > 0){
				$r = $sql->fetch_assoc();
				$_SESSION['nama'] = $r['nama'];
				$_SESSION['user'] = $r['idpengguna'];
				$_SESSION['level'] = $r['level'];
				
				header('location:index.php');
			}else{
				?>
				<script>
					alert("User dan Password Tidak di Temukan");
				</script>
				<?php
			}	
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pelayanan Masyarakat | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <img src="dist/img/logo.png" style="margin-left: -4rem;margin-bottom: -6rem;
margin-top: -8rem;"></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <form method="post" action="login.php">
        <div class="input-group mb-3">
          <input type="text" name="userid" class="form-control" placeholder="ID Pengguna">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
		<div class="form-group">
			<img src="includes/captcha.php" alt="gambar" />
		</div>
		<div class="input-group mb-3">
          <input type="text" name="vercode" class="form-control" placeholder="Kode Keamanan">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-key"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Log In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
