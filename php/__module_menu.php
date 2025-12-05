<?php
namespace SIM\PDF;
use SIM;

const MODULE_VERSION		= '8.2.4';

DEFINE(__NAMESPACE__.'\MODULE_PATH', plugin_dir_path(__DIR__));

//module slug is the same as grandparent folder name
DEFINE(__NAMESPACE__.'\MODULE_SLUG', strtolower(basename(dirname(__DIR__))));

add_filter('sim_submenu_pdf_options', __NAMESPACE__.'\moduleOptions', 10, 2);
function moduleOptions($optionsHtml, $settings){
	ob_start();
	
    ?>
	<label>
		<input type='checkbox' name='full-screen' <?php if(isset($settings['full-screen'])){echo 'checked';}?>>
		Show PDF documents full screen if that is the only page content
	</label>
	<br>
	<br>
	<label>
		<input type='checkbox' name='pdf-print' <?php if(isset($settings['pdf-print'])){echo 'checked';}?>>
		Add a "Print to PDF" button option
	</label>
	<br>
	<br>
	<?php
	SIM\pictureSelector('logo', 'Logo for use in PDF headers', $settings, 'png');

	return $optionsHtml.ob_get_clean();
}