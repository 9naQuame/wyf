<?php

class SystemConfigurationsModel extends ORMSQLDatabaseModel
{
    public $database = '.configurations';
    public $disableAuditTrails = true;
}