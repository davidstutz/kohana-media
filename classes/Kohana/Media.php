<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Media helper.
 * 
 * @package     Media
 * @author      David Stutz
 * @copyright   (c) 2013 - 2014 David Stutz
 * @license     http://opensource.org/licenses/bsd-3-clause
 */
class Kohana_Media {

    /**
     * @var	object	instance
     */
    protected static $_instance = NULL;

    /**
     * @var	array 	css
     */
    protected $_css = array();

    /**
     * @var	array 	js
     */
    protected $_js;

    /**
     * Get instance.
     *
     * @return	object	instance
     */
    public static function instance() {
        if (Media::$_instance === NULL) {
            Media::$_instance = new Media();
            
            if (!class_exists('CSSMin')) {
                require_once MODPATH . 'media/vendor/cssmin-v3.0.1.php';
            }

            if (!class_exists('JSMin')) {
                require_once MODPATH . 'media/vendor/jsmin-1.1.1.php';
            }
        }

        return Media::$_instance;
    }

    /**
     * Get the CSS media class to add styles.
     *
     * @return  object  css
     */
    public function css($media = 'screen') {
        if (!isset($this->_css[$media])) {
            $this->_css[$media] = new Media_CSS();
        }

        return $this->_css[$media];
    }

    /**
     * Get the JS media class to add scripts.
     *
     * @return  object  js
     */
    public function js() {
        if (!is_object($this->_js)) {
            $this->_js = new Media_JS();
        }

        return $this->_js;
    }

}
