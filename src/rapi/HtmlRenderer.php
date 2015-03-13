<?php

class HtmlRenderer extends ReportRenderer
{   
    private $markup;
    
    /**
     * 
     * @param LogoContent $content
     */
    public function renderlogo(LogoContent $content)
    {
        $data = array(
            'image' => $content->image,
            'title' => $content->title,
            'address' => $content->address
        );
        $this->markup .= TemplateEngine::render(__DIR__ . '/html_templates/logo.tpl', $data);
    }
    
    /**
     * 
     * @param TextContent $content
     */
    public function renderText(TextContent $content)
    {
        $style = $content->getStyle();
        $css = "padding:0px;margin:0px;";
        if(isset($style["font"])) $css .= "font-family:{$style["font"]};";
        if(isset($style["size"])) $css .= "font-size:{$style["size"]}pt;";
        if(isset($style["top_margin"])) $css .= "margin-top:{$style["top_margin"]}px;";
        if(isset($style["bottom_margin"])) $css .= "margin-bottom:{$style["bottom_margin"]}px;";

        $css .= $style["bold"]?"font-weight:bold;":"";
        $css .= $style["underline"]?"text-decoration:underline;":"";
        $css .= $style["align"] == 'R' ? "text-align:right":"";        
        
        $this->markup .= "<div style='$css'>{$content->text}</div>";
    }

    public function output() 
    {
        return $this->markup;
    }

    public function renderTable(TableContent $content) 
    {
        $style = $content->getStyle();     
        
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
