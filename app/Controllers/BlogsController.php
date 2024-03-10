<?php
    namespace App\Controllers;

    use App\Models\Blog;
    use App\Models\Comment;
    use Respect\Validation\Validator;

    class BlogsController extends BaseController
    {
        public function getAddBlogAction($request)
        {
            $responseMessage = null;
            if ($request->getMethod() == "POST") {
                $postData = $request->getParsedBody();

                $blogValidator = Validator::key("title", Validator::stringType()->notEmpty())
                    ->key("description", Validator::stringType()->notEmpty());
                try {
                    $blog = new Blog();
                    $blog->title = $postData["title"];
                    $blog->blog = $postData["description"];
                    $blog->tags = $postData["tag"];
                    $blog->author = $postData["author"];

                    $files = $request->getUploadedFiles();
                    $image = $files["image"];
                    if ($image->getError() == UPLOAD_ERR_OK) {
                        $fileName = $image->getClientFilename();
                        $fileName = "image_" . $blog->id . "." . pathinfo($fileName, PATHINFO_EXTENSION);
                        $image->moveTo("./img/$fileName");
                        $blog->image = $fileName;
                    }
                    $blog->save();
                    $responseMessage = "Saved";
                } catch (\Exception $e) {
                    $responseMessage = $e->getMessage();
                }
            }
            return $this->renderHTML("addBlog.twig", [
                "responseMessage" => $responseMessage
            ]);
        }

        public function showBlogAction($request)
        {
            $blogId = $request->getUri()->getPath();
            $blogId = explode("/", $blogId)[2];
            $blog = Blog::find($blogId);
            $comments = Comment::where("blog_id", $blogId)->get();
            return $this->renderHTML("showBlog.twig", [
                "blog" => $blog,
                "comments" => $comments
            ]);
        }

        public function aboutAction($request)
        {
            return $this->renderHTML("page/about.html.twig");
        }

        public function contactAction($request)
        {
            return $this->renderHTML("page/contact.html.twig");
        }
    }
