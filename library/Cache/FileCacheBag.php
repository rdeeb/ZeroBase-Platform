<?php
namespace Zerobase\Cache;

class FileCacheBag implements CacheBagInterface
{
    protected $hashes = array();

    public function __construct( $name_space )
    {
        $this->name_space = $name_space;
        $this->hashes = get_option( "zb_file_cache_{$this->name_space}_hashes", array() );
        if ( !is_dir( $this->getCacheDir() ) )
        {
            mkdir( $this->getCacheDir(), 755 );
        }
    }

    /**
     * @inheritdoc
     */
    public function store( $key, $data )
    {
        $filename = $this->getCacheDir() . '/' . \ZerobasePlatform::slugify( $key ) . '.cache';
        file_put_contents( $filename, $data );
        $this->hashes[ $filename ] = md5_file( $filename );
        update_option( "zb_file_cache_{$this->name_space}_hashes", $this->hashes );
    }

    /**
     * @inheritdoc
     */
    public function retreive( $key )
    {
        $filename = $this->getCacheDir() . '/' . \ZerobasePlatform::slugify( $key ) . '.cache';
        if ( file_exists( $filename ) )
        {
            if ( isset( $this->hashes[ $filename ] ) && md5_file( $filename ) == $this->hashes[ $filename ] )
            {
                return require( $filename );
            }
            return false;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function destroy( $key )
    {
        $filename = $this->getCacheDir() . '/' . \ZerobasePlatform::slugify( $key ) . '.cache';
        try
        {
            if ( file_exists( $filename ) )
            {
                unlink( $filename );
                unset( $this->hashes[ $filename ] );
                update_option( "zb_file_cache_{$this->name_space}_hashes", $this->hashes );
            }
            return true;
        }
        catch ( \Exception $e )
        {
            return false;
        }
    }

    public function has( $key )
    {
        $filename = $this->getCacheDir() . '/' . \ZerobasePlatform::slugify( $key ) . '.cache';
        return file_exists( $filename );

    }

    private function getCacheDir()
    {
        return ZEROBASE_CACHE_DIR . '/' . $this->name_space;
    }
}
