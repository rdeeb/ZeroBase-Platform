<?php

require_once 'autoloader.php';

define( 'ZEROBASE_CACHE_DIR',  __DIR__ . '/../cache/test' );

if ( !is_dir( ZEROBASE_CACHE_DIR ) ) {
    mkdir( ZEROBASE_CACHE_DIR, 755 );
}