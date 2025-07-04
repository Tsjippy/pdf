<?php
namespace SIM\PDF;
use SIM;

//only load when checked
if(!SIM\getModuleOption(MODULE_SLUG, 'full_screen')){
    return;
}

function checkIfOnlyPdf($content){
    //If the string starts with 0 or more spaces, then a <p> followed by a hyperlink ending in .pdf then the download text ending an optional download button followed with 0 or more spaces.
    $pattern = '/^\s*<p><a href="(.*?\.pdf)">([^<]*)<\/a>(.*\.pdf">Download<\/a>)?<\/p>\s*$/i';
    
    //Execute the regex
    preg_match($pattern, $content, $matches);

    //If an url exists it means there is only one pdf on this page
    if(isset($matches[2])){
        return $matches;
    }

    return false;
}

//Show PDFs full screen
add_filter( 'the_content',  __NAMESPACE__.'\showFullScreen', 90);
function showFullScreen( $content ) {
    $postId 	= get_the_ID();
    $content	= str_replace('<p>&nbsp;</p>', '', $content);

    $matches    = checkIfOnlyPdf($content);
    
    //If an url exists it means there is only a pdf on this page
    if($matches){
        do_action('sim-pdf-before-fullscreen', $postId);

        //Get the url to the pdf
        $url    = $matches[1];
        $text   = str_replace('-', ' ', explode('.', end(explode("/", $matches[2])))[0]);

        replaceAnchorWithContainer($content, $matches[0], $url, $text);
    }else{
        preg_match_all("/<a.*? href=(\"|')(.*?.pdf)(\"|').*?>(.*?)<\/a>/i", $content, $anchors);

        foreach($anchors[0] as $index=>$raw){
            replaceAnchorWithContainer($content, $raw, $anchors[2][$index], $anchors[4][$index], true);
        }
    }
	
	return $content;
}

function replaceAnchorWithContainer(&$content, $raw, $url, $text, $hidden=''){

    if(!empty($hidden)){
        $class  = 'hidden';
    }

    /* SHOW THE PDF FULLSCREEN AND SHOW A CLOSE BUTTON */
    if(wp_is_mobile()){
        $style          = "left: 90%; top: 13px;";
        $close          = "X";
        $objectStyle    = "min-width: 100vw;";
    }else{
        $style          = "left: 80%; top: 13px;";
        $close          = "Close PDF";
        $objectStyle    = "height: -webkit-fill-available; width:100vw;";
    }

    $id                 = strtolower(str_replace(' ', '_', $text));
    // The button
    ob_start();
    ?> 
        <div>
            <button class='button small pdf-fullscreen' type="button" style='margin-top:10px;' data-target="<?php echo $id;?>">Show <?php echo $text;?></button>
        </div>
    <?php
    
    // Replace the url with a button
    $content    = str_replace($raw, ob_get_clean(), $content);

    // Add the container to the top
    ob_start();
    ?>
    <div id="<?php echo $id;?>" class='full-screen-pdf-wrapper <?php echo $class;?>' style='z-index: 9999999;position: absolute;top: 0;left: 0;'>
        <div style='position: absolute; top: 0; left: 0; z-index: 99991; width:100vw; height:-webkit-fill-available; min-height:100vh; background-color: white;margin-top: -33px;' >
            <button type='button' id='close-full-screen' class='button small' style='position: sticky; z-index: 99992; <?php echo $style;?>' onclick='this.closest(".full-screen-pdf-wrapper").classList.add("hidden");'>
                <?php echo $close;?>
            </button>
            <div class='loadergif_wrapper' style='display: flex;justify-content: center;align-items: center;height: 100vh;'>
                <img class='loadergif' src='<?php echo SIM\LOADERIMAGEURL;?>' loading='lazy'>
                Loading PDF...
            </div>
            <iframe class='' src='<?php echo $url;?>' style='<?php echo $objectStyle;?>' type='application/pdf' onload="this.closest('.full-screen-pdf-wrapper').querySelector('.loadergif_wrapper').classList.add('hidden')"></iframe>
        </div>
    </div>
    <?php
    
    $content    = ob_get_clean().$content;
}

// add url to signal message
add_filter('sim_signal_post_notification_message', __NAMESPACE__.'\postNotification', 10, 2);
function postNotification( $excerpt, $post){
    // if this is a fullscreen pdf always return the url
    if(checkIfOnlyPdf($post->post_content)){
        return $excerpt."\n\n".get_permalink($post);
    }

    return $excerpt;
}