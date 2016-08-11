<?php
class SystemUsersController extends ModelController 
{
    public $listFields = array(
        ".users.user_id",
        ".users.user_name",
        ".users.first_name",
        ".users.last_name",
        ".roles.role_name"
    );
    
    public function __construct()
    {
        parent::__construct(".users");
        $this->table->addOperation('reset', "Reset Password");
    }

    public function reset($params)
    {
        $this->model->queryResolve = false;
        $user = $this->model->getWithField2('user_id', $params[0]);
        $user[0]['user_status'] = '2';
        $this->model->setData($user[0]);
        $this->model->update('user_id', $params[0]);
        Application::redirect($this->urlPath);
    }
}