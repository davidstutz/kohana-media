<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Javascript helper.
 * 
 * @package     Media
 * @author      David Stutz
 * @copyright   (c) 2013 - 2014 David Stutz
 * @license     http://opensource.org/licenses/bsd-3-clause
 */
class Kohana_Media_JS {

    /**
     * @var array   js
     */
    protected $_files = array();

    /**
     * Add script.
     *
     * @param string  file
     * @param array   dependencies
     * @return  object  instance
     */
    public function add($mixed, $dependencies = array()) {
        $path = Kohana::$config->load('media.js');
        
        if (is_array($mixed)) {
            foreach ($mixed as $file => $array) {
                if (is_array($array)) {
                    $this->_files[$path. $file] = array();
                    foreach ($array as $dependency) {
                        $this->_files[$path . $file][] = $path . $dependency;
                    }
                }
                else {
                    $this->_files[$path . DIRECTORY_SEPARATOR . $array] = array();
                }
            }
        }
        else {
            foreach ($dependencies as &$file) {
                $file = $path . $file;
            }

            $this->_files[$path . $mixed] = $dependencies;
        }

        return $this;
    }

    /**
     * Rebuild the cache file.
     *
     * @param   string  filepath
     */
    protected function _rebuild($filepath) {
        $added = array();
        $content = '';

        foreach ($this->_files as $file => $array) {
            foreach ($array as $dependency) {
                if (FALSE === array_key_exists($dependency, $added)) {
                    $content .= file_get_contents($dependency);
                    $added[$dependency] = $dependency;
                }
            }
            
            if (FALSE === array_key_exists($file, $added)) {
                $content .= file_get_contents($file);
                $added[$file] = $file;
            }
        }

        file_put_contents($filepath, $content);
    }

    /**
     * Renders styles and scripts.
     *
     * @param boolean echo
     * @return  string  rendered
     */
    public function render() {
        $filename = sha1(serialize($this->_files)) . '.js';
        $filepath = Kohana::$cache_dir . DIRECTORY_SEPARATOR . $filename;

        if (file_exists($filepath)) {
            $mtime = filemtime($filepath);

            foreach ($this->_files as $file => $dependencies) {
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
