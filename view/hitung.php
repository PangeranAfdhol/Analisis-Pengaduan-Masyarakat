 <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1 class="m-0 text-center"><small>Halaman Perhitungan Data Training</small></h1>
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
	
	<form action="index.php" method="get" target="_blank">
	<input type="hidden" name="page" value="hitung2">
	<!-- <input type="hidden" name="id" value="<?php //echo $_GET['id'];?>"> -->
	<input type="hidden" name="keluhan" value="<?php echo $_GET['keluhan'];?>">
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          
          <!-- /.col-md-6 -->
		  
         <div class="col-lg-12">
		 
			<div class="card">
              <div class="card-header">
                <h5 class="card-title m-0"><em><b>A. Pengklasifikasian & Perhitungan Data Latih</b></em></h5>
              </div>
              <div class="card-body">
			  <table class="table table-bordered" width="100%">
				<thead>
					<tr>
						<th width="5%">Dokumen</th>
						<th width="48%">Keluhan</th>
						<th>Klasifikasi</th>
					</tr>
				</thead>
				<tbody>
				<?php
				
				 //Fungsi Stemming
				include "includes/stemming2.php";

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
			
            <div class="card">
              <div class="card-header">
                <h5 class="card-title m-0"><em><b>1. Preprocessing Case Folding</b></em></h5>
              </div>
              <div class="card-body">
			  <table class="table table-bordered" width="100%">
				<thead>
					<tr>
						<td width="5%">Dokumen</td>
						<td width="48%">Keluhan</td>
						<td>Hasil</td>
					</tr>
				</thead>
				<tbody>
				<?php
				
				$no = 0;
				$sql = $conn->query("SELECT * FROM p_filtertraining, m_sentimen WHERE p_filtertraining.idsentimen = m_sentimen.idsentimen ORDER BY idfilter ASC");
				while($r=$sql->fetch_assoc()){ $no++;?>
					<tr>
						<td>D<?php echo $no;?></td>
						<td><?php echo $r['nmtraining'];?></td>
						<td><?php echo strtolower($r['nmtraining']);?></td>
					</tr>
				<?php } ?>
				</tbody>
			  </table>
			  </div>
            </div>
			
			<div class="card">
              <div class="card-header">
                <h5 class="card-title m-0"><em><b>2. Preprocessing Filtering (Stopword)</b></em></h5>
              </div>
              <div class="card-body">
			  <table class="table table-bordered" width="100%">
				<thead>
					<tr>
						<td width="5%">Dokumen</td>
						<td width="48%">Keluhan</td>
						<td>Hasil</td>
					</tr>
				</thead>
				<tbody>
				<?php
				$no = 0;
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
					
					$no++;?>
					<tr>
						<td>D<?php echo $no;?></td>
						<td><?php echo $r['nmtraining'];?></td>
						<td><?php echo strtolower($removal);?></td>
					</tr>
				<?php
				$removal3[] = strtolower(trim($removal));
				} ?>
				</tbody>
			  </table>
			  </div>
            </div>
			
			<div class="card">
              <div class="card-header">
                <h5 class="card-title m-0"><em><b>3. Preprocessing Stemming </b></em></h5>
              </div>
              <div class="card-body">
			  
			  <table class="table table-bordered" width="100%">
				<thead>
					<tr>
						<td width="5%">Dokumen</td>
						<td width="48%">Keluhan</td>
						<td>Hasil</td>
					</tr>
				</thead>
				<tbody>
				<?php
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
					$no++;?>
					<tr>
						<td>D<?php echo $no;?></td>
						<td><?php echo $kalimatbaru[$i];?></td>
						<td><?php echo trim($rr);?></td>
					</tr>
				<?php $token[] = $hasilstem; } ?>
				</tbody>
			  </table>
			  </div>
            </div>
			
			<div class="card">
              <div class="card-header">
                <h5 class="card-title m-0"><em><b>5. Preprocessing  Tokenizing  </b></em></h5>
              </div>
              <div class="card-body">
				<table class="table table-bordered" width="100%">
				
				<?php
				$no = 0;
				$atoken = array_values(array_filter(array_map('array_filter',$token)));
				$ktoken = array_map('array_values', $atoken);
				
				$juml3 = count($ktoken) - 1;
				$tfkata = array();
				$tfkatanya = array();
				$sql = $conn->query("DELETE FROM p_pembobotan");
				for ($i = 0; $i <= $juml3; $i++) { $no++;
				  echo "<tr>";
				  echo "<td width='10%'>D$no</td>";
				  $sql = $conn->query("INSERT INTO p_pembobotan (idtraining) VALUES ('$no')");
				  for ($col = 0; $col < count($ktoken[$i]); $col++) {
					echo "<td>".$ktoken[$i][$col]."</td>";
					$tfkata[$i][] = $ktoken[$i][$col];
					$tfkatanya[] = $ktoken[$i][$col];
				  }
				  echo "</tr>";
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
				</table>
				<p>
			  </div>
			  <input type="hidden" name="kata" value="<?php echo $katanya;?>">
            </div>
			
			<div class="card">
              <div class="card-header">
                <h5 class="card-title m-0"><em><b>6. Pembobotan Term Frekuensi</b></em></h5>
              </div>
              <div class="card-body">
			  <table class="table table-bordered" width="100%">
				<thead>
					<tr>
						<th width="5%">Dokumen</th>
						<th>Kata</th>
						<th width="10%">Jumlah Term</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$term = array();
				$no = 0;
				$sql = $conn->query("SELECT * FROM m_training, p_pembobotan WHERE m_training.idtraining = p_pembobotan.idtraining ORDER BY m_training.idtraining ASC");
				while($r=$sql->fetch_assoc()){ $no++;?>
					<tr>
						<td>D<?php echo $no;?></td>
						<td><?php echo $r['term'];?></td>
						<td><?php echo $r['jmlkata'];?></td>
					</tr>
				<?php $term[] = $r['term']; } ?>
				</tbody>
			  </table>
			  <?php
				//Hapus Dulu Perhitungan Sebelumnya
				$sql3 = $conn->query("DELETE FROM p_pembobotantf");
				
				//Temporary Kata dari Dokumen 1,2,3
				$term1 = explode(",",$term[0]);
				$term2 = explode(",",$term[1]);
				$term3 = explode(",",$term[2]);
				
				//Ambil array dari proses Tokenizing
				$kterm = $tfkatanya;
				$j = count($kterm) - 1;
				
				//Hitung Per Kata
				for ($i = 0; $i <= $j; $i++) { 
					//Simpan kata ke tabel pembobotantf
					$sql4 = $conn->query("INSERT INTO p_pembobotantf(term) VALUES ('$kterm[$i]')");
				}
				?>
			  </div>
              
			</div>	
			
			<div class="card">
              <div class="card-header">
                <h5 class="card-title m-0"><em><b>7. Pembobotan dengan menggunakan TF-IDF</b></em></h5>
              </div>
              <div class="card-body">
			  <table class="table table-bordered" width="100%">
				<thead>
					<tr>
						<th width="5%">Term</th>
						<th>D1</th>
						<th>D2</th>
						<th>D3</th>
						<th width="15%">TFD1</th>
						<th width="15%">TFD2</th>
						<th width="15%">TFD3</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$no = 0;
				$sql6 = $conn->query("SELECT * FROM p_pembobotantf");
				while($r=$sql6->fetch_assoc()){ 
					$katabobot = $r['term'];
					//proses hitung pembobotan DF setiap kata  
					for ($x = 1; $x <= 3; $x++) {
						$cari = $conn->query("SELECT DISTINCT(term) FROM p_pembobotan WHERE idtraining=$x");
						while($rows = $cari->fetch_assoc()){
							$countbobot = substr_count($rows['term'],$katabobot);
							$update = $conn->query("UPDATE p_pembobotantf SET d$x='$countbobot' WHERE term='$katabobot'");
						}
					}
				}
				
				$sql8 = $conn->query("SELECT * FROM p_pembobotantf");
				while($rw=$sql8->fetch_assoc()){
					$bobotnya = $rw['term'];
					$jumlahdf = $rw['d1']+$rw['d2']+$rw['d3'];
					$update2 = $conn->query("UPDATE p_pembobotantf SET df='$jumlahdf' WHERE term='$bobotnya'");
				}
				
				$sql7 = $conn->query("SELECT DISTINCT(term) AS term,d1,d2,d3 FROM p_pembobotantf");
				while($ro=$sql7->fetch_assoc()){ 
				?>
					<tr>
						<td><?php echo $ro['term'];?></td>
						<td><?php echo $ro['d1'];?></td>
						<td><?php echo $ro['d2'];?></td>
						<td><?php echo $ro['d3'];?></td>
						<td><?php echo ($ro['d1']/$jmlkata1);?></td>
						<td><?php echo ($ro['d2']/$jmlkata2);?></td>
						<td><?php echo ($ro['d3']/$jmlkata3);?></td>
					</tr>
				<?php } ?>
				</tbody>
			  </table>
			  </div>
              
			</div>	
			
			<div class="card">
              <div class="card-header">
                <h5 class="card-title m-0"><em><b>8. Menghitung DF. IDF (Inverse Document Frequency). (N= Jumlah dokumen )</b></em></h5>
              </div>
              <div class="card-body">
				<div class="row">
				  <div class="col-md-6">
					<table class="table table-bordered" width="100%">
						<thead>
							<tr>
								<th width="50%">Term</th>
								<th width="50%">DF</th>
								
							</tr>
						</thead>
						<tbody>
						<?php
						$no = 0;
						$sql = $conn->query("SELECT DISTINCT(term) as term,df FROM p_pembobotantf");
						while($r=$sql->fetch_assoc()){ ?>
							<tr>
								<td><?php echo $r['term'];?></td>
								<td><?php echo $r['df'];?></td>
								
							</tr>
						<?php } ?>
						</tbody>
					</table>
				  </div>
				  <div class="col-md-6">
					<table class="table table-bordered" width="100%">
						<thead>
							<tr>
								<th width="35%">Term</th>
								<th width="35%">DF</th>
								<th width="35%">IDF (log N / df)</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$no = 0;
						$sql = $conn->query("SELECT DISTINCT(term) as term,df FROM p_pembobotantf");
						while($r=$sql->fetch_assoc()){ ?>
							<tr>
								<td><?php echo $r['term'];?></td>
								<td><?php echo $r['df'];?></td>
								<td><?php echo (log10(3)/$r['df']);?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				  </div>
				</div>
			  
			  </div>
              
			</div>	
			
			<div class="card">
              <div class="card-header">
                <h5 class="card-title m-0"><em><b>9. Hasil Perhitungan TF-IDF Data Training</b></em></h5>
              </div>
              <div class="card-body">
				 
					<table class="table table-bordered" width="100%">
						<thead>
							<tr>
							  <th width="5%" rowspan="2">Term</th>
							  <th rowspan="2">TFD1</th>
							  <th rowspan="2">TFD2</th>
							  <th rowspan="2">TFD3</th>
							  <th rowspan="2">IDF (log N / df)</th>
							  <th colspan="3">IDF</th>
							</tr>
							<tr>
								<th>D1</th>
								<th>D2</th>
								<th>D3</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$no = 0;
						$sql = $conn->query("SELECT DISTINCT(term) as term,d1,d2,d3 FROM p_pembobotantf");
						while($r=$sql->fetch_assoc()){ ?>
							<tr>
								<td><?php echo $r['term'];?></td>
								<td><?php echo ($r['d1']/4);?></td>
								<td><?php echo ($r['d2']/2);?></td>
								<td><?php echo ($r['d3']/2);?></td>
								<td><?php echo (log10(3)/1);?></td>
								<td><?php echo (($r['d1']/4)*(log10(3)/1));?></td>
								<td><?php echo (($r['d2']/2)*(log10(3)/1));?></td>
								<td><?php echo (($r['d3']/2)*(log10(3)/1));?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
			  </div>
              <div class="card-footer">
				<div class="float-right">
					<button type="submit" class="btn btn-sm btn-success"><i class="fa fa-search"></i> <em>Lanjut ke Tahap Perhitungan Data Testing</em></button>
				</div>	
			  </div>
			</div>	
			
			
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid-fluid -->
    </div>
	</form>
	
	