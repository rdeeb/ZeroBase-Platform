<?php

interface ZB_RendererInterface
{
    public function render();
    public function renderRow( $widgetName );
    public function renderLabel( $widgetName );
    public function renderWidget( $widgetName );
}