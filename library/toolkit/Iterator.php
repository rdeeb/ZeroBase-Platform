<?php
namespace Zerobase\Toolkit;

abstract class Iterator
{
    protected $data = array();
    protected $indexes = array();
    protected $position = 0;

    public function isEmpty()
    {
        return empty($this->data);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->data[$this->indexes[$this->position]];
    }

    public function key()
    {
        return $this->indexes[$this->position];
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->data[$this->indexes[$this->position]]);
    }

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