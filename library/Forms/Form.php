<?php
namespace Zerobase\Forms;

use Zerobase\Forms\Models\ModelInterface;
use Zerobase\Forms\Renderers\RendererInterface;
use Zerobase\Forms\Validators\ValidatorFactory;
use Zerobase\Forms\Widgets\WidgetFactory;
use Zerobase\Toolkit\Request;

/**
 * Form
 * A class that builds options forms for Wordpress
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
 *          http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
class Form
{
    protected $formName;
    protected $model;
    protected $widgets = array();
    protected $renderer;
    protected $values;
    protected $taintedValues;
    protected $validations = array();

    public function __construct( $form_name, RendererInterface $renderer, ModelInterface $model )
    {
        $this->formName = $form_name;
        $this->renderer = $renderer;
        $this->model    = $model;
        $this->loadDataFromRequest();
    }

    private function loadDataFromRequest()
    {
        $request = Request::getInstance();
        if ( $request->has( $this->formName ) )
        {
            $this->taintedValues = $request->get( $this->formName );
        }
        else
        {
            $this->values = array();
        }
    }

    public function addWidget( $name, $type, array $options = array(), $value = NULL )
    {
        $options = $this->sanitizeWidgetOptions( $name, $options );
        $wm      = WidgetFactory::getInstance();
        if ( isset( $options[ 'validations' ] ) )
        {
            $this->setWidgetValidators( $name, $options[ 'validations' ] );
            unset( $options[ 'validations' ] );
        }
        $this->widgets[ $name ] = $wm->createWidget( $type, $options );
        $this->setWidgetValue( $name, $value );
        $this->renderer->addWidget( $name, $this->widgets[ $name ] );
        $this->model->setModel( $this->getProposedModel() );
    }

    protected function getProposedModel()
    {
        $proposedModel = array();
        foreach ( $this->widgets as $name => $widget )
        {
            $proposedModel[ $name ] = array(
                'default' => isset( $this->values[ $name ] ) ? $this->values[ $name ] : NULL
            );
        }

        return $proposedModel;
    }

    /**
     * @param string $name
     * @param array  $options
     *
     * @return array
     */
    private function sanitizeWidgetOptions( $name, array $options )
    {
        if ( !isset( $options[ 'attr' ] ) )
        {
            $options[ 'attr' ] = array();
        }
        $options[ 'attr' ][ 'id' ]   = isset( $options[ 'id' ] ) ? $options[ 'id' ] : "{$this->formName}_$name";
        $options[ 'attr' ][ 'name' ] = isset( $options[ 'name' ] ) ? $options[ 'name' ] : "{$this->formName}[$name]";
        if ( !isset( $options[ 'label' ] ) )
        {
            $options[ 'label' ] = ucfirst( str_replace( '_', ' ', $name ) );
        }

        return $options;
    }

    private function setWidgetValue( $name, $default )
    {
        /** @var WidgetInterface $widget */
        $widget = $this->widgets[ $name ];
        if ( isset( $this->taintedValues[ $name ] ) )
        {
            $widget->setValue( $this->taintedValues[ $name ] );
        }
        else
        {
            $request = Request::getInstance();
            if ( $default != NULL && !( $request->has( $this->formName ) && $widget->getType() == 'checkbox' ) )
            {
                $widget->setValue( $default );
                $this->values[ $name ] = $default;
            }
            else
            {
                if ( $request->has( $this->formName ) && $widget->getType() == 'checkbox' )
                {
                    $this->values[ $name ] = FALSE;
                }
            }
        }
    }

    private function setWidgetValidators( $name, $validators )
    {
        if ( !is_array( $validators ) )
        {
            throw new \Exception( "The validators must be defined as an array of validator options" );
        }
        foreach ( $validators as $key => $options )
        {
            $validators[ $key ] = $this->sanitizeValidatorOptions( $options );
        }
        $this->validations[ $name ] = $validators;
    }

    private function sanitizeValidatorOptions( $options )
    {
        return array_merge( array(
            'options'  => array(),
            'messages' => array()
        ), $options );
    }

    public function getValues()
    {
        return $this->values;
    }

    public function getTaintedValues()
    {
        return $this->taintedValues;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isValid()
    {
        $isValid          = TRUE;
        $validatorFactory = ValidatorFactory::getInstance();
        foreach ( $this->validations as $name => $validators )
        {
            foreach ( $validators as $key => $options )
            {
                $validator = $validatorFactory->createValidator( $key, $options );
                $validator->setValue( $this->taintedValues[ $name ] );
                if ( !$validator->assert() )
                {
                    $currentClasses = $this->widgets[ $name ]->getAttr( 'class' );
                    $currentClasses .= empty( $currentClasses ) ? 'error' : ' error';
                    $this->widgets[ $name ]->setAttr( 'class', $currentClasses );
                    $isValid = FALSE;
                }
            }
        }

        return $isValid;
    }

    public function save()
    {
        if ( $this->isValid() && !empty( $this->taintedValues ) )
        {
            foreach ( $this->taintedValues as $name => $value )
            {
                $this->model->setValue( $name, $value );
            }
            $this->model->save();
        }
    }

    /**
     * @return ZB_RendererInterface
     */
    public function getRenderer()
    {
        $this->renderer->addWidgets( $this->widgets );

        return $this->renderer;
    }
}
