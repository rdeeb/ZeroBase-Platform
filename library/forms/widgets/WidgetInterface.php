<?php
namespace Zerobase\Forms\Widgets;

interface WidgetInterface
{
    public function getType();

    public function renderWidget();

    public function renderLabel();

    public function getValue();

    public function setValue( $v );

}
