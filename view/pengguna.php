<?php
if(isset($_GET['hapus'])){
	$id = $_GET['hapus'];
	$sql = $conn->query("DELETE FROM m_user WHERE idpengguna='$id'");
	?>
		<script>
			document.location = "index.php?page=pengguna";
		</script>
	<?php
}elseif(isset($_POST['id'])){
	include "../includes/config.php";
	$id = $_POST['id'];
	$nama = $_POST['nama'];
	$pass = $_POST['password'];
	$email = $_POST['email'];
	$level = $_POST['level'];
	$cari = $conn->query("SELECT * FROM m_user WHERE idpengguna = '$id'");
	$ada = $cari->num_rows;
	if($ada > 0){
		$ubah = $conn->query("UPDATE m_user SET nama='$nama',password='$pass',email='$email',level='$level' WHERE idpengguna='$id'");
	}else{
		$simpan = $conn->query("INSERT INTO m_user (idpengguna,nama,password,email,level) VALUES ('$id','$nama','$pass','$email','$level')");
	}
	
}else{
?>
<div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"> Desa Tandun <small> - Pelayanan Masyarakat </small></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tambah">Tambah Data Pengguna</a></li>
              
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container">
        <div class="row">

          <!-- /.col-md-6 -->
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title m-0">Daftar Seluruh Pengguna</h5>
              </div>
              <div class="card-body">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th style="width: 10px">No</th>
                      <th>Nama Pengguna</th>
					  <th>Email </th>
					  <th>Level Pengguna</th>
                      <th style="width: 10%">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
				  $no=0;
				  $sql=$conn->query("SELECT * FROM m_user");
				  while($r = $sql->fetch_assoc()){ $no++;
				  ?>
                    <tr>
                      <td><?php echo $no;?>.</td>
                      <td><?php echo $r['nama'];?></td>
					  <td><?php echo $r['email'];?></td>
					  <td><?php echo $r['level'];?></td>
                      <td><a href="#" class="edit" data-toggle="modal" data-target="#tambah" 
					  data-id="<?php echo $r['idpengguna'];?>"  
					  data-nama="<?php echo $r['nama'];?>"
					  data-email="<?php echo $r['email'];?>"
					  data-level="<?php echo $r['level'];?>">
					  
					  <span class="badge bg-warning">Ubah</span></a> <a href="index.php?page=pengguna&hapus=<?php echo $r['idpengguna'];?>" onclick="return confirm('Data Akan dihapus, Lanjutkan ?')"><span class="badge bg-danger">Hapus</span></a></td>
                    </tr>
				  <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>

            
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
	
	<form id="form">
	 <div class="modal fade" id="tambah">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Formulir Pengguna</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
				<div class="form-group">
                    <label>ID Pengguna</label>
                        <input type="text" class="form-control" id="id" name="id" placeholder="Masukkan ID Pengguna" required>
                </div>
				<div class="form-group">
                    <label>Nama Pengguna</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama Pengguna" required>
                </div>
				<div class="form-group">
                    <label>Email Pengguna</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email Pengguna" required>
                </div>
				<div class="form-group">
                    <label>Password Pengguna</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password Pengguna" required>
                </div>
				<div class="form-group">
                    <label>Level Pengguna</label>
                        <select class="form-control" id="level" name="level" required>
							<option value="Admin">Administrator</option>
							<option value="Staff">Staff</option>
						</select>
                </div>
				
            </div>
            <div class="modal-footer">
			  <button type="submit" class="btn btn-primary">Simpan Data</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Keluar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
	  </form>
	  
<script src="dist/js/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
   $(document).ready(function (e) {
	$("#form").on('submit',(function(e) {
		e.preventDefault();
		$.ajax({
        	url: "view/pengguna.php",
			type: "POST",
			data:  new FormData(this),
			contentType: false,
    	    cache: false,
			processData:false,
			success: function(data)
				{
					document.location = "index.php?page=pengguna";
				},
		  	error: function() 
				{
				} 	        
	   });
	}));
});

$(document).on("click", ".edit", function () {

	var id             = $(this).data('id');
	var nama		   = $(this).data('nama');
	var email		   = $(this).data('email');
	var level		   = $(this).data('level');
	var bidang		   = $(this).data('bidang');
	
	$(".modal-body #id").val( id );
	$(".modal-body #nama").val( nama );
	$(".modal-body #email").val( email );
	$(".modal-body #level").val( level );
	$(".modal-body #bidang").val( bidang );
	
});
</script>
<?php }?>