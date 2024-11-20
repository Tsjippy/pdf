<?php
namespace SIM\PDF;
use SIM;

const MODULE_VERSION		= '8.0.3';

DEFINE(__NAMESPACE__.'\MODULE_PATH', plugin_dir_path(__DIR__));

//module slug is the same as grandparent folder name
DEFINE(__NAMESPACE__.'\MODULE_SLUG', strtolower(basename(dirname(__DIR__))));

add_filter('sim_submenu_options', __NAMESPACE__.'\moduleOptions', 10, 3);
function moduleOptions($optionsHtml, $moduleSlug, $settings){
	//module slug should be the same as grandparent folder name
	if($moduleSlug != MODULE_SLUG){
		return $optionsHtml;
	}

	ob_start();
	
    ?>
	<label>
		<input type='checkbox' name='full_screen' <?php if(isset($settings['full_screen'])){echo 'checked';}?>>
		Show PDF documents full screen if that is the only page content
	</label>
	<br>
	<br>
	<label>
		<input type='checkbox' name='pdf_print' <?php if(isset($settings['pdf_print'])){echo 'checked';}?>>
		Add a "Print to PDF" button option
	</label>
	<br>
	<br>
	<?php
	SIM\pictureSelector('logo', 'Logo for use in PDF headers', $settings, 'jpe');

	return ob_get_clean();
}