<style type="text/css">
.font {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
}
.isi {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
}
table[border="1"] {
  border-collapse:collapse;
  font:normal normal 11px Verdana,Arial,Sans-Serif;
  color:#0000000;
}
.hed {
	font-size: 14px;
}
.hed {
	font-family: Arial, Helvetica, sans-serif;
	text-align:center;
}
.hed {
	font-size: 14px;
}
</style>
<?php
session_start();
include "../includes/config.php";
?>
<title>Rekapitulasi Tahun <?php echo $_SESSION['tahun'];?></title>
<?php
$sql = $conn->query("SELECT nmbidang FROM m_bidang WHERE idbidang  = '$_SESSION[bidang]'");
$a = $sql->fetch_assoc();
?>
<p class="hed"><strong>LAPORAN PROGRAM DAN KEGIATAN<br>
  BADAN KEPEGAWAIAN PENDIDIKAN DAN PELATIHAN<br>
  KABUPATEN ROKAN HULU TAHUN <?php echo $_SESSION['tahun'];?>
</strong></p><br>
<table width="100%" border="1" cellpadding="5" class="font">
  <thead>
	<tr>
	  <th height="43" colspan="2">NAMA PROGRAM / KEGIATAN</th>
	  <th>LOKASI</th>
	  <th>SUMBER DANA</th>
	  <th>BIDANG</th>
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
					WHERE a.tahun_keg = '$tahun'
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
	  <td style="width:2%">-</td>
	  <td><?php echo $r['nmkegiatan'];?></td>
	  <td><?php echo $r['lokasi'];?></td>
	  <td><?php echo $r['sdana'];?></td>
	  <td><?php echo $r['nmbidang'];?></td>
	 </tr>
  <?php } ?>
  </tbody>
</table>
<script type="text/javascript">
window.print();
window.onfocus=function(){ window.close();}
</script>