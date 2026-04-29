<?php
namespace TSJIPPY\PDF;

use function TSJIPPY\addElement;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AdminMenu extends \TSJIPPY\ADMIN\SubAdminMenu{

    public function __construct($settings, $name){
        parent::__construct($settings, $name);
    }

    public function settings($parent){

        $label = addElement('label', $parent, [], 'Show PDF documents full screen if that is the only page content');

        $attributes = ['type' => 'checkbox', 'name' => 'full-screen'];
        if(isset($this->settings['full-screen'])){
            $attributes['checked'] = 'checked';
        }

        addElement('input', $label, $attributes, '', 'afterBegin');

        addElement('br', $parent);
        addElement('br', $parent);

        $label = addElement('label', $parent, [], 'Add a "Print to PDF" button option to posts');

        $attributes = ['type' => 'checkbox', 'name' => 'pdf-print'];
        if(isset($this->settings['pdf-print'])){
            $attributes['checked'] = 'checked';
        }
        addElement('input', $label, $attributes, '', 'afterBegin');

        addElement('br', $parent);
        addElement('br', $parent);

        $this->pictureSelector('logo', 'Logo for use in PDF headers', $parent, 'png');

        return true;
    }

    public function emails($parent){
        return false;
    }

    public function data($parent=''){

        return false;
    }

    public function functions($parent){

        return false;
    }

}