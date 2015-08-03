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

    public function testCreateCache()
    {
        \WP_Mock::wpFunction( 'get_option', array(
            'args' => array(
                'zb_file_cache_file_cache_test_hashes',
                array()
            ),
            'times' => 1,
            'return' => array()
        ) );
        $cache = $this->getCacheInstance();
        $bag = $cache->createCache( 'file_cache_test' );
        $this->assertTrue( $cache->cacheExists( 'file_cache_test' ) );
    }

    public function testStoreInCache()
    {
        \WP_Mock::wpFunction( 'update_option', array(
            'args' => array(
                'zb_file_cache_file_cache_test_hashes',
                array(
                    ZEROBASE_CACHE_DIR . '/file_cache_test/test-cache.cache' => '8cdd9bc3b28656cde3c70e0c1d4f9dbc'
                )
            ),
            'times' => 1,
            'return' => array()
        ) );
        $cache = $this->getCacheInstance();
        $bag = $cache->retreiveCache( 'file_cache_test' );
        $this->assertTrue( $bag->store( 'test_cache', '<?php return "Hello World" ?>' ) );
    }

    public function testRetreiveCache()
    {
        $cache = $this->getCacheInstance();
        $bag = $cache->retreiveCache( 'file_cache_test' );
        $value = $bag->retreive( 'test_cache' );
        $this->assertEquals( 'Hello World', $value );
    }

    public function testDestroyCache()
    {
        $cache = $this->getCacheInstance();
        $this->assertTrue( $cache->destroyCache( 'file_cache_test' ) );
    }

    public function __destruct()
    {
        \WP_Mock::tearDown();
    }
}