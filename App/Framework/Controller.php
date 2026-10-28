<?php

namespace App\Framework;

use App\Framework\Request;
use App\Framework\TemplateViewerInterface;

abstract class Controller
{
    protected Request $request;
    protected TemplateViewerInterface $view;
    // protected Response $response;
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setView(TemplateViewerInterface $view)
    {
        $this->view = $view;
    }
}
