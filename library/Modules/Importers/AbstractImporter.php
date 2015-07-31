<?php
namespace Zerobase\Modules\Importers;

abstract class AbstractImporter implements ImporterInterface
{
    protected $allowed_keys = array();

    /**
     * @inheritdoc
     */
    static function validate( array $config )
    {
        if ( !empty( self::$allowed_keys ) )
        {
            $config_keys = self::getConfigKeys( $config );
            foreach($config_keys as $key)
            {
                if ( !in_array( $key, self::allowed_keys ) )
                {
                    throw new \Exception("Unsupported key [$key] in configuration file");
                }
            }
        }
        else
        {
            return true;
        }
    }

    /**
     * Returns the list of keys in the configuration array
     * @param array $config
     * @return array
     */
    private static function getConfigKeys( array $config )
    {
        $keys = array();
        foreach($config as $key => $value)
        {
            if ( !is_int( $key ) )
            {
                $keys[] = $key;
                if ( is_array( $value ) && $key != 'fields' && $key != 'choices' )
                {
                    $child_keys = self::getConfigKeys( $value );
                    $keys = array_unique( array_merge( $keys, $child_keys ) );
                }
            }
        }
        return $keys;
    }
}
