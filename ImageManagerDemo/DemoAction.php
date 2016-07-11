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

        $html = '<a href="' . \OLOG\Image\Pages\Admin\ImageListAction::getUrl() . '">Images admin</a>';

        
        echo $html;
    }
}