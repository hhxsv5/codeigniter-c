<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" context="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="description" content="">
<title><?php echo empty($view_title) ? '未定义的标题' : $view_title;?></title>
<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('static/css/bootstrap-theme.min.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('static/css/common.css'); ?>" rel="stylesheet">
<?php $this -> load -> view('include/css');?>