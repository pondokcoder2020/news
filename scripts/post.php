<script type="text/javascript">
    function load_post(__HOSTAPI__, kategori) {
        var Data;
        if(kategori === "*") {
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Berita/front_all_news",
                type: "GET",
                success: function (response) {
                    Data = response.response_data;
                },
                error: function (response) {
                    console.log(response);
                }
            });
        } else {
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Berita/front_cathegory_news/" + kategori,
                type: "GET",
                success: function (response) {
                    Data = response.response_data;
                },
                error: function (response) {
                    console.log(response);
                }
            });
        }


        return Data;
    }
</script>