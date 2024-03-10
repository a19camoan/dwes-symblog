<?php
    namespace App\Controllers;

    use App\Models\User;
    use Laminas\Diactoros\Response\RedirectResponse;

    class AuthController extends BaseController
    {
        public function getLogin()
        {
            return $this->renderHTML("login.twig");
        }

        public function postLogin($request)
        {
            $postData = $request->getParsedBody();
            $responseMessage = null;

            $user = User::where("email", $postData["email"])->first();
            if ($user) {
                if (password_verify($postData["password"], $user->password)) {
                    ob_start();
                    $_SESSION["userId"] = $user->id;
                    $_SESSION["profile"] = $user->profile;
                    return new RedirectResponse("/admin");
                } else {
                    $responseMessage = "Bad credentials";
                }
            } else {
                $responseMessage = "Bad credentials";
            }
            return $this->renderHTML("login.twig", [
                "responseMessage" => $responseMessage,
                "profile" => $_SESSION["profile"]
            ]);
        }

        public function getLogout()
        {
            unset($_SESSION["userId"]);
            $_SESSION["profile"] = "guest";
            return new RedirectResponse("/login");
        }
    }

