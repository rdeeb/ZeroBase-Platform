<?php
namespace Zerobase\Cache;

interface CacheBagInterface
{
    /**
     * @param string $key Key of the data to store
     * @param mixed $data Tha data to store
     * @return bool True on success false on error
     */
    public function store($key, $data);

    /**
     * @param string $key Key of the data to store
     * @return mixed The data Stored
     */
    public function retreive($key);

    /**
     * @param string $key Key of the data to store
     * @return bool True on success false on error
     */
    public function destroy($key);

    /**
     * @param string $key Key of the data to search
     * @return bool True if exists, false if not
     */
    public function has($key);
}
