<?php
    require_once __DIR__ . "/../vendor/autoload.php";

    use Dotenv\Dotenv;
    use Illuminate\Database\Capsule\Manager as Capsule;
    use Laminas\Diactoros\ServerRequestFactory;
    use Aura\Router\RouterContainer;

    session_start();

    if (!isset($_SESSION["profile"])) {
        $_SESSION["profile"] = "guest";
    }

    $dotenv = Dotenv::createImmutable(__DIR__ . "/../");
    $dotenv->load();

    $capsule = new Capsule;

    $capsule->addConnection([
        "driver" => "mysql",
        "host" => $_ENV["DBHOST"],
        "database" => $_ENV["DBNAME"],
        "username" => $_ENV["DBUSER"],
        "password" => $_ENV["DBPASS"],
        "charset" => "utf8",
        "collation" => "utf8_unicode_ci",
        "prefix" => ""
    ]);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    $request = ServerRequestFactory::fromGlobals(
        $_SERVER,
        $_GET,
        $_POST,
        $_COOKIE,
        $_FILES
    );

    $routerContainer = new RouterContainer();
    $map = $routerContainer->getMap();

    $map->get("index", "/", [
        "controller" => "App\Controllers\IndexController",
        "action" => "indexAction"
    ]);
    $map->get("addBlog", "/blogs/add", [
        "controller" => "App\Controllers\BlogsController",
        "action" => "getAddBlogAction"
    ]);
    $map->post("saveBlog", "/blogs/add", [
        "controller" => "App\Controllers\BlogsController",
        "action" => "getAddBlogAction"
    ]);
    $map->get("showBlog", "/blog/{id}", [
        "controller" => "App\Controllers\BlogsController",
        "action" => "showBlogAction"
    ])->tokens(["id" => "\d+"]);
    $map->post("addComment", "/comment/save", [
        "controller" => "App\Controllers\CommentController",
        "action" => "postCommentAction"
    ]);
    $map->get("contact", "/contact", [
        "controller" => "App\Controllers\BlogsController",
        "action" => "contactAction"
    ]);
    $map->post("contactPost", "/contact", [
        "controller" => "App\Controllers\BlogsController",
        "action" => "contactAction"
    ]);
    $map->get("addUser", "/users/add", [
        "controller" => "App\Controllers\UsersController",
        "action" => "getAddUser",
        "auth" => true
    ]);
    $map->post("postaddUser", "/users/add", [
        "controller" => "App\Controllers\UsersController",
        "action" => "postAddUser",
        "auth" => true
    ]);
    $map->get("loginForm", "/login", [
        "controller" => "App\Controllers\AuthController",
        "action" => "getLogin"
    ]);
    $map->post("loginFormPost", "/login", [
        "controller" => "App\Controllers\AuthController",
        "action" => "postLogin"
    ]);
    $map->get("adminLogout", "/logout", [
        "controller" => "App\Controllers\AuthController",
        "action" => "getLogout",
        "auth" => true
    ]);
    $map->get("admin", "/admin", [
        "controller" => "App\Controllers\AdminController",
        "action" => "getAdmin",
        "auth" => true
    ]);
    $map->post("adminPost", "/admin", [
        "controller" => "App\Controllers\AdminController",
        "action" => "getAdmin",
        "auth" => true
    ]);
    $map->get("about", "/about", [
        "controller" => "App\Controllers\BlogsController",
        "action" => "aboutAction"
    ]);

    $matcher = $routerContainer->getMatcher();
    $route = $matcher->match($request);

    if (!$route) {
        echo "No route";
    } else {
        $handlerData = $route->handler;
        $controllerName = $handlerData["controller"];
        $actionName = $handlerData["action"];

        $needsAuth = $handlerData["auth"] ?? false;
        $sessionUserId = $_SESSION["userId"] ?? null;
        if ($needsAuth && !$sessionUserId) {
            header("Location: /login");
        } else {
            $controller = new $controllerName;
            $response = $controller->$actionName($request);
            foreach ($response->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    header(sprintf("%s: %s", $name, $value), false);
                }
            }
            http_response_code($response->getStatusCode());
            echo $response->getBody();
        }
    }
