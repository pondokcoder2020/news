<?php
require 'config.php';
?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" lang="en-US"> <![endif]--><!--[if IE 7]>
<html id="ie7" lang="en-US"> <![endif]--><!--[if IE 8]>
<html id="ie8" lang="en-US"> <![endif]--><!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html lang="en-US">
<!--<![endif]-->
<!-- Mirrored from capethemes.com/demo/stylishmag/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 12 Jun 2021 02:27:32 GMT -->
<!-- Added by HTTrack -->
<?php require 'head.php'; ?>
<body class="home page-template page-template-homepage page-template-homepage-php page page-id-4224 page-parent upper">
<?php require 'header.php'; ?>
<div class="clearfix"></div>
<?php
    if(empty(__PAGES__[0])) {
        require 'pages/home.php';
    } else {
        require 'pages/home.php';
    }
?>
<?php require 'footer.php'; ?>
<div class="scrollTo_top" style="display: block">
    <a href="#" title="Scroll to top"><i class="fa fa-angle-up rad"></i></a>
</div>
<script src='<?php echo __HOSTCLIENT__; ?>wp-includes/js/jquery/jqueryb8ff.js?ver=1.12.4' type='text/javascript'>
</script>
<script type='text/javascript'>
    Main.boot([]);
</script>
<script defer
        src="<?php echo __HOSTCLIENT__; ?>wp-content/cache/autoptimize/1/js/autoptimize_6fdfb7d6c89fbf2ddba1b81630748137.js"
        type="text/javascript">
</script>
<!-- Mirrored from capethemes.com/demo/stylishmag/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 12 Jun 2021 02:27:32 GMT -->
</body>
</html>