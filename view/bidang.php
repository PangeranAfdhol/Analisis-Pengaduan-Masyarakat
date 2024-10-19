<?php
if(isset($_GET['hapus'])){
	$id = $_GET['hapus'];
	$sql = $conn->query("DELETE FROM m_bidang WHERE idbidang='$id'");
	?>
		<script>
			document.location = "index.php?page=bidang";
		</script>
	<?php
}elseif(isset($_POST['id'])){
	include "../includes/config.php";
	$id = $_POST['id'];
	$nama = $_POST['nama'];
	$cari = $conn->query("SELECT * FROM m_bidang WHERE idbidang = '$id'");
	$ada = $cari->num_rows;
	if($ada > 0){
		$ubah = $conn->query("UPDATE m_bidang SET nmbidang='$nama' WHERE idbidang='$id'");
	}else{
		$simpan = $conn->query("INSERT INTO m_bidang (nmbidang) VALUES ('$nama')");
	}
	
}else{
?>
<div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Layanan Masyarakat <small>Desa Tandun</small></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tambah">Tambah Data Bidang</a></li>
              
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
                <h5 class="card-title m-0">Daftar Seluruh Bidang</h5>
              </div>
              <div class="card-body">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th style="width: 10px">No</th>
                      <th>Nama Bidang</th>
                      <th style="width: 10%">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
				  $no=0;
				  $sql=$conn->query("SELECT * FROM m_bidang");
				  while($r = $sql->fetch_assoc()){ $no++;
				  ?>
                    <tr>
                      <td><?php echo $no;?>.</td>
                      <td><?php echo $r['nmbidang'];?></td>
                      <td><a href="#" class="edit" data-toggle="modal" data-target="#tambah" 
					  data-id="<?php echo $r['idbidang'];?>"  
					  data-nama="<?php echo $r['nmbidang'];?>">
					  <span class="badge bg-warning">Ubah</span></a> <a href="index.php?page=bidang&hapus=<?php echo $r['idbidang'];?>" onclick="return confirm('Data Akan dihapus, Lanjutkan ?')"><span class="badge bg-danger">Hapus</span></a></td>
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
              <h4 class="modal-title">Formulir Bidang</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
				<div class="form-group">
                    <label>Nama Bidang</label>
						<input type="hidden" name="id" id="id">
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama Bidang">
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
        	url: "view/bidang.php",
			type: "POST",
			data:  new FormData(this),
			contentType: false,
    	    cache: false,
			processData:false,
			success: function(data)
				{
					document.location = "index.php?page=bidang";
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
	
	$(".modal-body #id").val( id );
	$(".modal-body #nama").val( nama );
	
});
</script>
<?php }?>