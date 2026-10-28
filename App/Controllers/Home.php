<?php

namespace App\Controllers;

use App\Framework\PHPViewTemplate;

class Home
{
    public function index()
    {
        $view = new PHPViewTemplate();
        echo $view->render('Home/home.php');
    }
}
