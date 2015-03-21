<?php
$this -> load -> view('include/header');
?>
</head>
<body>
<?php
$this -> load -> library(array('session', 'mobile_detect'));
$containerWidth = $this -> mobile_detect -> isMobile() ? '95%' : '88%';
?>
<div class="container" style="width: <?php echo $containerWidth; ?>">
<?php
$this -> load -> view($view_content);
?>
<?php
$this -> load -> view('include/footer');
?>
