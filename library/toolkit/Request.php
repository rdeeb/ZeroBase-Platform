<?php
namespace Zerobase\Toolkit;

class Request extends Singleton
{
    protected $request;
    protected $server;

    protected function __construct()
    {
        $this->request = array_merge( $_GET, $_POST );
        $this->server = $_SERVER;
    }

    public function get( $key, $default = null )
    {
        if ( $this->has( $key ) )
        {
            return $this->request[ $key ];
        }
        return $default;
    }

    public function has( $key )
    {
        if ( isset( $this->request[ $key ] ) )
        {
            return true;
        }
        return false;
    }
}