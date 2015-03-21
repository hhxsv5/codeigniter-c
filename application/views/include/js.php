<script src="<?php echo base_url('static/js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('static/js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript">
var BASE_URL = '<?php echo base_url();?>',
if (top.location.href.indexOf(BASE_URL) != 0) {
	top.location.href = BASE_URL;
}
</script>
<script type="text/javascript" src="<?php echo base_url('static/js/common.js')?>"></script>
<?php
if (isset($view_js)) {
	if (is_array($view_js)) {
		foreach ($view_js as $value) {
			$value = trim((string)$value);
			if ($value != '') {
				if(($pos = stripos($value, '.js')) === strlen($value) - 3){
					$value = substr($value, 0, $pos);
				}
				echo '<script type="text/javascript" src="', base_url('static/js/' . $value), '.js"></script>';
			}
		}
	} else {
		$view_js = trim((string)$view_js);
		if ($view_js != '') {
			if(($pos = stripos($view_js, '.js')) === strlen($view_js) - 3){
				$view_js = substr($view_js, 0, $pos);
			}
			echo '<script type="text/javascript" src="', base_url('static/js/' . $view_js), '.js"></script>';
		}
	}
}
?>