<?php
/**
 * Engine Yard PHP Performance Tools
 *
 * @copyright Copyright 2013 Engine Yard, Inc
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @author Davey Shafik <davey@engineyard.com>
 */

namespace EngineYard;

/**
 * A filter iterator that lets us specify any number
 * of callbacks to accept/reject the items
 */
class CallbackFilterIterator extends \FilterIterator {
    /**
     * @var array An array of callbacks
     */
    protected $callbacks;

    /**
     * @param Iterator $iterator
     * @param array $callbacks An array of callbacks with boolean results
     */
    public function __construct(\Iterator $iterator, $callbacks = [])
    {
        $this->callbacks = $callbacks;
        parent::__construct($iterator);
    }

    /**
     * Accept/Reject the item
     *
     * @return bool
     */
    public function accept()
    {
        // Get the current value from the actual iterator
        $current = $this->getInnerIterator()->current();

        foreach ($this->callbacks as $callback) {
            if (is_callable($callback) && !$callback($current)) {
                // Reject the item
                return false;
            }
        }
        // Accept the item
        return true;
    }
}
