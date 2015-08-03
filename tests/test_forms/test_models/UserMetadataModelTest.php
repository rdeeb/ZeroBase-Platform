<?php

class UserMetadataModelTest extends PHPUnit_Framework_TestCase
{
    protected $model;

    private function getModel()
    {
        if ( !$this->model )
        {
            $this->model = \Zerobase\Forms\Models\ModelFactory::getInstance()->createModel( 'user_metadata' );
            $this->model->setModel( array(
                'test_key' => array(
                    'default' => NULL
                )
            ) );
        }
        return $this->model;
    }

    public function testStoreValue()
    {
        \WP_Mock::wpFunction( 'get_the_author_meta', array(
            'args' => array(
                'test_key',
                1
            ),
            'times' => 1,
            'return' => NULL
        ) );
        \WP_Mock::wpFunction( 'update_user_meta', array(
            'args' => array(
                1,
                'test_key',
                'test_value'
            ),
            'times' => 1,
            'return' => NULL
        ) );
        \WP_Mock::wpFunction( 'get_current_user_id', array(
            'args' => NULL,
            'times' => 2,
            'return' => 1
        ) );
        $model = $this->getModel();
        $model->setValue( 'test_key', 'test_value' );
        $this->assertTrue( $model->save() );
    }

    public function testRetreiveValue()
    {
        \WP_Mock::wpFunction( 'get_the_author_meta', array(
            'args' => array(
                'test_key',
                1
            ),
            'times' => 1,
            'return' => 'test_value'
        ) );
        \WP_Mock::wpFunction( 'get_current_user_id', array(
            'args' => NULL,
            'times' => 1,
            'return' => 1
        ) );
        $model = $this->getModel();
        $this->assertEquals( 'test_value', $model->getValue( 'test_key' ) );
    }
}
