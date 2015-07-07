<?php
namespace Zerobase\Toolkit;

abstract class ArrayAccess implements \ArrayAccess
{
    protected $data = array();
    protected $indexes = array();
    protected $position = 0;

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->data[$offset] : null;
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }
}
