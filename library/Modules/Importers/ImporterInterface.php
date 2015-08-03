<?php
namespace Zerobase\Modules\Importers;

interface ImporterInterface
{
    /**
     * @param array $config The configuration to validate
     *
     * @return bool
     */
    static function validate( array $config );

    /**
     * @param string $key The unique identifier of this object
     * @param array  $config
     *
     * @return bool
     */
    static function load( $key, array $config );
}
