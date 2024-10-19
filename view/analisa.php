 <?php
 //Fungsi Stemming
include "includes/stemming2.php";
?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1 class="m-0 text-center"><small>Halaman Perhitungan Data Testing</small></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
	
	<form action="index.php" method="get">
	<input type="hidden" name="page" value="hitung2">
	<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
	<input type="hidden" name="keluhan" value="<?php echo $_GET['keluhan'];?>">
    <div class="content">
      <div class="container">
        <div class="row">
          
          <!-- /.col-md-6 -->
		  
         <div class="col-lg-12">
			<div class="card">
              <div class="card-header">
                <h5 class="card-title m-0"><em><b>1. Keluhan Masyarakat</b></em></h5>
              </div>
              <div class="card-body">
			  <table class="table table-bordered" width="100%">
				<thead>
					<tr>
						<th width="5%">Dokumen</th>
						<th>Keluhan</th>
						<th width="10%">Klasifikasi</th>
					</tr>
				</thead>
				<tbody>
				<?php
				
				$kalimat1 = strtolower($_GET['keluhan']);
				//Proses Untuk Data Testing
				$sql5 = $conn->query("SELECT * FROM m_stopword");
					while ($row5 = $sql5->fetch_array()) {
						$stopword1[] = trim($row5['stopword']);
					}
				$kata1 = explode(" ", $kalimat1);
				$jkata1 = count($kata1) - 1;
				for ($i = 0; $i <= $jkata1; $i++) {
					if (in_array($kata1[$i], $stopword1)) {
						unset($kata1[$i]);
					}
				}


				$kalimatbaru1 = implode(" ", $kata1);

				//Proses Stemming
				$vstem1 = explode(" ",$kalimatbaru1);
				$juml1 = count($vstem1) - 1;
				for ($i = 0; $i <= $juml1; $i++) {
					$hasilstem1[] = stemming($vstem1[$i]);
				}
				$hstem1 = implode(" ", $hasilstem1);
				
				$no = 0;
				$delete = $conn->query("DELETE FROM p_filtertraining");
				$deletejarak = $conn->query("DELETE FROM p_jarak");
				$sql = $conn->query("SELECT a.idtraining, a.nmtraining, b.idsentimen, b.nmsentimen FROM m_training a INNER JOIN m_sentimen b ON a.idsentimen = b.idsentimen WHERE a.nmtraining RLIKE 'kartu|uang|".implode("|",$hasilstem1)."' GROUP BY b.idsentimen ORDER BY RAND() LIMIT 3");
				while($r=$sql->fetch_assoc()){ 
				$no++;
				
				?>
					<tr>
						<td>D<?php echo $no;?></td>
						<td><?php echo $r['nmtraining'];?></td>
						<td><?php echo $r['nmsentimen'];?></td>
					</tr>
				<?php 
				$simpanfilter = $conn->query("INSERT INTO p_filtertraining (idtraining,nmtraining,idsentimen) VALUES ('$r[idtraining]','$r[nmtraining]','$r[idsentimen]')");
				$simpanjarak = $conn->query("INSERT INTO p_jarak (idtraining) VALUES ('$r[idtraining]')");
				} ?>
					<tr>
						<th width="5%">Dokumen</th>
						<th width="48%">Keluhan</th>
						<th>Klasifikasi</th>
					</tr>
					<tr>
						<td>D4</td>
						<td><?php echo $_GET['keluhan'];?></td>
						<td>???</td>
					</tr>
				</tbody>
			  </table>
			  </div>
            </div>	
			<!-- PROSES PERHITUNGAN DATA TRAINING ---------------------------------------------------------------------------------------------------------------------------------------->
			
			<!-- 1. Proses Stopword -->
			<?php
			$sql = $conn->query("SELECT * FROM p_filtertraining, m_sentimen WHERE p_filtertraining.idsentimen = m_sentimen.idsentimen ORDER BY idfilter ASC");
			while($r=$sql->fetch_assoc()){ 
				$kalimat = strtolower($r['nmtraining']);
				
					$sql2 = $conn->query("SELECT * FROM m_stopword");
					while ($row = $sql2->fetch_array()) {
						$stopword[] = trim($row['stopword']);
					}
					$pieces = explode(" ", $kalimat);
					$jml = count($pieces) - 1;
					for ($i = 0; $i <= $jml; $i++) {
						if (in_array($pieces[$i], $stopword)) {
							unset($pieces[$i]);
						}
					}
					$removal = implode(" ", $pieces);
					$no++;
			
			$removal3[] = strtolower(trim($removal));
			}		
					
			// 2. Proses Stemming
			
			$katadasar = array();
				$sql = $conn->query("SELECT * FROM m_katadasar");
				while($kd = $sql->fetch_assoc()){
					$katadasar[] = trim($kd['katadasar']);
				}

				$no = 0;
				
				$kalimatbaru = $removal3;
				$juml = count($kalimatbaru) - 1;
				
				for ($i = 0; $i <= $juml; $i++) {
					
					$stem = explode(" ",trim($kalimatbaru[$i]));
					$juml2 = count($stem) - 1;
					$hasilstem = array();
					for ($a = 0; $a <= $juml2; $a++) {
						$hasilstem[] = stemming($stem[$a]);
					}
					$rr = implode(" ",array_filter($hasilstem));					
					$no++;
					
					$token[] = $hasilstem; 
					} 
					
			// 3. Proses Tokenizing
				$no = 0;
				$atoken = array_values(array_filter(array_map('array_filter',$token)));
				$ktoken = array_map('array_values', $atoken);
				
				$juml3 = count($ktoken) - 1;
				$tfkata = array();
				$tfkatanya = array();
				$sql = $conn->query("DELETE FROM p_pembobotan");
				for ($i = 0; $i <= $juml3; $i++) { $no++;
				  $sql = $conn->query("INSERT INTO p_pembobotan (idtraining) VALUES ('$no')");
				  for ($col = 0; $col < count($ktoken[$i]); $col++) {
					$tfkata[$i][] = $ktoken[$i][$col];
					$tfkatanya[] = $ktoken[$i][$col];
				  }
				}
				
				$termd1 = implode(",",$tfkata[0]);
				$termd2 = implode(",",$tfkata[1]);
				$termd3 = implode(",",$tfkata[2]);
				
				$jmlkata1 = str_word_count($termd1);
				$jmlkata2 = str_word_count($termd2);
				$jmlkata3 = str_word_count($termd3);
				
				$katanya = implode(",",$tfkatanya);
				
				$sql2 = $conn->query("UPDATE p_pembobotan SET term='$termd1', jmlkata='$jmlkata1' WHERE idtraining = '1'");
				$sql2 = $conn->query("UPDATE p_pembobotan SET term='$termd2', jmlkata='$jmlkata2' WHERE idtraining = '2'");
				$sql2 = $conn->query("UPDATE p_pembobotan SET term='$termd3', jmlkata='$jmlkata3' WHERE idtraining = '3'");

			?>
						
			<!-- PROSES PERHITUNGAN DATA TRAINING ---------------------------------------------------------------------------------------------------------------------------------------->
			
			<div class="card">
              <div class="card-header">
                <h5 class="card-title m-0"><em><b>2. Hasil Preprocessing Dokumen Testing (Stopword + Stemming)</b></em></h5>
              </div>
              <div class="card-body">
			  <table class="table table-bordered" width="100%">
				<thead>
					<tr>
						<th width="5%">Dokumen</th>
						<th>Keluhan</th>
						<th width="10%">Klasifikasi</th>
					</tr>
				</thead>
				<tbody>
				<?php
				//Proses Stopword dari database
				//Proses LowerCase cukup menggunakan strtolower()
					$nom = 0;
					$kalimat1 = strtolower($_GET['keluhan']);

					$sql5 = $conn->query("SELECT * FROM m_stopword");
						while ($row1 = $sql5->fetch_array()) {
							$stopword1[] = trim($row1['stopword']);
						}
					$kata1 = explode(" ", $kalimat1);
					$jkata1 = count($kata1) - 1;
					for ($i = 0; $i <= $jkata1; $i++) {
						if (in_array($kata1[$i], $stopword1)) {
							unset($kata1[$i]);
						}
					}

					$kalimatbaru1 = implode(" ", $kata1);

					//Proses Stemming data testing
					$vstem12 = explode(" ",$kalimatbaru1);
					$jum112 = count($vstem1) - 1;
					for ($k = 0; $k <= $jum112; $k++) {
						$hasilstem12[] = stemming($vstem12[$k]);
					}
					$hstem12 = implode(" ", array_filter($hasilstem12));
					?>
					<tr>
						<td>D<?php echo $no+1;?></td>
						<td><?php echo $hstem12;?></td>
						<td>?</td>
					</tr>
				
				</tbody>
			  </table>
			  </div>
            </div>	
			
			<div class="card">
              <div class="card-header">
                <h5 class="card-title m-0"><em><b>3. Pencocokan Kata Antara Dokumen Training dan Testing</b></em></h5>
              </div>
              <div class="card-body">
			  <table class="table table-bordered" width="100%">
				<thead>
					<tr>
						<th width="5%">Dokumen</th>
						<th>Kumpulan Kata</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$sql = $conn->query("SELECT term FROM p_pembobotan");
				$k = array();
				while($r = $sql->fetch_assoc()){
				 $k[] = $r['term'];
				}
				$katakata = implode(",",$k);
				?>
					<tr>
						<td>D1,D2,D3</td>
						<td><?php echo $katakata;?></td>
					</tr>
					<tr>
						<td>D4</td>
						<td><?php echo str_replace(" ",",",trim($hstem12));?></td>
					</tr>
					<tr>
						<th>Hasil</th>
						<td><?php 
						$a = explode(",",$katakata);
						$b = explode(",",str_replace(" ",",",trim($hstem12)));
						$c = array_intersect($a,$b);
						if(count($c) == 0){ echo 0; } else { echo implode(",",$c); }
						?></td>
					</tr>
				</tbody>
			  </table>
			  </div>
            </div>
			<?php
			if(count($c) > 0){ ?>
				<div class="row">
				<div class="col-md-6">
				<div class="card">
				  <div class="card-header">
					<h5 class="card-title m-0"><em><b>4. Perhitungan TF-IDF Dokumen Testing</b></em></h5>
				  </div>
				  <div class="card-body">
				  <table class="table table-bordered" width="100%">
					<thead>
						<tr>
							<th>Term</th>
							<th>TF</th>
							<th>IDF (log N / DF)</th>
							<th width="20%">TF.IDF</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					$tfidf = 0.000;
					$totalbobot = 0;
					$totalkata = str_word_count(implode(",",$c));
					$sql = $conn->query("SELECT DISTINCT(term) as term,df,d1,d2,d3 FROM p_pembobotantf WHERE term IN ('".implode("','", $c)."')");
					while($r = $sql->fetch_assoc()){
					$tf = $r['d1']+$r['d2']+$r['d3']/($totalkata+(1/1000000));
					$idf = bcdiv(log10(3)/($r['df']+(1/1000000)),1,3);
					$tfidf = bcdiv($tf * $idf,1,3);
					?>
						<tr>
							<td><?php echo $r['term'];?></td>
							<td><?php echo $tf;?></td>
							<td><?php echo $idf;?></td>
							<td><?php echo $tfidf;?></td>
						</tr>
					<?php $totalbobot += $tfidf; } ?>
						<tr>
							<th colspan="3">Total</th>
							<td><?php echo $totalbobot;?></td>
						</tr>
					</tbody>
				  </table>
				  </div>
				</div>	
				</div>
				<div class="col-md-6">
				<div class="card">
				  <div class="card-header">
					<h5 class="card-title m-0"><em><b>5. Perhitungan TF-IDF Dokumen Testing</b></em></h5>
				  </div>
				  <div class="card-body">
				  
				  <table class="table table-bordered" width="100%">
					<thead>
						<tr>
							<th>Term</th>
							<th>D1</th>
							<th>D2</th>
							<th>D3</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$totalkata = str_word_count(implode(",",$c));
						$totalbobot1 = 0;
						$totalbobot2 = 0;
						$totalbobot3 = 0;
						$sql = $conn->query("SELECT DISTINCT(term) as term,df,d1,d2,d3 FROM p_pembobotantf WHERE term IN ('".implode("','",$c)."')");
						while($r = $sql->fetch_assoc()){
							
						$d1 = $r['d1']/($totalkata+(1/1000000));
						$hitung = bcdiv($d1 * ((log10(3)/1)),1,3);
						
						$d2 = $r['d2']/($totalkata+(1/1000000)) ;
						$hitung2 = bcdiv($d2 * ((log10(3)/1)),1,3);
						
						$d3 = $r['d3']/($totalkata+(1/1000000));
						$hitung3 = bcdiv($d3 * ((log10(3)/1)),1,3);
							?>
						<tr>
							<td><?php echo $r['term'];?></td>
							<td><?php echo bcdiv($hitung,1,3); ?></td>
							<td><?php echo bcdiv($hitung2,1,3);?></td>
							<td><?php echo bcdiv($hitung3,1,3);?></td>
						</tr>
						<?php $totalbobot1 += $hitung; $totalbobot2 += $hitung2; $totalbobot3 += $hitung3; } ?>
						<tr>
							<th>Total</th>
							<td><?php echo $totalbobot1;?></td>
							<td><?php echo $totalbobot2;?></td>
							<td><?php echo$totalbobot3;?></td>
						</tr>
					</tbody>
				  </table>
				  <?php 
				  //memasukkan nilai jarak kedalam session.				  
				  $nof = 0;
				  $bobotnya = array($totalbobot1,$totalbobot2,$totalbobot3);
				  $carijarak = $conn->query("SELECT * FROM p_filtertraining ORDER BY idfilter ASC");
				  while($rf = $carijarak->fetch_assoc()){
					  $id = $rf['idtraining'];
					  $simpanjarak = $conn->query("UPDATE p_jarak SET nilaibobot='$bobotnya[$nof]' WHERE idtraining='$id'");
					  $nof++;
				  } ?>
				  </div>
				</div>
				</div>
				</div>
				<div class="card">
				  <div class="card-header">
					<h5 class="card-title m-0"><em><b>6. Perhitungan Jarak dan Euclidean Distance dari data testing dengan data training </b></em></h5>
				  </div>
				  <div class="card-body">
				  <table class="table table-bordered" width="100%">
					<thead>
						<tr>
							<th width="20%">Dokumen</th>
							<th width="20%">Bobot</th>
							<th width="20%">Kuadrat Jarak (<?php echo $totalbobot;?>)</th>
							<th width="20%">Euclidean Distance</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					$peringkat = 0;
					$nomm = 0;
					$sql = $conn->query("SELECT * FROM p_jarak");
					while($r=$sql->fetch_assoc()){ $nomm++;
					$jaraknya = $totalbobot;
					$jaraknya1 = pow($r['nilaibobot']-$jaraknya,2);
					$euclidean = sqrt($jaraknya1);
					$updatelagi = $conn->query("UPDATE p_jarak SET euclidean='$euclidean' WHERE idtraining='$r[idtraining]'");
					?>				
						<tr>
							<td>D<?php echo $nomm;?></td>
							<td><?php echo $r['nilaibobot'];?></td>
							<td><?php echo $jaraknya1;?></td>
							<td><?php echo $euclidean;?></td>
						</tr>
					<?php 
					$updatelagi2 = $conn->query("UPDATE p_jarak SET idtemp='$nomm' WHERE idtraining='$r[idtraining]'");
					} ?>
					</tbody>
				  </table>
				  </div>
				</div>
				
				<div class="card">
				  <div class="card-header">
					<h5 class="card-title m-0"><em><b>7. Pengurutan Daftar Peringkat Mulai dari nilai terkecil </b></em></h5>
				  </div>
				  <div class="card-body">
				  <table class="table table-bordered" width="100%">
					<thead>
						<tr>
							<th width="20%">Dokumen</th>
							<th width="20%">Bobot</th>
							<th width="20%">Kuadrat Jarak (<?php echo $totalbobot;?>)</th>
							<th width="20%">Euclidean Distance</th>
							<th width="20%">Peringkat</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					$nomor = 0;
					$peringkat = 0;
					$sql = $conn->query("SELECT idtraining,nilaibobot,euclidean,idtemp FROM p_jarak ORDER BY CAST(euclidean as DECIMAL(10,5)) ASC");
					while($r=$sql->fetch_assoc()){ $nomor++; $peringkat++;
					$jaraknya = $totalbobot;
					$jaraknya1 = pow($r['nilaibobot']-$jaraknya,2);
					$euclidean = sqrt($jaraknya1);
					
					?>				
						<tr>
							<td>D<?php echo $r['idtemp'];?></td>
							<td><?php echo $r['nilaibobot'];?></td>
							<td><?php echo $jaraknya1;?></td>
							<td><?php echo $euclidean;?></td>
							<td><?php echo $peringkat;?></td>
						</tr>
					<?php } ?>
					</tbody>
				  </table>
				  </div>
				</div>
			<?php
			}
			$sql = $conn->query("SELECT * FROM p_jarak, m_sentimen,m_training WHERE m_training.idsentimen = m_sentimen.idsentimen AND p_jarak.idtraining = m_training.idtraining ORDER BY CAST(p_jarak.euclidean as DECIMAL(10,5))  ASC LIMIT 1");
			$r = $sql->fetch_assoc();
			?>
			<div class="card">
              <div class="card-header">
                <h5 class="card-title m-0"><em><b>7. Klasifikasi Terpilih : <?php if(count($c) == 0){ echo "Tidak Ditemukan"; }else{ echo $r['nmsentimen']; } ?></b></em></h5>
              </div>
              <div class="card-body">
				<?php if(count($c) == 0){ ?>
				   <p>Dari hasil perhitungan diatas, <b>Tidak Ditemukan Klasifikasi yang cocok, </b> karena tidak terdapat kecocokan antara data latih dan testing.</p>
				<?php }else{ ?>
				   <p>Dari hasil perhitungan diatas, diperoleh jarak data training dan data testing terdekat yaitu (D<?php echo $r['idtemp'];?>/<?php echo $r['nmsentimen'];?>). Jadi hasil klasifikasi menggunkan K-Nearest Neighbor data testing masuk ke dalam klasifikasi : <b><?php echo $r['nmsentimen'];?></b></p>
				<?php } ?>
			 </div>
			  <div class="card-footer">
				<div class="float-right">
					<a href="./" class="btn btn-sm btn-success"><i class="fa fa-home"></i> <em>Kembali Ke Home</em></a>
				</div>	
			  </div>
            </div>
			
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid-fluid -->
    </div>
	</div>