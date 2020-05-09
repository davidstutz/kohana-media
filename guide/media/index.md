# Usage

The configuration file allows to adjust the paths to the CSS and Javascript files. By default, the following paths are used:

    return array(
        'css' => DOCROOT . 'media' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR,
        'js' => DOCROOT . 'media' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR,
    );

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