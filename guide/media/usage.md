# Usage

**Note:** Currently the plugin expects the CSS and JS files to be found at the following paths:

    $path = DOCROOT . 'media' . Media::DS . 'css' . Media::DS // CSS
    $path = DOCROOT . 'media' . Media::DS . 'js' . Media::DS // JS

These paths are hardcoded. I consider adding a configuration options for setting the paths.

Used medua - both CSS and JS - is managed by the current Media instance:

    Media::instance()

To add CSS styles of a given media type (meaning `screen` or `print` for example):

    Media::instance()->css('print')->add('bootstrap.min.css');
    // OR add mutliple at once:
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

The dependencies will be processed first. The module will care about processing each file only once.

The media files will be bundled and minified in one file and stored in `Kohana::$cache_dir`:

    <script type="text/javascript" src="<?php echo URL::base() . 'cache/' . Media::instance()->js()->render(); ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() . 'cache/' . Media::instance()->css('screen')->render(); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() . 'cache/' . Media::instance()->css('print')->render(); ?>" media="print">
