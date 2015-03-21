<?php
if (isset($view_css)) {
	if (is_array($view_css)) {
		foreach ($view_css as $value) {
			$value = trim((string)$value);
			if ($value != '') {
				if (($pos = stripos($value, '.css')) === strlen($value) - 4) {
					$value = substr($value, 0, $pos);
				}
				echo '<link rel="stylesheet" type="text/css" href="', base_url('static/css/' . $value), '.css">';
			}
		}
	} else {
		$view_css = trim((string)$view_css);
		if ($view_css != '') {
			if (($pos = stripos($view_css, '.css')) === strlen($view_css) - 4) {
				$view_css = substr($view_css, 0, $pos);
			}
			echo '<link rel="stylesheet" type="text/css" href="', base_url('static/css/' . $view_css), '.css">';
		}
	}
}
?>