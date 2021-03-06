<?php
namespace Zerobase\Cache;

interface CacheInterface
{
    /**
     * Creates a cache bag for the specified name space
     * @param string $name_space
     * @return CacheBagInterface
     */
    public function createCache($name_space);

    /**
     * @param string $name_space
     * @return bool
     */
    public function cacheExists($name_space);

    /**
     * @param $name_space
     * @return bool
     */
    public function destroyCache($name_space);

    /**
     * @param $name_space
     * @return CacheBagInterface
     */
    public function retreiveCache($name_space);

}
