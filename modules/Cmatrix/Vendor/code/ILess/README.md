[![Build Status](https://travis-ci.org/mishal/iless.png?branch=master)](https://travis-ci.org/mishal/iless)
[![Dependency Status](https://gemnasium.com/mishal/iless.svg)](https://gemnasium.com/mishal/iless)
[![Downloads](https://img.shields.io/packagist/dm/mishal/iless.svg)](https://packagist.org/packages/mishal/iless)
[![Latest release](https://img.shields.io/packagist/v/mishal/iless.svg)](https://github.com/mishal/iless/releases)

# ILess - PHP port of Less.js

![ILess](logo.png)

## What is Less?

Less is a CSS pre-processor, meaning that it extends the CSS language, adding features that allow variables, mixins, functions and many other techniques 
that allow you to make CSS that is more maintainable, themable and extendable. 

For more info about the language see the official website: <http://lesscss.org>

## What is This?

ILess is a **PHP port** of the official LESS processor written in Javascript. Current version of ILess is compatible with less.js `2.5.x.`

## Getting Started

To use ILess in your project you can:

  - Install it using Composer ([more info on Packagist](https://packagist.org/packages/mishal/iless))
  - [Download the latest release](https://github.com/mishal/iless/releases)
  - [Install the PHAR executable](https://github.com/mishal/iless#phar-installation)
  - Clone the repository: `git clone git://github.com/mishal/iless.git`

## Requirements

To run ILess you need `PHP >= 5.4.0`

## Feature Highlights

 * Allows to register **custom file importers** (from filesystem, database, ...)
 * Allows to setup **import directories** so search imports for
 * Allows to define **custom LESS functions** with PHP callbacks, supports context aware dynamic functions
 * Allows to **use plugins** for pre/post processing of the CSS (currently only via API, not from command line)
 * Generates **source maps** (useful for debugging the generated CSS)
 * Generates debugging information with SASS compatible information and/or simple comments
 * Allows caching of the precompiled files and the generated CSS
 * Is **unit tested** using PHPUnit
 * Compiled CSS is 100% equal to CSS compiled with less.js (exception is the javascript, which cannot be evaluated using php)
 * Provides command line utility
 * Has developer friendly exception messages with location of the error and file excerpt (output is colorized when used by command line)
 * Has well documented API, see [the docs](http://doc-iless.rhcloud.com)
 * Is PHP 7 and HHVM compatible

## Usage

### Basic usage

    <?php

    use ILess\Parser;
    use ILess\FunctionRegistry;
    use ILess\Node\ColorNode;
    use ILess\Node\DimensionNode;

    // setup autoloading
    // 1) when installed with composer
    require 'vendor/autoload.php';

    // 2) when installed manually
    // require_once 'lib/ILess/Autoloader.php';
    // ILess\Autoloader::register();

    $parser = new Parser();
    
    // parses the file
    $parser->parseFile('screen.less');

    // parse string
    $parser->parseString('body { color: @color; }');

    // assign variables via the API
    $parser->setVariables([
        'color' => 'white'
    ]);

    // Add a custom function
    $parser->addFunction('superdarken', function(FunctionRegistry $registry, ColorNode $color) {
        return $registry->call('darken', [$color, new DimensionNode(80, '%')]);
    });

    $css = $parser->getCSS();
    
    echo $css;

### Using the cache

    <?php

    use ILess\Parser;
    use ILess\Cache\FileSystemCache;

    // setup the parser to use the cache
    $parser = new Parser(array(), new FileSystemCache([
        'cache_dir' => sys_get_temp_dir() . '/iless',
        'ttl' => 86400 // the lifetime of cached files in seconds (1 day by default)
    ]);

The parser will use the cache driver to save *serialized data* from parsed files and strings and to save *generated CSS*.
The `ttl` option allows to set the lifetime of the cached files. The **change of the imported files will regenerate the cache** for those files automatically.

The cache of the CSS will be different if you assign different variables through the API (See the example above how to do it)
and for different options like `compress`, ....

The generated CSS will be also cached for the `ttl` seconds. The change in the imported files (variables, and options) will cause the CSS regeneration.

**Note**: The generated cached files can be copied in the cloud, the modification time of the imported files does not depend on the modification time of the cache files.

### Custom cache driver

If you would like to cache the parsed data and generated CSS somewhere else (like `memcached`, `database`) simple create your own driver by
implementing `ILess\Cache\CacheInterface`. See the [lib/ILess/Cache/CacheInterface.php](./lib/ILess/Cache/CacheInterface.php).

For more examples check the [examples](./examples) folder in the source files.

## Command line usage

To compile the LESS files (or input from `stdin`) you can use the ILess\CLI script (located in `bin` directory) or PHAR executable.

## PHAR installation

Download the PHAR archive

    wget http://mishal.github.io/iless/iless-latest.phar
    chmod +x iless-latest.phar
    mv iless-latest.phar /usr/local/bin/iless
    iless --version

## Usage from NetBeans IDE

To compile the LESS files from your NetBeans IDE (*version 7.4 is required*) you need to configure the path to the `iless` executable.
 [How to setup the compilation](http://wiki.netbeans.org/NetBeans_74_NewAndNoteworthy#Compilation_on_Save).

You have to configure the less path to point to `bin/iless` or the PHAR executable.

## Usage from PhpStorm IDE

To compile the LESS files from your PhpStorm IDE you need to configure the `File watcher` for `.less` files. [See the manual](http://www.jetbrains.com/phpstorm/webhelp/transpiling-sass-less-and-scss-to-css.html#d151302e621) how to do it.
You have to configure the `program` option to point to `bin/iless` or the PHAR executable.

**Note**: See additional command line options for the parser below.

## Plugins

  * [Custom imports](https://github.com/mishal/iless-plugin-custom-import) ??? allows to create custom schema like import directives like `foo://file.less`  
  * [Autoprefix](https://github.com/mishal/iless-plugin-autoprefix) ??? autoprefix generated CSS using postcss autoprefixer plugin  

## Examples

Parse the `my.less` and save it to `my.css` with compression enabled.

    $ iless my.less my.css --compress

Parse input from `stdin` and save it to a file `my.css`.

    $ iless - my.css

## Usage and available options

     _____        _______ _______ _______
       |   |      |______ |______ |______
     __|__ |_____ |______ ______| ______|

    usage: iless [option option=parameter ...] source [destination]

    If source is set to `-` (dash or hyphen-minus), input is read from stdin.

    options:
       -h, --help               Print help (this message) and exit.
       -v, --version            Print version number and exit.
       -s, --silent             Suppress output of error messages.
       --setup-file             Setup file for the parser. Allows to setup custom variables, plugins...
       --no-color               Disable colorized output.
       -x, --compress           Compress output by removing the whitespace.
       -a, --append             Append the generated CSS to the target file?
       --no-ie-compat           Disable IE compatibility checks.
       --source-map             Outputs an inline sourcemap to the generated CSS (or output to filename.map).
       --source-map-url         The complete url and filename put in the less file.
       --source-map-base-path   Sets sourcemap base path, defaults to current working directory.
       -sm, --strict-math       Strict math. Requires brackets.
       -su, --strict-units      Allows mixed units, e.g. 1px+1em or 1px*1px which have units that cannot be
                                represented.
       -rp, --root-path         Sets rootpath for url rewriting in relative imports and urls. Works with or
                                without the relative-urls option.
       -ru, --relative-urls     Re-writes relative urls to the base less file.
       --url-args               Adds params into url tokens (e.g. 42, cb=42 or a=1&b=2)
       --dump-line-numbers      Outputs filename and line numbers. TYPE can be either 'comments', which will
                                output the debug info within comments, 'mediaquery' that will output the
                                information within a fake media query which is compatible with the SASS
                                format, and 'all' which will do both.

## CLI setup

You can setup the parser (plugins, custom variables...) , by using .iless file in the root directory of your project.
The parser instance is available as `$parser` variable.

Example of the `.iless` setup file:

    <?php

    use ILess\FunctionRegistry;
    use ILess\Node\ColorNode;
    use ILess\Node\DimensionNode;

    /* @var $parser ILess\Parser */

    $parser->addVariables([
        'color' => 'white'
    ]);

    $parser->addFunction('superdarken', function (FunctionRegistry $registry, ColorNode $color) {
        return $registry->call('darken', [$color, new DimensionNode(80, '%')]);
    });

If you want to use setup file from another location, simply pass the path as `--setup-file` option from the command line.

    $ iless foo.less --setup-file=/home/user/project/setup.php

## Issues

Before opening any issue, please search for [existing issues](https://github.com/mishal/iless/issues). After that if you find a bug or would like to make feature request, please open a new issue. Please *always* create a unit test.

 * [List of issues](https://github.com/mishal/iless/issues)

## Contributing

Contributions are welcome! If you want to participate on development, have a bug report, ... please see the [contributing guide](./CONTRIBUTING.md).

## Disclaimer & About

iless = I less: **He must increase, but I must decrease.** [[John 3:30](https://www.bible.com/bible/37/jhn.3.30.ceb)]

I was born in non believers family and was raised as a atheist. When I was 30 years old my girlfriend came home and said that she is now a Christian and she believes in God! **What a shock for me**! I thought that she must be totally crazy!

I decided to do a heavy investigation on that topic a bring some proofs to her, **that there is no God**. I said to myself that I will search without any prejudices no matter what the result will be. In about 1 year I checked the topics which I thought **would bring any evidence of God's existence** - the science.

I was very surprised to see that there is a plenty of evidence of a design in things around me, even in me. The **DNA is a programming language**, but a bit complicated than only 1 and 0 that my computer uses. I know that no computer app can just appear or develop by chance even if I will have a rest of 1 billion years.

I came to a **revolutionary conclusion** for me. **God exists!** I was 30 year blind!

My girlfriend told me that God loves me and **wants a relationship with me**. That Jesus died for me and is waiting for my answer to his invitation. I said yes!

Now I'm God's adopted son saved for the eternity. God takes care of me. He freed me from drug addition and other ugly thinks.

I know that [God loves to you](http://bible.com/37/1jn.4.9-10.ceb) (is written in his Word) and [wants you to save you too](http://bible.com/37/act.2.21.ceb). Invite Jesus to your life!

**Note**: This is **not a religion!** But a relationship with living God.

### Upgrade your life

  * **Agree and accept the license** which God offers. There is no *accept* button, but you have to do it by faith. Accept that Jesus died for you and took the punishment instead of you.
  * **Repent from your sins**. Sin is everything that violates the law given by God (not loving God, stealing, cheating, lying... [See the full list](https://www.bible.com/bible/37/deu.30.15-16.ceb).
  * **Ask Jesus** for [forgiveness] and to become your personal lord and savior (http://bible.com/37/mrk.2.5-12.ceb).

If you did the steps above with your whole heart you are now a **[new creation](http://bible.com/37/2co.5.17.ceb)**. You belong to God's family and you have now an **eternal life**. You have been redeemed from the eternal punishment - from the outer darkness where is weeping and gnashing of teeth.

**Read the Bible**, and ask God to speak with you and to lead you to a (*true*) Church. There is a lot of so called Churches around, but they to do not teach nor live the Bible.

## Credits

The work is based on the code by [Matt Agar](https://github.com/agar), [Martin Janto??ovi??](https://github.com/Mordred) and [Josh Schmidt](https://github.com/oyejorge). Source maps code based on [phpsourcemaps](https://github.com/bspot/phpsourcemaps) by [bspot](https://github.com/bspot).

[All contributors](https://github.com/mishal/iless/wiki/Contributors) are listed on separate [wiki page](https://github.com/mishal/iless/wiki/Contributors).
