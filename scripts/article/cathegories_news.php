<script type="text/javascript">
    $(function () {
        $.ajax({
            async: false,
            url: __HOSTAPI__ + "/Berita/detail_kategori/" + __PAGES__[2],
            type: "GET",
            success: function (response) {
                var data = response.response_data[0];
                $("#cathegory_name").html(data.name);
                $("#cathegory_desc").html(data.description);


                var NewsCath = load_post(__HOSTAPI__, data.uid);

                for(var a in NewsCath) {
                    $("#cathegory_news_loader").append("" +
                        "<div class=\"item normal tranz post-1042 post type-post status-publish format-image has-post-thumbnail hentry category-lifestyle tag-activity post_format-post-format-image\">" +
                        "<div class=\"imgwrap\"><p class=\"meta cat tranz\"><a href=\"#\" rel=\"category tag\">" + data.name + "</a></p>" +
                        "<a href=\"" + __HOSTCLIENT__ + "article/detail/" + NewsCath[a].uid + "\">" +
                            "<img src=\"" + __HOSTCLIENT__ + "/lontonglah/images/berita/SS" + NewsCath[a].uid + ".png\" class=\"tranz grayscale grayscale-fade wp-post-image\" alt=\"\" width=\"260\" height=\"320\">" +
                        "</a>" +
                        "<p class=\"meta counter\"><span class=\"meta likes\"><a href=\"#\" class=\"jm-post-like\" data-post_id=\"1042\" title=\"Like\">" +
                        "<i class=\"fa fa-heart-o\"></i>&nbsp;17</a></span>• <span class=\"meta views\"> <i class=\"fa fa-thumbs-o-up\"></i> 4116 </span></p></div>" +
                        "<div class=\"item_inn tranz\"><h2><a href=\"" + __HOSTCLIENT__ + "article/detail/" + NewsCath[a].uid + "\">" + NewsCath[a].title + "</a></h2>" +
                        "<div class=\"clearfix\"></div>" +
                        "<p class=\"meta meta_alt tranz\"> " + NewsCath[a].created_at_parsed + "</p>" +
                        "<p class=\"teaser\">" + NewsCath[a].content_short + "...</p>" +
                        "<p class=\"meta_more\"><a href=\"" + __HOSTCLIENT__ + "article/detail/" + NewsCath[a].uid + "\">Read article <i class=\"fa fa-long-arrow-right\"></i></a></p></div>" +
                        "</div>");
                }
            },
            error: function (response) {
                console.clear();
                console.log(response);
            }
        });
    });
</script>