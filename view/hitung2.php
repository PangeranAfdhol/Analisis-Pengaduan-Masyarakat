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
				$no = 0;
				$sql = $conn->query("SELECT idtraining FROM p_filtertraining");
				$r=$sql->num_rows;
				$no = $r + 1;
				?>
					<tr>
						<td>D<?php echo $no;?></td>
						<td><?php echo $_GET['keluhan'];?></td>
						<td>?</td>
					</tr>
				
				</tbody>
			  </table>
			  </div>
            </div>	
			
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
				include "includes/stemming2.php";
				$no = 0;
				$sql = $conn->query("SELECT idtraining FROM p_filtertraining");
				$r=$sql->num_rows;
				$no = $r + 1;
				//Proses LowerCase cukup menggunakan strtolower()
				$kalimat = strtolower($_GET['keluhan']);
				
				//Proses Stopword dari database
				$sql2 = $conn->query("SELECT * FROM m_stopword");
					while ($row = $sql2->fetch_array()) {
						$stopword[] = trim($row['stopword']);
					}
				$kata = explode(" ", $kalimat);
				$jkata = count($kata) - 1;
				for ($i = 0; $i <= $jkata; $i++) {
					if (in_array($kata[$i], $stopword)) {
						unset($kata[$i]);
					}
				}
				
				$kalimatbaru = implode(" ", $kata);
				
				//Proses Stemming
				$vstem = explode(" ",$kalimatbaru);
				$juml = count($vstem) - 1;
				for ($i = 0; $i <= $juml; $i++) {
					$hasilstem[] = stemming($vstem[$i]);
				}
				$hstem = implode(" ", $hasilstem);
				?>
					<tr>
						<td>D<?php echo $no;?></td>
						<td><?php echo $hstem;?></td>
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
					<tr>
						<td>D1,D2,D3</td>
						<td><?php echo $_GET['kata'];?></td>
					</tr>
					<tr>
						<td>D4</td>
						<td><?php echo str_replace(" ",",",trim($hstem));?></td>
					</tr>
					<tr>
						<th>Hasil</th>
						<td><?php 
						$a = explode(",",trim($_GET['kata']));
						$b = explode(",",str_replace(" ",",",trim($hstem)));
						$c = array_intersect($a,$b);
						if(count($c) == 0){ echo 0; } else { echo implode(",",$c); } ?></td>
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
					$tf = 0;
					$tfidf = 0;
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
							<td><?php echo $totalbobot3;?></td>
						</tr>
					</tbody>
				  </table>
				  <?php 
				  
				  $nof = 0;
				  $bobotnya = array($totalbobot1,$totalbobot2,$totalbobot3);
				  $carijarak = $conn->query("SELECT * FROM p_filtertraining ORDER BY idfilter ASC");
				  while($rf = $carijarak->fetch_assoc()){
					  $id = $rf['idtraining'];
					  $simpanjarak = $conn->query("UPDATE p_jarak SET nilaibobot='$bobotnya[$nof]' WHERE idtraining='$id'");
					  $nof++;
				  }
				  ?>
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
					$nomm = 0;
					$sql11 = $conn->query("SELECT * FROM p_jarak");
					while($r11=$sql11->fetch_assoc()){ $nomm++;
					$jaraknya = $totalbobot;
					$jaraknya1 = pow($r11['nilaibobot']-$jaraknya,2);
					$euclidean = sqrt($jaraknya1);
					$updatelagi = $conn->query("UPDATE p_jarak SET euclidean='$euclidean' WHERE idtraining='$r11[idtraining]'");
					?>				
						<tr>
							<td>D<?php echo $nomm;?></td>
							<td><?php echo $r11['nilaibobot'];?></td>
							<td><?php echo $jaraknya1;?></td>
							<td><?php echo $euclidean;?></td>
						</tr>
					
					<?php 
					$updatelagi2 = $conn->query("UPDATE p_jarak SET idtemp='$nomm' WHERE idtraining='$r11[idtraining]'");
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
					$nom = 0;
					$peringkat = 0;
					$sql9 = $conn->query("SELECT idtraining,nilaibobot,euclidean,idtemp FROM p_jarak ORDER BY CAST(euclidean as DECIMAL(10,5)) ASC");
					while($r9=$sql9->fetch_assoc()){ $peringkat++; $nom++;
					$jaraknya2 = $totalbobot;
					$jaraknya12 = pow($r9['nilaibobot']-$jaraknya2,2);
					$euclidean2 = sqrt($jaraknya12);
					?>				
						<tr>
							<td>D<?php echo $r9['idtemp'];?></td>
							<td><?php echo $r9['nilaibobot'];?></td>
							<td><?php echo $jaraknya12;?></td>
							<td><?php echo $euclidean2;?></td>
							<td><?php echo $peringkat;?></td>
						</tr>
					<?php 
					
					} ?>
					</tbody>
				  </table>
				  </div>
				</div>
			<?php
			}
			$sql10 = $conn->query("SELECT * FROM p_jarak, m_sentimen,m_training WHERE m_training.idsentimen = m_sentimen.idsentimen AND p_jarak.idtraining = m_training.idtraining ORDER BY CAST(p_jarak.euclidean as DECIMAL(10,5))  ASC LIMIT 1");
			$r10 = $sql10->fetch_assoc();
			?>
			<div class="card">
              <div class="card-header">
                <h5 class="card-title m-0"><em><b>7. Klasifikasi Terpilih : <?php if(count($c) == 0){ echo "Tidak Ditemukan"; }else{ echo $r10['nmsentimen']; } ?></b></em></h5>
              </div>
              <div class="card-body">
				<?php if(count($c) == 0){ ?>
				   <p>Dari hasil perhitungan diatas, <b>Tidak Ditemukan Klasifikasi</b> karena tidak terdapat kecocokan antara data latih dan testing.</p>
				<?php }else{ ?>
				   <p>Dari hasil perhitungan diatas, diperoleh jarak data training dan data testing terdekat yaitu (<?php echo $r10['nmsentimen'];?>). Jadi hasil klasifikasi menggunkan K-Nearest Neighbor data testing masuk ke dalam klasifikasi : <b><?php echo $r10['nmsentimen'];?></b></p>
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