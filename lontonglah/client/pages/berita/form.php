<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/berita">Berita</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="mode_item">Tambah</li>
                </ol>
            </nav>
            <h4>Tambah Berita</h4>
        </div>
    </div>
</div>


<div class="container-fluid page__container">
    <div class="row card-group-row">
        <div class="col-lg-12 col-md-12">
            <div class="z-0">
                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                    <li class="nav-item">
                        <a href="#tab-informasi" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true" aria-controls="tab-informasi" >
							<span class="nav-link__count">
								<i class="fa fa-info-circle"></i>
							</span>
                            Informasi Dasar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-gambar" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								<i class="fa fa-cubes"></i>
							</span>
                            Gambar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-meta" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
							<span class="nav-link__count">
								<i class="fa fa-code"></i>
							</span>
                            Meta
                        </a>
                    </li>
                </ul>
                <div class="card card-body tab-content">
                    <div class="tab-pane active show fade" id="tab-informasi">
                        <?php require 'form-dasar.php'; ?>
                    </div>
                    <div class="tab-pane show fade" id="tab-gambar">
                        <?php require 'form-gambar.php'; ?>
                    </div>
                    <div class="tab-pane show fade" id="tab-meta">
                        <?php require 'form-meta.php'; ?>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" id="btn_save_data" class="btn btn-success saveData action-panel"><i class="fa fa-save"></i> Simpan & Keluar</button>
                        <button type="submit" id="btn_save_data_stay" class="btn btn-info saveData action-panel stay"><i class="fa fa-save"></i> Simpan & Tetap Disini</button>
                        <a href="<?php echo __HOSTNAME__; ?>/master/inventori" class="btn btn-danger action-panel"><i class="fa fa-ban"></i> Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
