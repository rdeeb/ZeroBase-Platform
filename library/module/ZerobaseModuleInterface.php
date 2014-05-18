<?php

interface ZerobaseModuleInterface
{
    public function getName();

    public function getDescription();

    public function getType();

    public function getLoadPath();

    public function getOptionForm();

    public function saveOptions();

    public function getSlug();
} 