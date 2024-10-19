<?php
if(isset($_GET['hapus'])){
	$id = $_GET['hapus'];
	$sql = $conn->query("DELETE FROM m_training WHERE idtraining='$id'");
	?>
		<script>
			document.location = "index.php?page=data-latih";
		</script>
	<?php
}elseif(isset($_POST['id'])){
	include "../includes/config.php";
	$id = $_POST['id'];
	$nama = $_POST['nama'];
	$sentimen = $_POST['sentimen'];

	if($id == ""){
		$cek = $conn->query("SELECT idtraining FROM m_training");
		$row = $cek->num_rows;
		if($row == 3){
			?>
			<script>
				alert("Maaf, Data Latih dibatasi hanya 3.");
			</script>
			<?php
		}else{
			$simpan = $conn->query("INSERT INTO m_training (idsentimen,nmtraining) VALUES ('$sentimen','$nama')");
		}
		
	}else{
		$ubah = $conn->query("UPDATE m_training SET idsentimen='$sentimen',nmtraining='$nama' WHERE idtraining='$id'");
	}
	
}else{
?>
<div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><small>Master Data Training</small></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tambah">Tambah Data</a></li>
              
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
                <h5 class="card-title m-0">Daftar Seluruh Data Training</h5>
              </div>
              <div class="card-body">
                <table class="tabledata table-striped">
                  <thead>
                    <tr>
                      <th style="width: 10px">No</th>
                      <th>Uraian Keluhan</th>
					  <th style="width: 15%">Klasifikasi </th>
                      <th style="width: 10%">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
				  $no=0;
				  $sql=$conn->query("SELECT * FROM m_training, m_sentimen WHERE m_training.idsentimen = m_sentimen.idsentimen");
				  while($r = $sql->fetch_assoc()){ $no++;
				  ?>
                    <tr>
                      <td><?php echo $no;?>.</td>
                      <td><?php echo $r['nmtraining'];?></td>
					  <td><?php echo $r['nmsentimen'];?></td>
                      <td><a href="#" class="edit" data-toggle="modal" data-target="#tambah" 
					  data-id="<?php echo $r['idtraining'];?>"  
					  data-nama="<?php echo $r['nmtraining'];?>"
					  data-sentimen="<?php echo $r['idsentimen'];?>">
					  <span class="badge bg-warning">Ubah</span></a> <a href="index.php?page=data-latih&hapus=<?php echo $r['idtraining'];?>" onclick="return confirm('Data Akan dihapus, Lanjutkan ?')"><span class="badge bg-danger">Hapus</span></a></td>
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
              <h4 class="modal-title">Formulir Data Training</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
				<div class="form-group">
                    <label>Uraian Keluhan</label>
						<input type="hidden" id="id" name="id">
                        <textarea class="form-control" id="nama" name="nama" required></textarea>
                </div>
				<div class="form-group">
                    <label>Masukan Klasifikasi</label>
                        <select class="form-control" id="sentimen" name="sentimen" required>
							<option value="0">Tidak Ada</option>
							<?php
							$sql = $conn->query("SELECT * FROM m_sentimen");
							while($r = $sql->fetch_assoc()){?>
							<option value="<?php echo $r['idsentimen'];?>"><?php echo $r['nmsentimen'];?></option>
							<?php } ?>
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
        	url: "view/data-latih.php",
			type: "POST",
			data:  new FormData(this),
			contentType: false,
    	    cache: false,
			processData:false,
			success: function(data)
				{
					document.location = "index.php?page=data-latih";
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
	var sentimen	   = $(this).data('sentimen');
	
	$(".modal-body #id").val( id );
	$(".modal-body #nama").val( nama );
	$(".modal-body #sentimen").val( sentimen );
	
});
</script>
<?php }?>