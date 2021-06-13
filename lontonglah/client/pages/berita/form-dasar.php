<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header card-header-large bg-white d-flex align-items-center">
                <h5 class="card-header__title flex m-0">Informasi Dasar</h5>
            </div>
            <div class="card-body tab-content">
                <div class="tab-pane show fade active" id="info-dasar-1">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="txt_nama">Judul:</label>
                                        <input type="text" class="form-control" id="txt_judul" placeholder="Judul Berita" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_kategori">Kategori:</label>
                                        <select class="form-control" id="txt_kategori"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_penulis">Penulis:</label>
                                        <select class="form-control" id="txt_penulis"></select>
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-info" id="btnSaya">
                                        <span>
                                            <i class="fa fa-check-circle"></i> Saya
                                        </span>
                                        </button>
                                        <button class="btn btn-info" id="btnPenulisTambah">
                                        <span>
                                            <i class="fa fa-plus"></i> Tambah Penulis Baru
                                        </span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-3"></div>
                                <div class="col-md-2">
                                    <ol type="1" class="form-list-item no-caption">
                                        <li>
                                            <h6>Featured</h6>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="featured" value="y" />
                                                        <label class="form-check-label">
                                                            Ya
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="featured" value="n" />
                                                        <label class="form-check-label">
                                                            Tidak
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                </div>
                                <div class="col-md-12">
                                    <br />
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="txt_short_content">Konten Pendek:</label>
                                        <textarea id="txt_short_content" class="form-control" placeholder="Konten pendek akan dimunculkan pada cuplikan link berita"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="txt_long_content"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>