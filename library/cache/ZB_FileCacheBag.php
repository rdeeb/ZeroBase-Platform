<?php
include_once( 'ZB_CacheBagInterface.php' );

class ZB_FileCacheBag implements ZB_CacheBagInterface
{
    protected $hashes = array();
    protected $name_space;

    public function __cosntruct( $name_space )
    {
        $this->name_space = $name_space;
        $this->hashes = get_option( "zb_file_cache_{$this->name_space}_hashes", array() );
        if ( !is_dir( $this->getCacheDir() ) )
        {
            mkdir( $this->getCacheDir(), 755 );
        }
    }

    public function __destruct()
    {
        update_option( "zb_file_cache_{$this->name_space}_hashes", $this->hashes );
    }

    /**
     * @inheritdoc
     */
    public function store( $key, $data )
    {
        $filename = $this->getCacheDir() . '/' . ZerobasePlatform::slugify( $key ) . '.cache';
        file_put_contents( $filename, $data );
        $this->hashes[$filename] = md5_file($filename);
    }

    /**
     * @inheritdoc
     */
    public function retreive( $key )
    {
        $filename = $this->getCacheDir() . '/' . ZerobasePlatform::slugify( $key ) . '.cache';
        if ( file_exists( $filename ) )
        {
            if ( isset( $this->hashes[$filename] ) && md5_file( $filename ) == $this->hashes[$filename] )
            {
                return file_get_contents( $filename );
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function destroy( $key )
    {
        $filename = $this->getCacheDir() . '/' . ZerobasePlatform::slugify( $key ) . '.cache';
        try
        {
            if ( file_exists( $filename ) )
            {
                unlink( $filename );
                unset( $this->hashes[ $filename ] );
            }
            return true;
        }
        catch ( Exception $e )
        {
            return false;
        }
    }

    private function getCacheDir()
    {
        return ZEROBASE_CACHE_DIR . '/' . ZerobasePlatform::slugify( $this->name_space );
    }
}