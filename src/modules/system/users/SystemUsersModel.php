<?php
class SystemUsersModel extends ORMSQLDatabaseModel
{
    public $database = '.users';
    
    public function preValidateHook()
    {
        if($this->datastore->data["password"]=="")
        {
            $this->datastore->data["password"] = md5($this->datastore->data["user_name"]);
        }
        unset($this->datastore->data['user_id']);
        if($this->datastore->data['role_id'] == 1 && $_SESSION['role_id'] != 1)
        {
            return array(
                'role_id' => array(
                    'Non super users cannot assign super users'
                )
            );
        }
    }
    
    public function preAddHook()
    {
        $this->datastore->data["user_status"] = 2;
    }    
}
