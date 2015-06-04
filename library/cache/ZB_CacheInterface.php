<?php

interface ZB_CacheInterface
{
    /**
     * Creates a cache bag for the specified name space
     * @param string $name_space
     * @return ZB_CacheBagInterface|bool The created cache bag | False on error
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
     * @return ZB_CacheBagInterface|bool The cache bag, or false if it doesn't exists
     */
    public function retreiveCache($name_space);

}