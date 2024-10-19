<?php
if(isset($_GET['hapus'])){
	$id = $_GET['hapus'];
	$sql = $conn->query("DELETE FROM p_kegiatan WHERE idkegiatan='$id'");
	?>
		<script>
			document.location = "index.php?page=proses";
		</script>
	<?php
}elseif(isset($_POST['id'])){
	include "../includes/config.php";
	session_start();
	$id = $_POST['id'];
	$nama = $_POST['nama'];
	$program = $_POST['program'];
	$lokasi = $_POST['lokasi'];
	$dana = $_POST['dana'];
	$bidang = $_SESSION['bidang'];
	$tahun = $_SESSION['tahun'];
	
	$cari = $conn->query("SELECT * FROM p_kegiatan WHERE idkegiatan = '$id'");
	$ada = $cari->num_rows;
	if($ada > 0){
		$ubah = $conn->query("UPDATE p_kegiatan SET nmkegiatan='$nama',idprogram='$program',lokasi='$lokasi',sdana='$dana',idbidang='$bidang',tahun_keg='$tahun' WHERE idkegiatan='$id'");
	}else{
		$simpan = $conn->query("INSERT INTO p_kegiatan (nmkegiatan,idprogram,lokasi,sdana,idbidang,tahun_keg) VALUES ('$nama','$program','$lokasi','$dana','$bidang','$tahun')");
	}
	
}else{
$sql = $conn->query("SELECT nmbidang FROM m_bidang WHERE idbidang  = '$_SESSION[bidang]'");
$a = $sql->fetch_assoc();
?>
<div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"> Pelayanan <small>Desa Tantun</small></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tambah">Tambah Kegiatan Tahun <?php echo $_SESSION['tahun'];?></a></li>
              <li class="breadcrumb-item"><a href="print/bidang.php" target="_blank" class="btn btn-primary btn-sm">Cetak Data</a></li>
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
                <h5 class="card-title m-0">Daftar Seluruh Kegiatan <?php echo $a['nmbidang'];?> Tahun <?php echo $_SESSION['tahun'];?></h5>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th colspan="2">NAMA PROGRAM / KEGIATAN</th>
					  <th>LOKASI</th>
					  <th>SUMBER DANA</th>
                      <th style="width: 10%">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
				  $no=0;
				  $inya = '';
				  $jml_per_jenjang =0;
				  $tahun = $_SESSION['tahun'];
				  $sql=$conn->query("SELECT a.idkegiatan, a.nmkegiatan, a.lokasi, a.sdana, b.idprogram, b.nmprogram, c.idbidang, c.nmbidang FROM p_kegiatan a
									JOIN m_program b ON a.idprogram = b.idprogram
									JOIN m_bidang c ON a.idbidang = c.idbidang
									WHERE a.tahun_keg = '$tahun' AND c.idbidang = '$_SESSION[bidang]' 
									GROUP BY a.idkegiatan, b.idprogram
									ORDER BY b.idprogram, a.idkegiatan ASC");
				  while($r = $sql->fetch_assoc()){ 
				  $idprogram = $r['idprogram'];
				  if($inya != $r['idprogram'])
					{
					?>
					<tr>
                      <td colspan="2"><b>Program : <?php echo $r['nmprogram'];?></b></td>
					  <td></td>
                      <td></td>
					  <td></td>
                    </tr>
					<?php		
					$inya=$r['idprogram']; 
					}
				  ?>
                    <tr>
                      <td style="width:1%">-</td>
                      <td><?php echo $r['nmkegiatan'];?></td>
					  <td><?php echo $r['lokasi'];?></td>
                      <td><?php echo $r['sdana'];?></td>
					  <td><a href="#" class="edit" data-toggle="modal" data-target="#tambah" 
					  data-id="<?php echo $r['idkegiatan'];?>"  
					  data-nama="<?php echo $r['nmkegiatan'];?>"
					  data-lokasi="<?php echo $r['lokasi'];?>"
					  data-dana="<?php echo $r['sdana'];?>"
					  data-program="<?php echo $r['idprogram'];?>">
					  <span class="badge bg-warning">Ubah</span></a> <a href="index.php?page=proses&hapus=<?php echo $r['idkegiatan'];?>" onclick="return confirm('Data Akan dihapus, Lanjutkan ?')"><span class="badge bg-danger">Hapus</span></a></td>
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
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Formulir Kegiatan <?php echo $a['nmbidang'];?></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
				<div class="form-group">
                    <label>Nama Kegiatan</label>
						<input type="hidden" name="id" id="id">
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama Kegiatan" required>
                </div>
				<div class="form-group">
                    <label>Pilih Program</label>
                        <select class="form-control" id="program" name="program" required>
							<option value="">-- Pilih --</option>
							<?php
							$sql = $conn->query("SELECT * FROM m_program WHERE tahun_program='$_SESSION[tahun]'");
							while($r = $sql->fetch_assoc()){?>
							<option value="<?php echo $r['idprogram'];?>"><?php echo $r['nmprogram'];?></option>
							<?php } ?>
						</select>
                </div>
				<div class="form-group">
                    <label>Lokasi</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Masukkan Lokasi" required>
                </div>
				<div class="form-group">
                    <label>Sumber Dana</label>
                        <input type="text" class="form-control" id="dana" name="dana" placeholder="Sumber Dana" required>
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
        	url: "view/proses.php",
			type: "POST",
			data:  new FormData(this),
			contentType: false,
    	    cache: false,
			processData:false,
			success: function(data)
				{
					document.location = "index.php?page=proses";
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
	var lokasi		   = $(this).data('lokasi');
	var dana		   = $(this).data('dana');
	var program		   = $(this).data('program');
	
	
	$(".modal-body #id").val( id );
	$(".modal-body #nama").val( nama );
	$(".modal-body #lokasi").val( lokasi );
	$(".modal-body #dana").val( dana );
	$(".modal-body #program").val( program );
	
});
</script>
<?php }?>