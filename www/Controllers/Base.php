<?php
namespace App\Controller;

use App\Core\Render;
use App\Helper\Errors;

class Base
{
    public function index($data = null): void
    {

        $render = new Render("home", "frontoffice");
        if($data){
            $render->assign("name", $data["username"]);
        }
        $render->render();
    }

    public function contact(): void
    {
        new Render("contact");
    }

    public function portfolio(): void
    {
        new Render("portfolio");
    }


}