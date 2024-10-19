 <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1 class="m-0"><small>Simulasi Rumus Perhitungan</small></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
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
		  <form action="index.php" method="get">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title m-0">Silahkan ketik keluhan anda pada kolom isian dibawah</h5>
              </div>
			  
              <div class="card-body">
					<input type="hidden" name="page" value="hitung">
					<!-- <input type="hidden" name="id" value="<?php //echo date('YmdHis');?>"> -->
					<input type="text" class="form-control" name="keluhan">
			  </div>
			  <div class="card-footer">
				<div class="float-right">
					<button type="submit" class="btn btn-sm btn-success"><i class="fa fa-search"></i> Proses Simulasi</button>
					<button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-window-close"></i> Batalkan</button>
				</div>	
			  </div>
			  
            </div>
			</form>
          </div>
		  
		  
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>