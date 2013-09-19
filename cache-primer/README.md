# Cache Primer

A tool for priming your cache to help avoid a
[cache stampede](http://en.wikipedia.org/wiki/Cache_stampede).

This can be used to prime both opcode caches (e.g. Zend OpCache, APC), as well
as HTTP caches (e.g. Varnish).

If you have [pecl_http](http://pecl.php.net/pecl_http) installed you may specify
how many parallel requests should be sent at once in `config.php`. Otherwise, all
requests will be sent serially.

## Usage

To use, first create a `config.php` using `config.php-dist` as a template.

Then simply call the script from the command line:

```bash
$ /path/to/vendor/bin/cache-primer
```