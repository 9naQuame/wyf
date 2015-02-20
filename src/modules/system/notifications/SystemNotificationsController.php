<?php
class SystemNotificationsController extends Controller
{
    public function getContents() 
    {
        ntentan\logger\Logger::info("Reading Notifications");
        Application::$template = false;
        header('Content-Type: application/json');
        $response = json_encode($_SESSION['notifications']);
        $_SESSION['notifications'] = array();
        return $response;
    }
}
