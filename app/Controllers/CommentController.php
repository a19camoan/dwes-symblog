<?php
    namespace App\Controllers;

    use App\Models\Comment;
    use App\Models\User;
    use Laminas\Diactoros\Response\RedirectResponse;

    class CommentController extends BaseController
    {
        public function postCommentAction($request)
        {
            $postData = $request->getParsedBody();
            $comment = new Comment();
            $comment->comment = $postData["comment"];
            $comment->blog_id = $postData["blog_id"];
            if (!isset($_SESSION["userId"])) {
                $comment->user = "Anonymous";
            } else {
                $user = User::find($_SESSION["userId"]);
                $comment->user = $user->name;
            }
            $comment->approved = 1;
            $comment->save();
            return new RedirectResponse("/blog/$postData[blog_id]");
        }
    }
