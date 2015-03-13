<?php
class Report
{
    private $contents = array();
    private $generator;

    public function __construct($format, $parameters = array())
    {
        $generatorClass = ucfirst($format) . 'Renderer';
        $this->generator = new $generatorClass();
        $this->generator->setParameters($parameters);
    }
        
    public function add()
    {
        $this->contents = array_merge($this->contents,func_get_args());
        return $this;
    }
    
    public function output()
    {
        foreach($this->contents as $content)
        {
            $contentType = $content->getType();
            $method = "render{$contentType}";
            //if(method_exists($this->generator, $method))
            //{
                $this->generator->$method($content);
            //}
        }
        
        echo $this->generator->output();
        die();
    }

    /*public function addPage($repeatLogos = false, $forced = false)
    {
        if(!$this->pageInitialized || $forced)
        {
            $this->contents[] = "NEW_PAGE";
            if($repeatLogos)
            {
                if($this->logo != null) $this->add($this->logo);
                if($this->label != null) $this->add($this->label);
                if($this->filterSummary != null) $this->add($this->filterSummary);
            }
        }
        else
        {
            $this->pageInitialized = false;
        }
    }

    public function resetPageNumbers()
    {
        $this->contents[] = "RESET_PAGE_NUMBERS";
    }*/
}

