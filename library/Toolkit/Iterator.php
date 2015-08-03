<?php
namespace Zerobase\Toolkit;

abstract class Iterator extends ArrayAccess implements \Iterator
{
    protected $data = array();
    protected $indexes = array();
    protected $position = 0;

    public function isEmpty()
    {
        return empty( $this->data );
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->data[ $this->indexes[ $this->position ] ];
    }

    public function key()
    {
        return $this->indexes[ $this->position ];
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset( $this->data[ $this->indexes[ $this->position ] ] );
    }
}
