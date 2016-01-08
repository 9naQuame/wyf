<?php

class ModelFieldSelect extends SelectionList
{
    public function __construct($path,$value,$conditions)
    {
        global $redirectedPackage;
        
        $info = Model::resolvePath($path);
        $model = Model::load((substr($info["model"],0,1) == "." ? $redirectedPackage: "") . $info["model"]);
        $valueField = $value;
        $field = $model->getFields(array($value));

        $this->setLabel($field[0]["label"]);
        $this->setDescription($field[0]["description"]);
        $this->setName($info["field"]);
        
        $params = array(
            "fields" => array($info["field"],$valueField),
            "sort_field" => $valueField,
        );
        
        if($conditions != '')
        {
            $params['conditions'] = $conditions;
        }           
        
        $data = $model->get(
            $params,
            Model::MODE_ARRAY
        );

        foreach($data as $datum)
        {
            if($datum[1] == "")
            {
                $this->addOption($datum[0]);
            }
            else
            {
                $this->addOption($datum[1],$datum[0]);
            }
        }         
    }
}