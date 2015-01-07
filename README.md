render-3d
=========

Wrapper library to help render common 3d file formats.

Requirements
============

This requires a few things to work.

  * The stl2pov python script, part of [stltools](http://rsmith.home.xs4all.nl/software/stltools.html)
  * For **Open SCAD** files:  Requires [Open SCAD](http://www.openscad.org/)
  * For the actual rendering, requires [POV Ray](http://www.povray.org/)
  * [Composer](https://getcomposer.org/)

Installation
============

If you are using composer, just add `"libre3d/render-3d": "~1.0.0"` to the `require` section, then run `composer update`.

Or if you do not use composer, clone this repository.  Then [get composer](http://getcomposer.com).  Then run
`composer install` from the root folder of this library to install dependencies.


Usage
=====

If you don't already have the composer vendor autoload PHP file included in your project, you will need to include it like:

```php
require 'render-3d/vendor/autoload.php';
```

That path may need to be adjusted.

Then you will need to initialize the Render3d object and let it know the locations of a few things (note that this is
a quick example, there are many options and different ways that files can be rendered using this library):

```php

$render3d = new \Libre3d\Render3d\Render3d();

// this is the working directory, where it will put any files used during the render process, as well as the final
// rendered image.
$render3d->workingDir('/path/to/working/folder/');

// Set paths to the executables on this system
$render3d->executable('stl2pov', '/path/to/stl2pov');
$render3d->executable('openscad', '/path/to/openscad');
$render3d->executable('povray', '/path/to/povray');

try {
	// This will copy in your starting file into the working DIR if you give the full path to the starting file.
	// This will also set the fileType for you.
	$render3d->filename('/path/to/starting/stlfile.stl');

	// Render!  This will do all the necessary conversions as long as the render engine (in this
	// case, the default engine, PovRAY) "knows" how to convert the file into a file it can use for rendering.
	// Note that this is a multi-step process that can be further broken down if you need it to.
	$renderedImagePath = $render3d->render('povray');

	echo "Render successful!  Rendered image will be at $renderedImagePath";
} catch (\Exception $e) {
	echo "Render failed :( Exception: ".$e->getMessage();
}
```

The main workflow:
==================

  * Convert to STL file format (if not starting with an STL file)
  * Convert the STL to a POVRay file format using the `stl2pov` library.
  * Render an image using povray and a common scene template.

Options
=======

The `$Render3d->render()` method takes an optional second parameter for `$options`, which is an array of options.  You
can also set the options before hand calling `$Render3d->options(['option1' => 'val1']);`

Here are a few options of note:
  * **buffer**  This controls what is done with output from the commands run on the command line.  The valid values are:
    * `Render3d::BUFFER_OFF` - Default value.  Nothing is displayed and nothing is buffered.
    * `Render3d::BUFFER_ON` - Buffers the output, and saves so that you can later retrieve it with `$Render3d->getBufferAndClean()`
    * `Render3d::BUFFER_STD_OUT` - Sends any output directly to std out (sends to the browser or console)
  * **width** - The width of the rendered image, in pixels.  Defaults to 1600
  * **height** - The height of the rendered image, in pixels.  Defaults to 1200

Version & Changelog
===================

We adhere to the [Semantic Versioning Specification (SemVer)](http://semver.org/).  If we ever introduce breaking changes
into a minor or patch release, please let us know!

**Changelog:**  We use Github issues and milestones to keep track of changes from version to version.  To see what changes were in a
specific version, look at the closed issues for the corresponding milestone.