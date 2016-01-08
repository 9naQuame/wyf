<?php

class UniElement extends Container
{
    const MODE_CONTAINER = "container";
    const MODE_FIELD = "field";
    
    /**
     * An instance of a fapi container which is used as a template form.
     * @var Container
     */
    protected $template;

    /**
     * The label for this UniElement instance.
     * @var unknown_type
     */
    public $label;
    protected $templateName;
    protected $data = array();
    protected $referenceField;
    protected $relatedField;
    public $hasRelatedData = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function validate()
    {
        $retval = true;
        foreach($this->data as $data)
        {
            foreach($data as $key => $dat)
            {
                $data[$this->templateName.".".$key."[]"] = $dat;
            }
            $this->clearErrors();
            $this->template->setData($data);
            $retval = $this->template->validate();
        }
        return $retval;
    }

    private function _retrieveData()
    {
        if($this->isFormSent())
        {
            $this->data = array();
            $fields = $this->template->getFields();
            foreach($fields as $field)
            {
                $name = str_replace($this->templateName."_","",$field->getName());
                $field->setValue($_POST[$field->getName()]);
                $this->data[$name] = $field->getValue();
            }
        }
        
        $this->template->setData($this->data);

        return $this->data;
    }

    public function getData($storable=false)
    {
        $this->_retrieveData();
        return array($this->templateName => $this->data);
    }

    public function setData($data)
    {
        //$this->_retrieveData();
        $this->data = $data[$this->templateName];
    }

    public function setTemplate($template)
    {
        $this->template = $template;
        
        $elements = $template->getFields();
        foreach($elements as $element)
        {
            if($element->getType() === "Field")
            {
                $element->setName($template->getName()."_".$element->getName());
            }
        }
        
        $this->templateName = $template->getName();
        
        return $this;
    }

    public function render()
    {
        empty($this->data) ? $this->_retrieveData() : null;
       
        if($this->template != null)
        {
            $this->template->clearErrors();
            $template = $this->template->render();
        }
        
        $ret = "<div>
                    $template
                </div>";
        return $ret;
    }

    public function setShowField($showField)
    {
        parent::setShowField($showField);
        $this->template->setShowField($showField);
    }
}

