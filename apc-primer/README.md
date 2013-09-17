# APC Primer

A tool for priming your APC cache to help avoid a
[cache stampede](http://en.wikipedia.org/wiki/Cache_stampede).

## Usage

> **Note:** You *must* run this on the SAPI where you wish to cache the files.
> e.g. If you run this on the command line (the `cli` SAPI) it will not be cached for `php-fpm`.

To use, first create a `config.php` using `config.php-dist` as a template.

Next, you need to run `apc-primer.php` via the SAPI in which you wish to perform the cache.

If you installed the project with composer and are using php-fpm or Apache we recommend:

- Include the script as part of your admin interface (behind your own authentication) and disable authentication in the config
- Simply `include` it in a web-accessible file and *make sure to enable authentication* in the config

You may define a `$config` variable before including `apc-primer.php`, ensuring it's available in whatever
scope `apc-primer.php` executes in.