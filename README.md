# Engine Yard PHP Performance Tools

A small suite of simple tools to help diagnose and
fix performance issues, or other performance-related
tasks.

## Tool list

- APC Primer — A tool for priming your APC cache to help avoid a
    [cache stampede](http://en.wikipedia.org/wiki/Cache_stampede)

## Installation

These tools can be installed by composer. Add the following to your
`composer.json`:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "http://github.com/engineyard/ey-php-performance-tools"
    }
],
"require": {
    "php": ">=5.3.3",
    "engineyard/php-performance-tools": "dev-master"
}
```

Then run the install:

```bash
$ composer update engineyard/php-performance-tools
```

## Usage

Each individual tool has it's own documentation within it's sub-directory
