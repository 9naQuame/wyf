<?php

class SystemMyTrailController extends Controller
{
    public function __construct()
    {
        $this->label = 'My Trail';
        $this->description = 'A trail of activities you have performed in the past';
    }
    
    public function getContents()
    {
        $table = new MultiModelTable(null);
        $table->setParams(
            array(
                'fields' => array(
                    '.audit_trail.audit_trail_id',
                    '.audit_trail.audit_date',
                    '.audit_trail.description',
                    '.audit_trail.item_type'
                ),
                'conditions' => "user_id = '{$_SESSION['user_id']}'",
                'sort_field' => 'audit_trail_id DESC'
            )
        );
        $table->useAjax = true;
        return $table->render();
    }
}