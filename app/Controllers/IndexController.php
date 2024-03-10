<?php
    namespace App\Controllers;

    use App\Models\Blog;
    use App\Models\Comment;

    class IndexController extends BaseController
    {
        public function indexAction()
        {
            $comments = Comment::orderBy("created", "desc")->take(5)->get();
            $tags = Blog::all();
            $tags = $tags->pluck("tags");
            $tags = $tags->implode(",");
            $tags = explode(",", $tags);
            $tags = array_count_values($tags);
            $tags = array_slice($tags, 0, 10);

            $blogs = Blog::all();
            return $this->renderHTML("index.twig", [
                "blogs" => $blogs,
                "latestComments" => $comments,
                "tags" => $tags,
                "profile" => $_SESSION["profile"]
            ]);
        }
    }
