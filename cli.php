<?php
require_once 'vendor/autoload.php';

\OLOG\ConfWrapper::assignConfig(\ImageManagerDemo\ImageManagerDemoCommonConfig::get());

\OLOG\Model\CLI\CLIMenu::run();
