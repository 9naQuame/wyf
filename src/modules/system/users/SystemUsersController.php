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

    public $modelName = ".users";

    public function setupListView() 
    {
        $this->listView->addOperation('reset', 'Reset Password');
    }
    
    public function reset($params)
    {
        $user = reset($this->model->setQueryResolve(false)->getWithField('user_id', $params[0]));
        $user['user_status'] = 2;
        $this->model->setData($user);
        $this->model->update('user_id', $params[0]);
        Application::redirect($this->urlPath);
    }
}