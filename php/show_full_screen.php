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
add_filter( 'the_content',  __NAMESPACE__.'\showFullScreen');
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
        preg_match_all("/<a.*? href=(\"|')(.*?).pdf(\"|').*?>(.*?)<\/a>/i", $content, $anchors);

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
    ob_start();

    if(wp_is_mobile()){
        $style          = "left: 90%;";
        $close          = "X";
        $objectStyle    = "";
    }else{
        $style          = "left: 80%; top: 13px;";
        $close          = "Close PDF";
        $objectStyle    = "style='height: -webkit-fill-available; width:100vw; margin-top: -30px;'";
    }

    ?> 
        <div>
            <button class='button small' onclick="this.parentElement.querySelector('.full-screen-pdf-wrapper').classList.remove('hidden')" style='margin-top:10px;'>Show <?php echo $text;?></button>
            <div class='full-screen-pdf-wrapper <?php echo $class;?>'>
                <div style='position: absolute; top: 0; left: 0; z-index: 99991; width:100vw; height:-webkit-fill-available; background-color: white;' >
                    <button type='button' id='close-full-screen' class='button small' style='position: sticky; z-index: 99992; <?php echo $style;?>' onclick='this.closest(".full-screen-pdf-wrapper").classList.add("hidden");'>
                        <?php echo $close;?>
                    </button>
                    <object data='<?php echo $url;?>' <?php echo $objectStyle;?> type='application/pdf'></object>
                </div>
            </div>
        </div>
    <?php
    
    // Replace the url with a button
    $content    = str_replace($raw, ob_get_clean(), $content);
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