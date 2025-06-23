<?php
namespace SIM\PDF;
use SIM;

add_action( 'wp_enqueue_scripts', __NAMESPACE__.'\registerPDFScripts');

function registerPDFScripts(){
   wp_enqueue_script_module('pdf', '//mozilla.github.io/pdf.js/build/pdf.mjs');
   wp_enqueue_script_module('sim_pdf_script', SIM\pathToUrl(MODULE_PATH.'js/pdf.min.js'), array('pdf'), MODULE_VERSION, true);
}