<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Media helper.
 *
 * @package   Media
 * @author    David Stutz
 * @copyright (c) 2013 David Stutz
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
    public function add($mixed, $media = 'screen') {
        $path = DOCROOT . 'media' . Media::DS . 'css' . Media::DS;

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
        $content = '';
        foreach ($this->_files as $file) {
            $content .= file_get_contents($file);
        }
        
        file_put_contents($filepath, $content);
    }

    /**
     * Get the css file name and rebuilkd if needed.
     *
     * @param boolean echo
     * @return  string  rendered
     */
    public function render() {
        $filename = sha1(serialize($this->_files)) . '.css';
        $filepath = Kohana::$cache_dir . Media::DS . $filename;

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
