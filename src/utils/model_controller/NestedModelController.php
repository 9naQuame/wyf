<?php

class NestedModelController extends ModelController
{
    public $_showInMenu = false;
    protected $parentItemId;
    private $methodName;
    private $parentNameField;
    private $entity;

    /**
     *
     * @var ModelController
     */
    protected $parentController;
    
    public function getLabel()
    {
        $entity = reset($this->parentController->model[$this->parentItemId]);
        return $this->entity . ($this->entity == '' ? '' : ' of ') .  "{$this->label} ({$entity[$this->parentNameField]})";
    }
    
    public function setupListView()
    {
        $this->listView->setListConditions(
            "{$this->parentController->model->getKeyField()} = '{$this->parentItemId}'"
        );
    }
    
    public function setMethodName($methodName)
    {
        $this->methodName = $methodName;
    }
    
    public function setParent(ModelController $parent)
    {
        $this->parentController = $parent;
    }
    
    public function setParentItemId($parentItemId)
    {
        $this->parentItemId = $parentItemId;
    }
    
    public function setParentNameField($parentNameField)
    {
        $this->parentNameField = $parentNameField;
    }
    
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }
}

