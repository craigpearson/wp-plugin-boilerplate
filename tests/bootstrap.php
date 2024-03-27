<?php
require_once dirname(__DIR__).'/vendor/autoload.php';

WP_Mock::setUsePatchwork(true);
// Enable God Mode
// WP_Mock::activateStrictMode();
WP_Mock::bootstrap();
