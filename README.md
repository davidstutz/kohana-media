# Media Kohana Module.

This a simple media/assets module for Kohana.

Documentation can also be found in the guide/ subfolder or using Kohana's [Userguide](https://github.com/kohana/userguide) module.

## Usage

**Note:** Currently the plugin expects the CSS and JS files to be found at the following paths:

    $path = DOCROOT . 'media' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR // CSS
    $path = DOCROOT . 'media' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR // JS

These paths are hardcoded. I consider adding a configuration options for setting the paths.

Used media - both CSS and JS - is managed by the current Media instance:

    Media::instance()

To add CSS styles of a given media type (for example `screen` or `print`):

    Media::instance()->css('print')->add('bootstrap.min.css');
    // Or add mutliple at once:
    Media::instance()->css('screen')->add(array(
        'bootstrap.min.css',
        'application.css',
        // ...
    ));

To add JS scripts (including dependencies):

    // Bootstrap depends on JQuery.
    Media::instance()->js()->add('bootstrap.min.js', array('jquery.min.js'));
    // Multiple at once:
    Media::instance()->js()->add(array(
        'bootstrap.min.js' => array('jquery.min.js'),
        'bootstrap-datepicker.js' => array('jquery.min.js', 'bootstrap.min.js'),
    ));

Multiple dependencies can easily be set using an array and will be processed first. The module will care about processing each file only once.

The media files will be bundled and minified in one file and stored in `Kohana::$cache_dir`:

    <script type="text/javascript" src="<?php echo URL::base() . 'cache/' . Media::instance()->js()->render(); ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() . 'cache/' . Media::instance()->css('screen')->render(); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() . 'cache/' . Media::instance()->css('print')->render(); ?>" media="print">

**Note:** `Kohana::$cache_dir` is usually set to application/cache. This will not work for css and js due to the rewriting rules (see the `.htaccess` file). So `Kohana::$cache_dir` should be changed or the code of the CSS and JS class has to be adjusted.

## License

Copyright (c) 2013, David Stutz
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
* Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
