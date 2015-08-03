<?php
namespace Zerobase\Settings;

use Zerobase\Toolkit\Singleton;

class Settings extends Singleton implements \Iterator
{
    protected $bagStorage = array();
    protected $bagIndexes = array();
    protected $position = 0;

    /**
     * Returns a bag
     *
     * @param $name string The name of the bag to retrieve
     *
     * @return SettingsBag
     */
    public function getBag( $name )
    {
        if ( $this->hasBag( $name ) )
        {
            return $this->bagStorage[ $name ];
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Creates a new settings bag
     *
     * @param $name string The name of the settings bag
     */
    public function createBag( $name )
    {
        if ( !array_key_exists( $name, $this->bagStorage ) )
        {
            $this->bagIndexes[] = $name;
        }
        $this->bagStorage[ $name ] = new SettingsBag();
    }

    /**
     * Checks if a given bag exists
     *
     * @param $name string The name of the bag to check
     *
     * @return bool
     */
    public function hasBag( $name )
    {
        return isset( $this->bagStorage[ $name ] );
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->bagStorage[ $this->bagIndexes[ $this->position ] ];
    }

    public function key()
    {
        return $this->bagIndexes[ $this->position ];
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset( $this->bagIndexes[ $this->position ] );
    }
}
