<?php
namespace Zerobase\Forms\Models;

use Zerobase\Toolkit\Singleton;

class ModelFactory extends Singleton
{

    private $models = array();

    protected function __construct()
    {
        $this->loadDefaultModels();
    }

    private function loadDefaultModels()
    {
        $this->addWidgetModel( 'memory', 'Zerobase\Forms\Models\MemoryModel' );
        $this->addWidgetModel( 'metadata', 'Zerobase\Forms\Models\MetadataModel' );
        $this->addWidgetModel( 'option', 'Zerobase\Forms\Models\OptionsModel' );
    }

    /**
     * @param string $name
     * @param string $className
     * @throws \Exception
     */
    public function addWidgetModel( $name, $className )
    {
        if ( !$this->checkClassImplements( $className ) )
        {
            throw new \Exception( "The class \"$className\" must implement the Zerobase\\Forms\\Models\\ModelInterface" );
        }
        else
        {
            $this->models[ $name ] = $className;
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function modelExists( $name )
    {
        return array_key_exists( $name, $this->models );
    }

    /**
     * @param string $name
     * @throws \Exception
     * @return ModelInterface
     */
    public function createModel( $name )
    {
        if ( $this->modelExists( $name ) )
        {
            $className = $this->models[ $name ];

            return new $className();
        }
        else
        {
            throw new \Exception( "The Model \"$name\" doesn't exists" );
        }
    }

    /**
     * @param string $className
     * @return bool
     * @throws \Exception
     */
    private function checkClassImplements( $className )
    {
        if ( !class_exists( $className ) )
        {
            throw new \Exception( "The class \"$className\" doesn't exists" );
        }
        $implements = class_implements( $className );
        if ( in_array( 'Zerobase\Forms\Models\ModelInterface', $implements ) )
        {
            return true;
        }

        return false;
    }
}
