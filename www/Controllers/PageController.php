<?php
namespace App\Controller;

use App\Core\Render;
use App\Helper\Errors;
use App\Models\Page;


class PageController
{
    public function createForm(): void
    {

        $render = new Render("createPage", "backoffice");
        $render->render();
    }

    public function createPage(): void{
        $page = new Page();
        $data = [
            "title"       => $_POST["title"],
            "description" => $_POST["description"],
            "slug"        => $_POST["slug"],
            "user_id"     => $_SESSION["id"],          
            "status"      => "draft",         
        ];
        $page->insert($data);
    }

    public function showPages():void {
        $page = new Page();
        $pages = $page->findAll();
        $render = new Render("showPages","frontoffice");
        $render->render();
        // Finir show ensuite update et delete
    }

}