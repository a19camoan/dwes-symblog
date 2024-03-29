<?php
    namespace App\Controllers;

    use Laminas\Diactoros\Response\HtmlResponse;
    use Twig\Environment;
    use Twig\Loader\FilesystemLoader;

    class BaseController
    {
        protected $templateEngine;

        public function __construct()
        {
            $loader = new FilesystemLoader("../app/Views");
            $this->templateEngine = new Environment($loader, [
                "debug" => true,
                "cache" => false
            ]);
        }

        public function renderHTML($fileName, $data = [])
        {
            return new HtmlResponse($this->templateEngine->render($fileName, $data));
        }
    }
