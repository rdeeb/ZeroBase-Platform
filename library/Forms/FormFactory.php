<?php
namespace Zerobase\Forms;

use Zerobase\Forms\Models\ModelFactory;

class FormFactory
{
    /**
     * @param string $formName
     * @param string $renderer
     *
     * @return ZB_Form
     */
    public static function createForm( $formName, $renderer = 'default', $model = 'memory' )
    {
        $rendererClass = self::getRenderer( $renderer );
        $modelClass    = self::getModel( $model );

        return new Form( $formName, $rendererClass, $modelClass );
    }

    private static function getModel( $model )
    {
        $modelFactory = ModelFactory::getInstance();

        return $modelFactory->createModel( $model );
    }

    private static function getRenderer( $renderer )
    {
        if ( !self::checkIfRenderingClassExists( $renderer ) )
        {
            self::loadRendererClass( $renderer );
        }
        $className = self::getClassName( $renderer );

        return new $className();
    }

    private static function checkIfRenderingClassExists( $renderer )
    {
        return class_exists( self::getClassName( $renderer ) );
    }

    private static function loadRendererClass( $renderer )
    {
        $className = self::getClassName( $renderer );
        try
        {
            $obj = new $className();
            unset( $obj );
        }
        catch ( \Exception $e )
        {
            throw new \Exception( "Unknown Renderer $renderer" );
        }
    }

    private static function getClassName( $string )
    {
        return 'Zerobase\Forms\Renderers\\' . ucwords( $string ) . 'Renderer';
    }
}
