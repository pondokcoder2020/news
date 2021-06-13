<script type="text/javascript">
    $(function () {

        var NewsAll = load_post(__HOSTAPI__, "*");
        var counterAll = 1;
        for(var a in NewsAll) {
            if(counterAll <= 6) {
                $("#serpane1").append("" +
                    "<div class=\"tab-post item\">" +
                        "<a href=\"" + __HOSTCLIENT__ + "article/detail/" + NewsAll[a].uid + "\" title=\"" + NewsAll[a].title + "\">" +
                            "<img alt=\"" + NewsAll[a].title + "\" class=\"grayscale grayscale-fade wp-post-image\" height=\"150\" sizes=\"(max-width: 150px) 100vw, 150px\" src=\"" + __HOSTCLIENT__ + "/lontonglah/images/berita/TN" + NewsAll[a].uid + ".png\" width=\"150\">" +
                        "</a> " +
                        "<a class=\"meta\" href=\"\" title=\"\">" +
                            NewsAll[a].title +
                        "</a>" +
                        "<p class=\"meta meta_alt tranz\">" + NewsAll[a].created_at_parsed + "</p>" +
                    "</div>");
            }
        }



        $.ajax({
            async: false,
            url: __HOSTAPI__ + "/Berita/detail_berita/" + __PAGES__[2],
            type: "GET",
            success: function (response) {
                var data = response.response_data[0];
                $("#news_title").html(data.title);
                $("#news_image").attr({
                    "src": __HOSTCLIENT__ + "lontonglah/images/berita/SS" + data.uid + ".png?" + day + "-" + month + "-" + year + "_" + hour + "_" + minutes + "_" + seconds
                });
                $("#news_date").html(data.created_at_parsed);
                $("#news_content").html(data.content_long);
                $("#news_cathegory_target").attr({
                    "href": __HOSTCLIENT__ + "article/cathegories_news/" + data.cathegory
                });


                var NewsCath = load_post(__HOSTAPI__, data.cathegory);

                var counter = 1;

                for(var a in NewsCath) {
                    if(NewsCath[a].uid !== __PAGES__[2] && counter <= 4) {
                        $("#news_related").append("" +
                            "<li class=\"item\">" +
                                "<a href=\"" + __HOSTCLIENT__ + "article/detail/" + NewsCath[a].uid + "\" title=\"" + NewsCath[a].title + "\">" +
                                    "<img alt=\"\" class=\"grayscale grayscale-fade wp-post-image\" height=\"116\" sizes=\"(max-width: 173px) 100vw, 173px\" src=\"" + __HOSTCLIENT__ + "/lontonglah/images/berita/TN" + NewsCath[a].uid + ".png\" width=\"173\">" +
                                "</a>" +
                                "<h3>" +
                                    "<a href=\"" + __HOSTCLIENT__ + "article/detail/" + NewsCath[a].uid + "\" title=\"" + NewsCath[a].title + "\">" + NewsCath[a].title + "</a>" +
                                "</h3>" +
                            "</li>");
                        counter++;
                    }
                }

            },
            error: function (response) {
                console.clear();
                console.log(response);
            }
        });
    });
</script>