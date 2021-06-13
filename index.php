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
    $lastExist;
if(empty(__PAGES__[0])) {
    require 'pages/home.php';
} else {
    if(implode('/', __PAGES__) == 'system/logout') {
        require 'pages/system/logout.php';
    } else {
        /*echo '<pre>';
        print_r($_SESSION['akses_halaman_link']);
        echo '</pre>';*/
        if(is_dir('pages/' . implode('/', __PAGES__))) {
            $isInAccess = '';
            $allowAccess = true;

            if($allowAccess) {
                require 'pages/' . implode('/', __PAGES__) . '/index.php';
            } else {
                if(!$allowAccess) {
                    require 'pages/system/403.php';
                } else {
                    require 'pages/system/404.php';
                }
            }
        } else {
            if(file_exists('pages/' . implode('/', __PAGES__) . '.php')) {
                require 'pages/' . implode('/', __PAGES__) . '.php';
            } else {
                $isFile = 'pages';
                $isInAccess = '';
                $allowAccess = true;

                foreach (__PAGES__ as $key => $value) {
                    if(file_exists($isFile . '/' . $value . '.php')) {
                        $lastExist = $isFile . '/' . $value . '.php';
                    }
                    $isFile .= '/' . $value;
                }

                if(isset($lastExist) && $allowAccess) {
                    //echo $allowAccess;
                    require $lastExist;
                } else {
                    if(!$allowAccess) {
                        require 'pages/system/403.php';
                    } else {
                        require 'pages/system/404.php';
                    }
                }
            }
        }
    }
}
?>
<?php require 'footer.php'; ?>
<div class="scrollTo_top" style="display: block">
    <a href="#" title="Scroll to top"><i class="fa fa-angle-up rad"></i></a>
</div>
<script type="text/javascript">
    var __HOSTAPI__ = <?php echo json_encode(__HOSTAPI__); ?>;
    var __HOSTCLIENT__ = <?php echo json_encode(__HOSTCLIENT__); ?>;
    var __PAGES__ = <?php echo json_encode(__PAGES__); ?>;
    var date = new Date;
    var seconds = date.getSeconds();
    var minutes = date.getMinutes();
    var hour = date.getHours();

    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();
</script>
<script src='<?php echo __HOSTCLIENT__; ?>wp-includes/js/jquery/jqueryb8ff.js?ver=1.12.4' type='text/javascript'></script>
<script src='<?php echo __HOSTCLIENT__; ?>scripts/jquery.min.js' type='text/javascript'></script>
<?php
require 'scripts/menu.php';
require 'scripts/post.php';

if(empty(__PAGES__[0])) {
    include 'scripts/home.php';
} else {
    if(is_dir('scripts/' . implode('/', __PAGES__))) {
        include 'scripts/' . implode('/', __PAGES__) . '/index.php';
    } else {
        if(file_exists('scripts/' . implode('/', __PAGES__) . '.php')) {
            include 'scripts/' . implode('/', __PAGES__) . '.php';
        } else {
            if(isset($lastExist)) {
                $getScript = explode('/', $lastExist);
                $getScript[0] = 'scripts';
                include implode('/', $getScript);
            } else {
                include 'scripts/system/404.php';
            }
        }
    }
}
?>
<script defer
        src="<?php echo __HOSTCLIENT__; ?>wp-content/cache/autoptimize/1/js/autoptimize_6fdfb7d6c89fbf2ddba1b81630748137.js"
        type="text/javascript">
</script>
<!-- Mirrored from capethemes.com/demo/stylishmag/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 12 Jun 2021 02:27:32 GMT -->
</body>
</html>