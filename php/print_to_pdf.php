<?php
namespace SIM\PDF;
use SIM;

//only load this if the pdf print is enabled
if(!SIM\getModuleOption(MODULE_SLUG, 'pdf-print')){
	return;
}

function createPagePdf(){
	global $post;
	
	$pdf = new PdfHtml();
	$pdf->SetFont('Arial','B',15);
	
	$pdf->skipfirstpage = false;
	
	//Set the title of the document
	$pdf->SetTitle($post->post_title);
	$pdf->AddPage();

    $pdf->skipFirstPage = false;
    $pdf->Header();

	do_action('sim-before-print-content', $post, $pdf);
	
	$pdf->WriteHTML($post->post_content);

	do_action('sim-after-print-content', $post, $pdf);
	
	$pdf->printpdf();
}

// Add print to PDF button
add_filter( 'the_content', __NAMESPACE__.'\printPdfButton');
function printPdfButton( $content ) {
    //Print to screen if the button is clicked
    if( isset($_POST['print-as-pdf'])){
		createPagePdf();
	}
    
    //pdf button
    if(!empty(get_post_meta(get_the_ID(), 'add-print-button',true))){
        $content .= "<div class='print-as-pdf-div' style='float:right;'>";
            $content .= "<form method='post' id='print-as-pdf_form'>";
                $content .= "<button type='submit' class='button' name='print-as-pdf'>Print this page</button>";
            $content .= "</form>";
        $content .= "</div>";
    }
	
	return $content;
}

// Add fields to frontend content form
add_action('sim_page_specific_fields', __NAMESPACE__.'\pageSpecificFields');
function pageSpecificFields($postId){
    ?>
	<div id="add-print-button-div" class="frontend-form">
        <h4>PDF button</h4>
        <label>
            <input type='checkbox'  name='add-print-button' value='1' <?php if(!empty(get_post_meta($postId, 'add-print-button', true))){echo 'checked';}?>>
            Add a 'Save as PDF' button
        </label>
    </div>
    <?php
}

// Save the option to have a pdf button
add_action('sim_after_post_save', __NAMESPACE__.'\afterPostSave');
function afterPostSave($post){
    //PDF button
    if(isset($_POST['add-print-button'])){
        //Store pdf button
        if(empty($_POST['add-print-button'])){
            $value = false;
        }else{
            $value = true;
        }
        update_post_meta($post->ID, 'add-print-button', $value);
    }else{
        delete_post_meta($post->ID, 'add-print-button');
    }
}

add_filter('sim-single-template-bottom', __NAMESPACE__.'\singleTemplateBottom', 10, 2);
function singleTemplateBottom($html, $postType){
    return "<div class='print-as-pdf-div'>
        <form method='post' id='print-as-pdf_form'>
            <button type='submit' class='button' name='print-as-pdf' id='print-as-pdf' style='margin-left: 10px;'>Print this $postType</button>
        </form>
    </div>";
}