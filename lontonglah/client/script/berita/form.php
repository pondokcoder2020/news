<script src="<?php echo __HOSTNAME__; ?>/plugins/ckeditor5-build-classic/ckeditor.js"></script>
<script type="text/javascript">
    $(function() {
        var editContent = {};
        var editorLong;


        $.ajax({
            async: false,
            url: __HOSTAPI__ + "/Berita/detail_berita/" + UID,
            type: "GET",
            beforeSend: function (request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function (response) {
                editContent = response.response_data[0];
                $("#txt_judul").val(editContent.title);

                /*$("#txt_penulis").append("<option title=\"" + editContent.nama + "\" value=\"" + editContent.penulis + "\">" + editContent.nama + "</option>");
                $("#txt_penulis").select2("data", {id: editContent.penulis, text: editContent.nama});
                $("#txt_penulis").trigger("change");*/


                /*$("#txt_kategori").append("<option title=\"" + editContent.cathegory_detail.name + "\" value=\"" + editContent.cathegory_detail.uid + "\">" + editContent.cathegory_detail.name + "</option>");
                $("#txt_kategori").select2("data", {id: editContent.cathegory_detail.uid, text: editContent.cathegory_detail.name});
                $("#txt_kategori").trigger("change");*/
            },
            error: function (response) {
                //
            }
        });





        function MyCustomUploadAdapterPlugin( editor ) {
            editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
                var MyCust = new MyUploadAdapter( loader );
                var dataToPush = MyCust.imageList;
                hiJackImage(dataToPush);
                return MyCust;
            };
        }

        var imageResultPopulator = [];

        function hiJackImage(toHi) {
            imageResultPopulator.push(toHi);
        }

        class MyUploadAdapter {
            static loader;
            constructor( loader ) {
                // CKEditor 5's FileLoader instance.
                this.loader = loader;

                // URL where to send files.
                this.url = __HOSTAPI__ + "/Upload";

                this.imageList = [];
            }

            // Starts the upload process.
            upload() {
                return new Promise( ( resolve, reject ) => {
                    this._initRequest();
                    this._initListeners( resolve, reject );
                    this._sendRequest();
                } );
            }

            // Aborts the upload process.
            abort() {
                if ( this.xhr ) {
                    this.xhr.abort();
                }
            }

            // Example implementation using XMLHttpRequest.
            _initRequest() {
                const xhr = this.xhr = new XMLHttpRequest();

                xhr.open( 'POST', this.url, true );
                xhr.setRequestHeader("Authorization", 'Bearer ' + <?php echo json_encode($_SESSION["admin_ciscard"]); ?>);
                xhr.responseType = 'json';
            }

            // Initializes XMLHttpRequest listeners.
            _initListeners( resolve, reject ) {
                const xhr = this.xhr;
                const loader = this.loader;
                const genericErrorText = 'Couldn\'t upload file:' + ` ${ loader.file.name }.`;

                xhr.addEventListener( 'error', () => reject( genericErrorText ) );
                xhr.addEventListener( 'abort', () => reject() );
                xhr.addEventListener( 'load', () => {
                    const response = xhr.response;

                    if ( !response || response.error ) {
                        return reject( response && response.error ? response.error.message : genericErrorText );
                    }

                    // If the upload is successful, resolve the upload promise with an object containing
                    // at least the "default" URL, pointing to the image on the server.
                    resolve( {
                        default: response.url
                    } );
                } );

                if ( xhr.upload ) {
                    xhr.upload.addEventListener( 'progress', evt => {
                        if ( evt.lengthComputable ) {
                            loader.uploadTotal = evt.total;
                            loader.uploaded = evt.loaded;
                        }
                    } );
                }
            }


            // Prepares the data and sends the request.
            _sendRequest() {
                const toBase64 = file => new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = () => resolve(reader.result);
                    reader.onerror = error => reject(error);
                });
                var Axhr = this.xhr;

                async function doSomething(fileTarget) {
                    fileTarget.then(function(result) {
                        var ImageName = result.name;

                        toBase64(result).then(function(renderRes) {
                            const data = new FormData();
                            data.append( 'upload', renderRes);
                            data.append( 'name', ImageName);
                            Axhr.send( data );
                        });
                    });
                }

                var ImageList = this.imageList;

                this.loader.file.then(function(toAddImage) {

                    ImageList.push(toAddImage.name);

                });

                this.imageList = ImageList;

                doSomething(this.loader.file);
            }
        }

        ClassicEditor
            .create( document.querySelector( '#txt_long_content' ), {
                extraPlugins: [ MyCustomUploadAdapterPlugin ],
                placeholder: "Konten Berita",
                removePlugins: ['MediaEmbed']
            } )
            .then( editor => {
                if(editContent.content_long === undefined) {
                    editor.setData("");
                } else {
                    editor.setData(editContent.content_long);
                }
                editorLong = editor;
                window.editor = editor;
            } )
            .catch( err => {
                //console.error( err.stack );
            } );


        $("#txt_kategori").select2({ //Tindakan Lab Sini
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Kategori tidak ditemukan";
                }
            },
            placeholder:"Cari Kategori",
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/Berita/kategori_select2",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_data;


                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.uid
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });

        $("#txt_penulis").select2({ //Tindakan Lab Sini
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Penulis tidak ditemukan";
                }
            },
            placeholder:"Cari Penulis",
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/Berita/penulis_select2",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_data;

                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.uid
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });

        $("#btnSaya").click(function () {
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Berita",
                type: "POST",
                data: {
                    request: "tambah_penulis_saya"
                },
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function (response) {
                    var data = response.response_data[0];

                    $("#txt_penulis").append("<option title=\"" + data.nama + "\" value=\"" + data.uid + "\">" + data.nama + "</option>");
                    $("#txt_penulis").select2("data", {id: data.uid, text: data.nama});
                    $("#txt_penulis").trigger("change");
                },
                error: function (response) {
                    //
                }
            });
        });





        //==========================================================CROPPER
        var imageLibrary = {
            SS: false,
            TN: false,
            SP: false

        };
        var targetCropperSS = $("#image-uploader1");
        var targetCropperTN = $("#image-uploader2");
        var targetCropperSP = $("#image-uploader3");

        var basic1 = targetCropperSS.croppie({
            enforceBoundary:false,
            viewport: {
                width: 478,
                height: 500
            },
        });

        if(imageLibrary.SS !== undefined) {
            if(imageLibrary.SS === false) {
                basic1.croppie("bind", {
                    zoom: 1,
                    url: __HOST__ + "/images/berita/SSUnset.png"
                });
            } else {
                basic1.croppie("bind", {
                    zoom: 1,
                    url: __HOST__ + "/images/berita/SS" + UID + ".png"
                });
            }
        } else {
            basic1.croppie("bind", {
                zoom: 1,
                url: __HOST__ + "/images/berita/SSUnset.png"
            });
        }






        var basic2 = targetCropperTN.croppie({
            enforceBoundary:false,
            viewport: {
                width: 173,
                height: 116
            },
        });

        if(imageLibrary.TN !== undefined) {
            if(imageLibrary.TN === false) {
                basic2.croppie("bind", {
                    zoom: 1,
                    url: __HOST__ + "/images/berita/TNUnset.png"
                });
            } else {
                basic2.croppie("bind", {
                    zoom: 1,
                    url: __HOST__ + "/images/berita/TN" + UID + ".png"
                });
            }
        } else {
            basic2.croppie("bind", {
                zoom: 1,
                url: __HOST__ + "/images/berita/TNUnset.png"
            });
        }










        var basic3 = targetCropperSP.croppie({
            enforceBoundary:false,
            viewport: {
                width: 320,
                height: 275
            },
        });

        if(imageLibrary.SP !== undefined) {
            if(imageLibrary.SP === false) {
                basic3.croppie("bind", {
                    zoom: 1,
                    url: __HOST__ + "/images/berita/SPUnset.png"
                });
            } else {
                basic3.croppie("bind", {
                    zoom: 1,
                    url: __HOST__ + "/images/berita/SP" + UID + ".png"
                });
            }
        } else {
            basic3.croppie("bind", {
                zoom: 1,
                url: __HOST__ + "/images/berita/SPUnset.png"
            });
        }






        $("#upload-image1").change(function() {
            readURL(this, basic1);
        });

        $("#upload-image2").change(function() {
            readURL(this, basic2);
        });

        $("#upload-image3").change(function() {
            readURL(this, basic3);
        });

        function readURL(input, cropper) {
            var url = input.value;
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    cropper.croppie('bind', {
                        url: e.target.result
                    });
                    //$('#imageLoader').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
            else{
                //$('#img').attr('src', '/assets/no_preview.png');
            }
        }





























        $("#btn_save_publish").click(function () {
            var me = $(this);
            me.attr({
                "disabled": "disabled"
            });

            var judul = $("#txt_judul").val();
            var kategori = $("#txt_kategori").val();
            var penulis = $("#txt_penulis").val();
            var featured = $("input[type=\"radio\"][name=\"featured\"]:checked").val();
            var content_short = $("#txt_short_content").val();
            var content_long = editorLong.getData();
            var meta_title = $("#txt_meta_title").val();
            var meta_description = $("#txt_meta_description").val();
            var meta_tag = $("#txt_meta_tag").val();

            /*new Promise(function (resolve, reject) {
                resolve(function () {
                    $(".nav-tabs a[href=\"#tab-gambar\"]").tab("show");
                    return true;
                });
            }).then(function (result) {
                if(result) {

                }
            });*/

            $(".nav-tabs a[href=\"#tab-gambar\"]").tab("show");

            setTimeout(function () {
                var imageSS;
                basic1.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function (ImageSS) {
                    basic2.croppie('result', {
                        type: 'canvas',
                        size: 'viewport'
                    }).then(function (ImageTN) {
                        basic3.croppie('result', {
                            type: 'canvas',
                            size: 'viewport'
                        }).then(function (ImageSP) {
                            Swal.fire({
                                title: "Berita",
                                text: "Proses Data?",
                                showDenyButton: true,
                                confirmButtonText: "Ya",
                                denyButtonText: "Tidak",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        async: false,
                                        url: __HOSTAPI__ + "/Berita",
                                        type: "POST",
                                        data: {
                                            request: MODE + "_berita",
                                            uid: (UID === undefined) ? "" : UID,
                                            judul: judul,
                                            kategori: kategori,
                                            penulis: penulis,
                                            featured: featured,
                                            content_short: content_short,
                                            content_long:content_long,
                                            ImageSS: ImageSS,
                                            ImageTN: ImageTN,
                                            ImageSP: ImageSP,
                                            publish: "Y",
                                            meta_title: meta_title,
                                            meta_description: meta_description,
                                            meta_tag: meta_tag
                                        },
                                        beforeSend: function (request) {
                                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                        },
                                        success: function (response) {
                                            var data = response.response_result;
                                            if(data > 0)  {
                                                location.href = __HOSTNAME__ + "/berita";
                                            } else {
                                                console.log(response);
                                            }
                                        },
                                        error: function (response) {
                                            console.log(response);
                                        }
                                    });
                                }
                            });
                        });
                    });
                });
            }, 1000);
            me.removeAttr("disabled");
        });

        $("#btn_save_data").click(function () {
            var me = $(this);
            me.attr({
                "disabled": "disabled"
            });

            var judul = $("#txt_judul").val();
            var kategori = $("#txt_kategori").val();
            var penulis = $("#txt_penulis").val();
            var featured = $("input[type=\"radio\"][name=\"featured\"]:checked").val();
            var content_short = $("#txt_short_content").val();
            var content_long = editorLong.getData();
            var meta_title = $("#txt_meta_title").val();
            var meta_description = $("#txt_meta_description").val();
            var meta_tag = $("#txt_meta_tag").val();

            /*new Promise(function (resolve, reject) {
                resolve(function () {
                    $(".nav-tabs a[href=\"#tab-gambar\"]").tab("show");
                    return true;
                });
            }).then(function (result) {
                if(result) {

                }
            });*/

            $(".nav-tabs a[href=\"#tab-gambar\"]").tab("show");

            setTimeout(function () {
                var imageSS;
                basic1.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function (ImageSS) {
                    basic2.croppie('result', {
                        type: 'canvas',
                        size: 'viewport'
                    }).then(function (ImageTN) {
                        basic3.croppie('result', {
                            type: 'canvas',
                            size: 'viewport'
                        }).then(function (ImageSP) {
                            Swal.fire({
                                title: "Berita",
                                text: "Proses Data?",
                                showDenyButton: true,
                                confirmButtonText: "Ya",
                                denyButtonText: "Tidak",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        async: false,
                                        url: __HOSTAPI__ + "/Berita",
                                        type: "POST",
                                        data: {
                                            request: MODE + "_berita",
                                            uid: (UID === undefined) ? "" : UID,
                                            judul: judul,
                                            kategori: kategori,
                                            penulis: penulis,
                                            featured: featured,
                                            content_short: content_short,
                                            content_long:content_long,
                                            ImageSS: ImageSS,
                                            ImageTN: ImageTN,
                                            ImageSP: ImageSP,
                                            publish: "N",
                                            meta_title: meta_title,
                                            meta_description: meta_description,
                                            meta_tag: meta_tag
                                        },
                                        beforeSend: function (request) {
                                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                        },
                                        success: function (response) {
                                            var data = response.response_result;

                                            if(data > 0)  {
                                                location.href = __HOSTNAME__ + "/berita";
                                            } else {
                                                console.log(response);
                                            }
                                        },
                                        error: function (response) {
                                            console.log(response);
                                        }
                                    });
                                }
                            });
                        });
                    });
                });
            }, 1000);
            me.removeAttr("disabled");
        });
    });
</script>