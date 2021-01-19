<?php

namespace SimpleSAML\Module\attrauthgocdb;

use SimpleSAML\Module;
use SimpleSAML\Utils\HTTP;

/**
 *
 * @package SimpleSAMLphp
 */
class Logout
{

    public static function postLogout(SimpleSAML\IdP $idp, array $state)
    {
        $url = Module::getModuleURL('attrauthgocdb/logout_completed.php');
        HTTP::redirectTrustedURL($url);
    }
}
