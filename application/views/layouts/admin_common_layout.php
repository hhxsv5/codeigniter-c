<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">

<meta name="renderer" content="webkit"> 
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<link rel="Bookmark" href="../assets/images/favicon.ico">
<link rel="Shortcut Icon" href="../assets/images/favicon.ico" />
<?php $staticUrl = base_url('assets') . '/';?>
<script type="text/javascript">
window.STATIC_URL = '<?php echo $staticUrl; ?>';
</script>
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo $staticUrl;?>lib/html5.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>lib/respond.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>lib/PIE_IE678.js"></script>
<![endif]-->
<link href="<?php echo $staticUrl;?>css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $staticUrl;?>css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $staticUrl;?>skin/default/skin.css" rel="stylesheet" type="text/css" id="skin" />
<link href="<?php echo $staticUrl;?>lib/Hui-iconfont/1.0.1/iconfont.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $staticUrl;?>css/style.css" rel="stylesheet" type="text/css" />
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title><?php echo $_ci_title;?></title>
<meta name="description" content="<?php echo $_ci_description; ?>">
<meta name="keywords" content="<?php echo $_ci_keywords; ?>">
<?php
if (isset($_ci_css) and is_array($_ci_css))
    foreach ($_ci_css as $css => $media) {
        $css = trim((string) $css);
        if ($css != '') {
            if (($pos = stripos($css, '.css')) === strlen($css) - 4)
                $css = substr($css, 0, $pos);
            $css = ltrim($css, '/\\');
            if (stripos($css, 'http://') === false and stripos($css, 'https://') === false)
                $css = $staticUrl . $css;
            echo '<link rel="stylesheet" type="text/css" href="', $css, '.css" media="' . $media . '">', PHP_EOL;
        }
    }
?>

<script type="text/javascript" src="<?php echo $staticUrl;?>lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>lib/layer/1.9.3/layer.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>lib/Validform/5.3.2/Validform.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/H-ui.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/H-ui.admin.js"></script>
<?php
if (isset($_ci_js) and is_array($_ci_js))
    foreach ($_ci_js as $js) {
        $js = trim((string) $js);
        if ($js != '') {
            if (($pos = stripos($js, '.js')) === strlen($js) - 3)
                $js = substr($js, 0, $pos);
            $js = ltrim($js, '/\\');
            
            if (stripos($js, 'http://') === false and stripos($js, 'https://') === false)
                $js = $staticUrl . $js;
            echo '<script type="text/javascript" src="', $js, '.js"></script>', PHP_EOL;
        }
    }
?>
</head>
<body>
    <?php $this->load->view($_ci_content);?>
</body>
</html>