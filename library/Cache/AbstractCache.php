<?php
namespace Zerobase\Cache;

use Zerobase\Toolkit\Singleton;

abstract class AbstractCache extends Singleton implements CacheInterface
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
