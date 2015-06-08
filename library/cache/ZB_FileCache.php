<?php
include_once( 'ZB_AbstractCache.php' );

class ZB_FileCache extends ZB_AbstractCache
{
    /**
     * @inheritdoc
     */
    public function createCache( $name_space )
    {
        if ( !$this->cacheExists( $name_space ) )
        {
            $this->bags[ $name_space ] = new ZB_FileCacheBag( $name_space );
        }
        return $this->bags[ $name_space ];
    }

}
