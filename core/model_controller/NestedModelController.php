<?php

class NestedModelController extends ModelController
{
    public $_showInMenu = false;
    protected $parentItemId;
    private $methodName;


    /**
     *
     * @var ModelController
     */
    protected $parentController;
    
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
}

