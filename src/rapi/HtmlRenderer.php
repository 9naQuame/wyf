<?php

class HtmlRenderer extends ReportRenderer
{   
    private $markup;
    
    public function renderlogo(LogoContent $content)
    {
        $data = array(
            'image' => $content->image,
            'title' => $content->title,
            'address' => $content->address
        );
        $this->markup .= TemplateEngine::render(__DIR__ . '/html_templates/logo.tpl', $data);
    }
    
    public function renderText(TextContent $content)
    {
        $this->markup .= "<div class='rapi-text rapi-text-{$content->getStyle()}'>{$content->getText()}</div>";
    }

    public function output() 
    {
        return $this->markup;
    }

    public function renderTable(TableContent $content) 
    {
        $templates = array(
            'as_totals_box' => $content->getAsTotalsBox(),
            'num_columns' => $content->getNumColumns(),
            'data' => $content->getData(),
            'headers' => $content->getHeaders(),
            'auto_totals' => $content->getAutoTotals(),
            'totals' => $content->getTotals(),
            'types' => $content->getDataTypes()
        );
                
        $this->markup .= TemplateEngine::render(__DIR__ . '/html_templates/table.tpl', $templates);
    }
}
