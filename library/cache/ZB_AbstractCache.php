<?php
include_once( 'ZB_CacheInterface.php' );

abstract class ZB_AbstractCache extends ZB_Singleton implements ZB_CacheInterface
{
    protected $bags = array();

    /**
     * @inheritdoc
     */
    public function cacheExists($name_space)
    {
        return isset($this->bags[$name_space]);
    }

    /**
     * @inheritdoc
     */
    public function destroyCache($name_space)
    {
        unset($this->bags[$name_space]);
    }

    /**
     * @inheritdoc
     */
    public function retreiveCache($name_space)
    {
        if ($this->cacheExists($name_space))
        {
            return $this->bags[$name_space];
        }


        return false;
    }
}
