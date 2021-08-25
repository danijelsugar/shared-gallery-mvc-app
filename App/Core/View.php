<?php

namespace Gallery\Core;

class View
{

    private $layout;

    public function __construct($layout = "layout")
    {
        $this->layout = basename($layout);
    }

    /**
     * renders page
     */
    public function render($name, $args = [])
    {
        ob_start();
        extract($args);
        require BP . "App/View/{$name}.php";
        $content = ob_get_clean();

        if ($this->layout) {
            require BP .  "App/View/{$this->layout}.php";
        } else {
            echo $content;
        }
        return $this;
    }
}
