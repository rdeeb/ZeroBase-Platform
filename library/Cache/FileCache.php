<?php
namespace Zerobase\Cache;

class FileCache extends AbstractCache
{
    /**
     * @inheritdoc
     */
    public function createCache( $name_space )
    {
        if ( !$this->cacheExists( $name_space ) )
        {
            $this->bags[ $name_space ] = new FileCacheBag( $name_space );
        }
        return $this->bags[ $name_space ];
    }

}
