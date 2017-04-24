<?php

namespace app\components;

class View
{

    public $layout = null;

    private function fetchPartial($template, $params = array())
    {
        extract($params);
        ob_start();
        include BASE_DIR . "/app/views/" . $template . '.php';
        return ob_get_clean();
    }

    private function fetch($template, $params = array())
    {
        $content = $this->fetchPartial($template, $params);

//        return $this->fetchPartial('layouts/frontend', array('content' => $content));
        return $this->fetchPartial($this->layout, array('content' => $content));
    }

    public function render($template, $params = array())
    {
        echo $this->fetch($template, $params);
    }
}