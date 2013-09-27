<?php
/**
 * Engine Yard PHP Performance Tools
 *
 * @copyright Copyright 2013 Engine Yard, Inc
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @author Davey Shafik <davey@engineyard.com>
 */

/**
 * APC/Zend OpCache Cache Primer
 *
 * This is a simple wrapepr for the apc-primer/zend-primer tools
 */

if (function_exists('apc_compile_file')) {
    require_once '../apc-primer/apc-primer.php';
} elseif (function_exists('opcache_compile_file')) {
    require_once '../zend-primer/zend-primer.php';
}