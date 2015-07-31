<?php

class FileCacheTest extends PHPUnit_Framework_TestCase
{

    public function __construct()
    {
        \WP_Mock::setUp();
    }

    /**
     * @return Zerobase\Cache\FileCache
     */
    protected function getCacheInstance()
    {
        return Zerobase\Cache\FileCache::getInstance();
    }

    protected function mockGetHashes()
    {
        \WP_Mock::wpFunction( 'get_option', array(
            'args' => array(
                'zb_file_cache_file_cache_test_hashes',
                array()
            ),
            'times' => 1,
            'return' => array()
        ) );

    }

    public function testCreateCache()
    {
        $this->mockGetHashes();
        $cache = $this->getCacheInstance();
        $bag = $cache->createCache( 'file_cache_test' );
        $this->assertTrue( $cache->cacheExists( 'file_cache_test' ) );
    }

    public function testStoreInCache()
    {
        $this->mockGetHashes();
        $cache = $this->getCacheInstance();
        $bag = $cache->createCache( 'file_cache_test' );
    }

    public function __destruct()
    {
        \WP_Mock::tearDown();
    }
}