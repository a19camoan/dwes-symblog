<?php
    namespace App\Controllers;

    use App\Models\User;

    class UsersController extends BaseController
    {
        public function getAddUser()
        {
            return $this->renderHTML("addUser.twig");
        }

        public function postAddUser($request)
        {
            $postData = $request->getParsedBody();
            $user = new User();
            $user->name = $postData["name"];
            $user->password = password_hash($postData["password"], PASSWORD_DEFAULT);
            $user->email = $postData["email"];
            $user->profile = $postData["profile"];
            $user->save();
            return $this->renderHTML("addUser.twig", [
                "responseMessage" => "Saved"
            ]);
        }
    }
