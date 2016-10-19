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
        $html = '<h2><a href="'. (new \OLOG\Auth\Admin\AuthAdminAction())->url() .'">Auth admin</a></h2>';
        $html .= '<h2><a href="' . (new \OLOG\Image\Pages\Admin\ImageListAction())->url() . '">Images admin</a><h2>';

        
        echo $html;
    }
}