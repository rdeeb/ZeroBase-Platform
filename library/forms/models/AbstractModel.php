<?php
namespace Zerobase\Forms\Models;

abstract class AbstractModel implements ModelInterface
{
    protected $values = array();
    protected $model = array();

    public function setModel(array $model)
    {
        $this->model = $this->sanitizeModel($model);
        $this->preLoadModelData();
    }

    private function sanitizeModel($model)
    {
        foreach($model as $key => $value)
        {
            $model[$key] = array_merge(array(
                'default' => null
            ), $value);
        }
        return $model;
    }

    private function preLoadModelData()
    {
        foreach($this->model as $name => $options)
        {
            $this->retreiveData($name, $options['default']);
        }
    }

    public function setValue($name, $value)
    {
        if (array_key_exists($name, $this->model))
        {
            $this->values[$name] = $value;
        }
        else
        {
            throw new \Exception("The key \"$name\" doesn't exists in the model");
        }
    }

    public function getValue($name)
    {
        if (array_key_exists($name, $this->model))
        {
            return isset($this->values[$name]) ? $this->values[$name] : null;
        }
        else
        {
            throw new \Exception("The key \"$name\" doesn't exists in the model");
        }
    }

    public function getValues()
    {
        return $this->values;
    }

    public function save()
    {
        foreach($this->model as $name => $options)
        {
            $this->storeData($name, $this->values[$name]);
        }
    }
}
