<?php

namespace Jo\Movie;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

require "autoload.php";
// require "config.php";
require "function.php";

class MovieController extends Database implements AppInjectableInterface
{
    use AppInjectableTrait;

    /**
     * This is the index method action:
     *
     * @return object
     */
    public function indexAction() : object
    {
        //$title = "Movie database | oophp";

        // Connect to database
        $this->app->db->connect(); // method from Database class!

        //Get incoming - route
        $route = getGet("route", "");

        $view = [];
        $sql = null;
        $resultset = null;


        //$routeSql = new SqlQuestions();
        // Simple router
        switch ($route) {
            case "":
                $title = "Show all movies";
                $view[] = "view/show-all.php";
                $sql = "SELECT * FROM movie;";
                $resultset = $this->app->db->executeFetchAll($sql);

                $this->app->page->add("movie/index", [
                    "resultset" => $resultset,
                ]);
                break;
            case "show-all-sort":
                $title = "Show and sort all movies";
                $view[] = "view/show-all-sort.php";
                // Only these values are valid
                $columns = ["id", "title", "year", "image"];
                $orders = ["asc", "desc"];

                // Get settings from GET or use defaults
                $orderBy = getGet("orderby") ?: "id";
                $order = getGet("order") ?: "asc";

                // Incoming matches valid value sets
                if (!(in_array($orderBy, $columns) && in_array($order, $orders))) {
                    var_dump("Not valid input for sorting.");
                    //die("Not valid input for sorting.");
                }
                $sql = "SELECT * FROM movie ORDER BY $orderBy $order;";
                $resultset = $this->app->db->executeFetchAll($sql);
                $this->app->page->add("movie/show-all-sort", [
                    "resultset" => $resultset,
                ]);
                break;
            case "search-title":
                $title = "Search title";
                $view[] = "view/search-title.php";
                $view[] = "view/show-all.php";
                $searchTitle = getGet("searchTitle"); // get the searched string
                if ($searchTitle) {
                    $sql = "SELECT * FROM movie WHERE title LIKE ?;";
                    $resultset = $this->app->db->executeFetchAll($sql, [$searchTitle]);
                }
                $this->app->page->add("movie/search-title", [
                    "resultset" => $resultset,
                    "searchTitle" => $searchTitle
                ]);
                break;
            case "search-year":
                $title = "Search year";
                $view[] = "view/search-year.php";
                $view[] = "view/show-all.php";
                $year1 = getGet("year1");
                $year2 = getGet("year2");
                if ($year1 && $year2) {
                    $sql = "SELECT * FROM movie WHERE year >= ? AND year <= ?;";
                    $resultset = $this->app->db->executeFetchAll($sql, [$year1, $year2]);
                } elseif ($year1) {
                    $sql = "SELECT * FROM movie WHERE year >= ?;";
                    $resultset = $this->app->db->executeFetchAll($sql, [$year1]);
                } elseif ($year2) {
                    $sql = "SELECT * FROM movie WHERE year <= ?;";
                    $resultset = $this->app->db->executeFetchAll($sql, [$year2]);
                }
                $this->app->page->add("movie/search-year", [
                    "resultset" => $resultset,
                    "year1" => $year1,
                    "year2" => $year2
                ]);
                break;
            case "movie-select":
                $movieId = getPost("movieId");

                if (getPost("doDelete")) {
                    $sql = "DELETE FROM movie WHERE id = ?;";
                    $this->app->db->execute($sql, [$movieId]);
                    header("Location: ?route=movie-select");
                } elseif (getPost("doAdd")) {
                    $sql = "INSERT INTO movie (title, year, image) VALUES (?, ?, ?);";
                    $this->app->db->execute($sql, ["A title", 2017, "img/noimage.png"]);
                    $movieId = $this->app->db->lastInsertId();
                    header("Location: ?route=movie-edit&movieId=$movieId");
                } elseif (getPost("doEdit") && is_numeric($movieId)) {
                    header("Location: ?route=movie-edit&movieId=$movieId");
                }
                $title = "Select a movie";
                $view[] = "view/movie-select.php";
                $sql = "SELECT id, title FROM movie;";
                $movies = $this->app->db->executeFetchAll($sql);

                $this->app->page->add("movie/movie-select", [
                    "resultset" => $resultset,
                    "movies" => $movies
                ]);
                break;
            case "movie-edit":
                $title = "UPDATE movie";
                $view[] = "view/movie-edit.php";
                $movieId    = getPost("movieId") ?: getGet("movieId");
                $movieTitle = getPost("movieTitle");
                $movieYear  = getPost("movieYear");
                $movieImage = getPost("movieImage");

                if (getPost("doSave")) {
                    $sql = "UPDATE movie SET title = ?, year = ?, image = ? WHERE id = ?;";
                    $this->app->db->execute($sql, [$movieTitle, $movieYear, $movieImage, $movieId]);
                    header("Location: ?route=movie-edit&movieId=$movieId");
                }
                $sql = "SELECT * FROM movie WHERE id = ?;";
                $movie = $this->app->db->executeFetchAll($sql, [$movieId]);
                $movie = $movie[0];

                $this->app->page->add("movie/movie-edit", [
                    "resultset" => $resultset,
                    "movie" => $movie
                ]);
                break;
            case "reset":
                $title = "Resetting the database";
                $view[] = "view/reset.php";
                $this->app->page->add("movie/reset");
                break;

            default:
                ;
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
        return "Debug my game!";
    }
}
