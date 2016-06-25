<?php

class SystemMyIpController extends Controller
{
    public function getContents() 
    {
        $this->label = "Your system's IP address";
        return "<h1>{$_SERVER['REMOTE_ADDR']}</h1>";
    }
}