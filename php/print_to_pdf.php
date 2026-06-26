<?php

namespace TSJIPPY\PDF;

use TSJIPPY;

if (! defined('ABSPATH')) {
    exit;
}

//only load this if the pdf print is enabled
if (!SETTINGS['pdf-print'] ?? false) {
    return;
}

function createPagePdf()
{
    global $post;

    $pdf = new PdfHtml();
    $pdf->SetFont('Arial', 'B', 15);

    //Set the title of the document
    $pdf->SetTitle($post->post_title);
    $pdf->AddPage();

    $pdf->skipFirstPage = false;
    $pdf->Header();

    do_action('tsjippy-pdf-before-print-content', $post, $pdf);

    $pdf->WriteHTML($post->post_content);

    do_action('tsjippy-pdf-after-print-content', $post, $pdf);

    $pdf->printpdf();
}

/** Add print to PDF button
 * @param string $content The content of the page
 * @return string The content with the print to PDF button if enabled
 */
add_filter('the_content', __NAMESPACE__ . '\printPdfButton');
/**
 * Adds the print to PDF button to the page content
 *
 * @param string $content The page content
 *
 * @return string The updated page content
 */
function printPdfButton($content)
{
    //Print to screen if the button is clicked
    if (isset($_POST['print-as-pdf'])) {
        createPagePdf();
    }

    //pdf button
    if (!empty(get_post_meta(get_the_ID(), "tsjippy_add-print-button", true))) {
        $content .= "<div class='print-as-pdf-div' style='float:right;'>";
        $content .= "<form method='post' id='print-as-pdf_form'>";
        $content .= "<button type='submit' class='button' name='print-as-pdf'>Print this page</button>";
        $content .= "</form>";
        $content .= "</div>";
    }

    return $content;
}

// Add fields to frontend content form
add_action('ttsjippy-frontend-content-page-specific-fields', __NAMESPACE__ . '\pageSpecificFields');
/**
 * Adds the fields for the print to PDF button to the frontend content form
 *
 * @param int $postId The ID of the post being edited
 *
 * @return void
 */
function pageSpecificFields($postId)
{
?>
    <div id="add-print-button-div" class="frontend-form">
        <h4>PDF button</h4>
        <label>
            <input
                type='checkbox'
                name='add-print-button'
                value='1'
                <?php if (!empty(get_post_meta($postId, 'tsjippy_add-print-button', true))) {
                    echo 'checked';
                } ?>>
            Add a 'Save as PDF' button
        </label>
    </div>
<?php
}

// Save the option to have a pdf button
/**
 * Allow comments
 * 
 * @param   \WP_Post    $post       The new or updated post
 * @param   object      $object     FrontEndContent Instance
 * @param   array       $request    The sanitized request data
 */
add_action('tsjippy-frontend-content-after-post-save', __NAMESPACE__ . '\afterPostSave', 10, 3);
/**
 * Saves the option to have a print to PDF button
 *
 * @param \WP_Post $post The post being saved
 *
 * @return void
 */
function afterPostSave($post, $object, $request)
{
    //PDF button
    if (isset($request['add-print-button'])) {
        //Store pdf button
        if (empty($request['add-print-button'])) {
            $value = false;
        } else {
            $value = true;
        }
        update_post_meta($post->ID, 'tsjippy_add-print-button', $value);
    } else {
        delete_post_meta($post->ID, 'tsjippy_add-print-button');
    }
}


add_filter('tsjippy-single-template-bottom', __NAMESPACE__ . '\singleTemplateBottom', 10, 2);

/**
 * Adds the print to PDF button to the single template bottom
 *
 * @param string $html The HTML content
 * @param string $postType The post type
 *
 * @return string The HTML content with the print to PDF button
 */
function singleTemplateBottom($html, $postType)
{
    return "<div class='print-as-pdf-div'>
        <form method='post' id='print-as-pdf_form'>
            <button type='submit' class='button' name='print-as-pdf' id='print-as-pdf' style='margin-left: 10px;'>Print this $postType</button>
        </form>
    </div>";
}


add_action('tsjippy-forms-results-table-footer', function ($formWrapper, $object) {
    $form   = TSJIPPY\addElement('form', $formWrapper, ['method' => "post", 'class' => "export-form", 'id' => "export-pdf"]);

    TSJIPPY\addElement('button', $form, ['class' => "button button-primary", 'type' => "submit", 'name' => "export-pdf"], 'Export data to pdf');
}, 10, 2);
