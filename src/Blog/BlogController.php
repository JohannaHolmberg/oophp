<?php

namespace Jo\Blog;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

// require 'function.php';

// require __DIR__ . "/function.php";
require_once __DIR__ . "/function.php";
/*
* @SuppressWarnings(PHPMD.UnusedFormalParameter)
* @SuppressWarnings(PHPMD.UnusedPrivateField)
* @SuppressWarnings(PHPMD.CyclomaticComplexity)
*/


class BlogController extends Database implements AppInjectableInterface
{
    use AppInjectableTrait;

    /**
     * This is the index method action:
     *
     * @return object
     */
    public function indexAction() : object
    {
        $title = "Blog index";
        $db = $this->app->db;
        // Connect to database
        $db->connect(); // method from Database class!

        $sqlQuestions = new SqlQuestions();

        // Get incoming
        $route = getGetBlog("route", "");
        $view = [];
        $sql = null;
        $resultset = null;

        // Simple router
        switch ($route) {
            case "":
                $title = "Show all content";
                $view[] = "show-all.php";
                $sql = "SELECT * FROM content;";
                $resultset = $db->executeFetchAll($sql);

                $this->app->page->add("blog/show-all", [
                    "resultset" => $resultset,
                ]);

                break;

            case "reset":
                $title = "Resetting the database";
                $view[] = "reset.php";


                $this->app->page->add("blog/reset", [
                    "resultset" => $resultset
                ]);
                break;

            case "admin":
                $title = "Admin content";
                $view[] = "admin.php";
                $sql = "SELECT * FROM content;";
                $resultset = $db->executeFetchAll($sql);
                $this->app->page->add("blog/admin", [
                    "resultset" => $resultset,
                ]);
                break;

            case "edit":
                $title = "Edit content";
                $view[] = "edit.php";
                $contentId = getPostBlog("contentId") ?: getGetBlog("id");

                // if (!is_numeric($contentId)) {
                //     die("Not valid for content id.");
                // }

                // Delete or Save
                if (hasKeyPostBlog("doDelete")) {
                    $this->app->response->redirect("?route=delete&id=$contentId");
                } elseif (hasKeyPostBlog("doSave")) {
                    $params = getPostBlog([
                        "contentTitle",
                        "contentPath",
                        "contentSlug",
                        "contentData",
                        "contentType",
                        "contentFilter",
                        "contentPublish",
                        "contentId"
                    ]);


                    // blogposts have slugs
                    // if no slug in entered, make a slug from title
                    if (!$params["contentSlug"]) {
                        $params["contentSlug"] = slugifyBlog($params["contentTitle"]);
                    }

                    // pages has Paths
                    // if no content path is inserted, then set path title slugify
                    if (!$params["contentPath"]) {
                        $params["contentPath"] = slugifyBlog($params["contentTitle"]);
                    }

                    // if there is a double, add a number to the end so there are no doubles
                    // get all from database
                    $sql = "SELECT slug FROM content;";
                    $resultset = $db->executeFetchAll($sql);
                    //var_dump($resultset); //array with all of the slugs

                    // search through each in a foreach to make sure there are no doubles
                    foreach ($resultset as $first => $value) {
                        // var_dump($value);
                        foreach ($value as $str) {
                            // var_dump($str);
                            if ($str == $params["contentSlug"]) {
                                var_dump("doulbe");
                                $params["contentSlug"] = $params["contentSlug"] . "-1";
                                $params["contentPath"] = $params["contentSlug"];
                                var_dump($params["contentSlug"]);
                            }
                        }
                    }

                    $sql = "UPDATE content SET title=?, path=?, slug=?, data=?, type=?, filter=?, published=? WHERE id = ?;";
                    $db->execute($sql, array_values($params));
                    $this->app->response->redirect("?route=admin");
                }

                $sql = "SELECT * FROM content WHERE id = ?;";
                $content = $db->executeFetch($sql, [$contentId]);


                $this->app->page->add("blog/edit", [
                    "resultset" => $resultset,
                    "content" => $content
                ]);
                break;

            case "create": // create both page and blog post
                $title = "Create content";
                $view[] = "create.php";
                var_dump("am I here?");

                if (hasKeyPostBlog("doCreate")) {
                    $title = getPostBlog("contentTitle");

                    $sql = "INSERT INTO content (title) VALUES (?);";
                    $db->execute($sql, [$title]);
                    $id = $db->lastInsertId();
                    var_dump("am I here2?");
                    $this->app->response->redirect("?route=edit&id=$id");
                }
                $this->app->page->add("blog/create", [
                    "resultset" => $resultset,
                ]);
                break;

            case "delete":
                $title = "Delete content";
                $view[] = "delete.php";
                $contentId = getPostBlog("contentId") ?: getGetBlog("id");
                // if (!is_numeric($contentId)) {
                //     die("Not valid for content id.");
                // }

                if (hasKeyPostBlog("doDelete")) {
                    $contentId = getPostBlog("contentId");
                    $sql = "UPDATE content SET deleted=NOW() WHERE id=?;";
                    $db->execute($sql, [$contentId]);
                    $this->app->response->redirect("?route=admin");
                }

                $sql = "SELECT id, title FROM content WHERE id = ?;";
                $content = $db->executeFetch($sql, [$contentId]);

                $this->app->page->add("blog/delete", [
                    "resultset" => $resultset,
                    "content" => $content
                ]);
                break;

            case "pages":
                $title = "View pages";
                $view[] = "pages.php";

                $sql = $sqlQuestions->sqlPages();
                $resultset = $db->executeFetchAll($sql, ["page"]);

                $this->app->page->add("blog/pages", [
                    "resultset" => $resultset
                ]);
                break;



            case "blog":
                $title = "View blog";
                $view[] = "blog.php";

                $sql = $sqlQuestions->sqlBlog(); // get sql from sqlQue

                $resultset = $db->executeFetchAll($sql, ["post"]);

                $this->app->page->add("blog/blog", [
                    "resultset" => $resultset
                ]);
                break;

            default:
                if (substr($route, 0, 5) === "blog/") {
                    //  Matches blog/slug, display content by slug and type post
                    $sql = $sqlQuestions->sqlSubBlog();
                    var_dump("im here");
                    $slug = substr($route, 5);
                    $content = $db->executeFetch($sql, [$slug, "post"]);
                    if (!$content) {
                        header("HTTP/1.0 404 Not Found");
                        $title = "404";
                        $view[] = "view/404.php";
                        break;
                    }
                    $title = $content->title;
                    $view[] = "blogpost.php";


                    $textfilter = new MyTextfilter();

                    // slit string toa array
                    $filters = explode(",", $content->filter);

                    // run foreach for each filter entered
                    foreach ($filters as $filter) {
                        $content->data = $textfilter->parse($content->data, $filter);
                    }

                    $this->app->page->add("blog/blogpost", [
                        "resultset" => $resultset,
                        "content" => $content,
                        "text" => $content->data

                    ]);
                } else {
                        // Try matching content for type page and its path
                        $sql = <<<EOD
SELECT
    *,
    DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%dT%TZ') AS modified_iso8601,
    DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%d') AS modified
FROM content
WHERE
    path = ?
    AND type = ?
    AND (deleted IS NULL OR deleted > NOW())
    AND published <= NOW()
;
EOD;
                    $content = $db->executeFetch($sql, [$route, "page"]);
                    //var_dump($content);

                    if (!$content) {
                        header("HTTP/1.0 404 Not Found");
                        $title = "404";
                        $view[] = "404.php";
                        break;
                    }
                    $title = $content->title;
                    $view[] = "page.php";

                    $textfilter = new MyTextfilter();

                    // slit string toa array
                    $filters = explode(",", $content->filter);
                    // var_dump($filters);

                    // run foreach for each filter entered
                    foreach ($filters as $filter) {
                        $content->data = $textfilter->parse($content->data, $filter);
                    }


                    $this->app->page->add("blog/page", [
                        "resultset" => $resultset,
                        "content" => $content,
                        "text" => $content->data

                    ]);
                }
        }
        return $this->app->page->render(["title" => $title,]);
    } // end indexAction()


    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function debugAction() : string
    {
        // Deal with the action and return a response.
        return "Debug my Blog!";
    }
}
