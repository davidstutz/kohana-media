<?php defined('SYSPATH') or die('No direct script access.');

use MatthiasMullie\Minify;

/**
 * CSS helper.
 * 
 * @package     Media
 * @author      David Stutz
 * @copyright   (c) 2013 - 2014 David Stutz
 * @license     http://opensource.org/licenses/bsd-3-clause
 */
class Kohana_Media_CSS {

    /**
     * @var array
     */
    protected $_files = array();

    /**
     * Add style.
     *
     * @param string  file
     * @param array   dependencies
     * @return  object  instance
     */
    public function add($mixed) {
        $path = Kohana::$config->load('media.css');

        if (is_array($mixed)) {
            foreach ($mixed as $file) {
                $this->_files[] = $path . $file;
            }
        }
        else {
            $this->_files[] = $path . $mixed;
        }

        return $this;
    }

    /**
     * Rebuild the cache file.
     * 
     * @param   string  filepath 
     */
    protected function _rebuild($filepath) {
        $minify = Kohana::$config->load('media.minify_css');
        $minifier = new Minify\CSS();
        $content = '';

        foreach ($this->_files as $file) {
            if ($minify) {
                $minifier->add($file);
            }
            else {
                $content .= file_get_contents($file);
            }
        }
        
        if ($minify) {
            file_put_contents($filepath, $minifier->minify());
        }
        else {
            file_put_contents($filepath, $content);
        }
    }

    /**
     * Get the css file name and rebuilkd if needed.
     *
     * @param boolean echo
     * @return  string  rendered
     */
    public function render() {
        $filename = sha1(serialize($this->_files)) . '.css';
        $filepath = Kohana::$cache_dir . DIRECTORY_SEPARATOR . $filename;

        if (file_exists($filepath)) {
            $mtime = filemtime($filepath);
            
            foreach ($this->_files as $file) {
                if (filemtime($file) > $mtime) {
                    $this->_rebuild($filepath);
                }
            }
        }

        if (!file_exists($filepath)) {
            $this->_rebuild($filepath);
        }

        return $filename;
    }

}
