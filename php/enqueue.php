<?php
namespace TSJIPPY\PDF;
use TSJIPPY;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__.'\registerPDFScripts');

function registerPDFScripts(){
   wp_enqueue_script_module('pdf', '//mozilla.github.io/pdf.js/build/pdf.mjs');
   wp_enqueue_script_module('tsjippy_pdf_script', TSJIPPY\pathToUrl(PLUGINPATH.'js/pdf.min.js'), array('pdf'), PLUGINVERSION, ['in_footer' => true]);
}