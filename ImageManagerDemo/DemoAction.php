<?php

namespace ImageManagerDemo;


class DemoAction
{
    public static function getUrl()
    {
        return '/';
    }

    public function action()
    {
        ob_start();
        $this->renderContent();
        $html = ob_get_clean();

        \OLOG\BT\Layout::render($html, $this);
    }

    public function renderContent()
    {
        $html = '<h2><a href="'. \OLOG\Auth\Admin\AuthAdminAction::getUrl() .'">Auth admin</a></h2>';
        $html .= '<h2><a href="' . \OLOG\Image\Pages\Admin\ImageListAction::getUrl() . '">Images admin</a><h2>';

        
        echo $html;
    }
}