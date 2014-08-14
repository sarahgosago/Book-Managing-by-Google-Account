<?php

class AppApiView extends View
{
    protected $response = '';

    public function render($action = null)
    {
        $action = is_null($action) ? $this->controller->action : $action;
        if (strpos($action, '/') === false) {
            $view_filename = VIEWS_DIR . $this->controller->name . '/' . $action . self::$ext;
        } else {
            $view_filename = VIEWS_DIR . $action . self::$ext;
        }

        $response = self::extract($view_filename, $this->vars);

        if (!defined('STDIN')) {
            header('Content-Type: application/json');
        }

        $this->controller->output .= json_encode($response);
    }

    public static function extract($filename, $vars)
    {
        if (!file_exists($filename)) {
            throw new DCException("{$filename} is not found");
        }

        extract($vars, EXTR_SKIP);
        include $filename;

        return $response;
    }
}
