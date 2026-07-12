<?php

namespace TSJIPPY\PDF;

use function TSJIPPY\addElement;

if (! defined('ABSPATH')) {
    exit;
}

class AdminMenu extends \TSJIPPY\ADMIN\SubAdminMenu
{

    /**
     * AdminMenu constructor.
     *
     * @param array $settings The settings for the plugin
     * @param string $name The name of the plugin
     */
    public function __construct($settings, $name)
    {
        parent::__construct($settings, $name);
    }

    /**
     * Add the settings page to the admin menu
     *
     * @param string $parent The parent menu slug
     * @return bool True if the settings page was added, false otherwise
     */
    public function settings($parent)
    {

        $label = addElement('label', $parent, [], 'Show PDF documents full screen if that is the only page content');

        $attributes = ['type' => 'checkbox', 'name' => 'full-screen'];
        if (isset($this->settings['full-screen'])) {
            $attributes['checked'] = 'checked';
        }

        addElement('input', $label, $attributes, '', 'afterBegin');

        addElement('br', $parent);
        addElement('br', $parent);

        $label = addElement('label', $parent, [], 'Add a "Print to PDF" button option to posts');

        $attributes = ['type' => 'checkbox', 'name' => 'pdf-print'];
        if (isset($this->settings['pdf-print'])) {
            $attributes['checked'] = 'checked';
        }
        addElement('input', $label, $attributes, '', 'afterBegin');

        addElement('br', $parent);
        addElement('br', $parent);

        $this->pictureSelector('logo', 'Logo for use in PDF headers', $parent, 'png');

        return true;
    }

    /**
     * Function to display the emails page
     *
     * @param   string  $parent The parent menu slug
     * 
     * @return  bool            True if the emails page was displayed, false otherwise
     */
    public function emails($parent)
    {
        return false;
    }

    /**
     * Function to display the emails page
     *
     * @param   string  $parent The parent menu slug
     * 
     * @return  bool            True if the emails page was displayed, false otherwise
     */
    public function data($parent = '')
    {

        return false;
    }

    /**
     * Add the functions page to the admin menu
     *
     * @param string $parent The parent menu slug
     * 
     * @return bool True if the functions page was added, false otherwise
     */
    public function functions($parent)
    {

        return false;
    }
}
