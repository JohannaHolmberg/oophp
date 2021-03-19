<?php

namespace Jo\Blog;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

/*
* @SuppressWarnings(PHPMD.UnusedFormalParameter)
* @SuppressWarnings(PHPMD.UnusedPrivateField)
* @SuppressWarnings(PHPMD.CyclomaticComplexity)
*/

class TextFilterController implements AppInjectableInterface
{
    use AppInjectableTrait;

    /**
     * This is the index method action:
     *
     * @return object
     */
    public function indexAction() : object
    {
        $title = "Textfilter index";

        $this->app->page->add("textfilter/index", [
            "title" => $title,
        ]);

        return $this->app->page->render(["title" => $title,]);
    } // end indexAction()



    public function bbcodeAction() : object
    {
        $title = "Show off BBCode";

        $filter = new MyTextFilter();
        $text = "En [b]fet[/b] moped. \r\n Next row";
        $html = $filter->parse($text, "bbcode");

        $this->app->page->add("textfilter/bbcode", [
            "title" => $title,
            "text" => $text,
            "html" => $html
        ]);

        return $this->app->page->render(["title" => $title,]);
    } // end indexAction()


    public function makeClickableAction() : object
    {
        $title = "Show off Clickable";

        $filter = new MyTextFilter();
        $text = "Detta är en länk som kan vara klickbar http://dbwebb.se/coachen.";
        $html = $filter->parse($text, "makeClickable");

        $this->app->page->add("textfilter/makeClickable", [
            "title" => $title,
            "text" => $text,
            "html" => $html
        ]);

        return $this->app->page->render(["title" => $title,]);
    } // end makeClickableAction()


    public function markupAction() : object
    {
        $title = "Show off Markdown";

        $filter = new MyTextFilter();
        $text = "Here is a paragraph with some **bold** text and some *italic* text and a [link to dbwebb.se](http://dbwebb.se).";
        $html = $filter->parse($text, "markdown");

        $this->app->page->add("textfilter/markdown", [
            "title" => $title,
            "text" => $text,
            "html" => $html
        ]);

        return $this->app->page->render(["title" => $title,]);
    } // end markupAction()



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
