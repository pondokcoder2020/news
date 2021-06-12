<script type="text/javascript">
    $(function () {
        var MODE = "add";
        var selectedUID = "";

        var dataKategori = $("#table-berita").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Berita",
                type: "POST",
                data: function(d) {
                    d.request = "get_berita";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var rawData = [];

                    if(response === undefined || response.response_package === undefined) {
                        rawData = [];
                        response.draw = 0;
                        response.recordsTotal = 0;
                        response.recordsFiltered = 0;
                    } else {
                        rawData = response.response_package.response_data;
                        response.draw = parseInt(response.response_package.response_draw);
                        response.recordsTotal = response.response_package.recordsTotal;
                        response.recordsFiltered = response.response_package.recordsFiltered;
                    }



                    return rawData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Judul, Konten, Penulis"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.title;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.content_short;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.content_short;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<a href=\"" + __HOSTNAME__ + "/berita/edit/" + row.uid + "\" class=\"btn btn-info btn-sm btnEdit\">" +
                            "<i class=\"fa fa-pencil-ruler\"></i> Edit" +
                            "</a>" +
                            "<button id=\"delete_" + row.uid + "\" class=\"btn btn-danger btn-sm btnDelete\">" +
                            "<i class=\"fa fa-trash-alt\"></i> Delete" +
                            "</button>" +
                            "</div>";
                    }
                }
            ]
        });

        $("body").on("click", ".btnDelete", function () {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];
            Swal.fire({
                title: "Berita",
                text: "Hapus Data?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/Berita/berita/" + uid,
                        type: "DELETE",
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function (response) {
                            dataKategori.ajax.reload();
                        },
                        error: function (response) {
                            //
                        }
                    });
                }
            });
        });

        $("#btnSubmit").click(function () {
            Swal.fire({
                title: "Kategori Berita",
                text: "Proses Data?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    var nama = $("#txt_nama").val();
                    var keterangan = $("#txt_keterangan").val();

                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/Berita",
                        type: "POST",
                        data: {
                            request: MODE + "_kategori",
                            uid: selectedUID,
                            nama: nama,
                            keterangan: keterangan
                        },
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function (response) {
                            dataKategori.ajax.reload();
                            $("#form-manage").modal("hide");
                        },
                        error: function (response) {
                            //
                        }
                    });
                }
            });
        });
    });
</script>

<div id="form-manage" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12">
                    <label for="txt_nama">Nama Kategori:</label>
                    <input type="text" class="form-control" placeholder="Nama Kategori" id="txt_nama" />
                </div>
                <div class="form-group col-md-12">
                    <label for="txt_alamat">Keterangan:</label>
                    <textarea class="form-control" id="txt_keterangan" placeholder="Keterangan"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span>
                        <i class="fa fa-times"></i> Kembali
                    </span>
                </button>
                <button type="button" class="btn btn-success" id="btnSubmit">
                    <span>
                        <i class="fa fa-check"></i> Submit
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>