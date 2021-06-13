<script type="text/javascript">
    $(function () {

        var Kategori = reloadKategori(__HOSTAPI__);


        function reloadKategori(__HOSTAPI__) {
            var Kategori;
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Berita/get_kategori/",
                type: "GET",
                success: function (response) {
                    Kategori = response.response_data;
                },
                error: function (response) {
                    console.log(response);
                }
            });

            return Kategori;
        }







        //Load Menu Kategori
        $("#top-menu-cathegories").html("");
        for(var a in Kategori) {
            $("#top-menu-cathegories").append("<li class=\"menu-item menu-item-type-taxonomy menu-item-object-category\">" +
                "<a href=\"" + __HOSTCLIENT__ + "article/cathegories_news/" + Kategori[a].uid + "\">" + Kategori[a].name + "</a>" +
                "</li>");
        }

    });
</script>