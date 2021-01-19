<?php

$globalConfig = SimpleSAML\Configuration::getInstance();
$t = new SimpleSAML\XHTML\Template($globalConfig, 'attrauthgocdb:logout_completed.tpl.php');
$t->show();
