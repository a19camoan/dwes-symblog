<?php
    namespace App\Controllers;

    use App\Controllers\BaseController;

    class AdminController extends BaseController
    {
        public function getAdmin()
        {
            ob_start();
            return $this->renderHTML("admin.twig", [
                "profile" => $_SESSION["profile"]
            ]);
        }
    }
