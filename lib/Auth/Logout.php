<?php

namespace SimpleSAML\Module\attrauthgocdb;

/**
 *
 * @package SimpleSAMLphp
 */
class Logout {

  public static function postLogout(SimpleSAML\IdP $idp, array $state) {
    $url = SimpleSAML\Module::getModuleURL('attrauthgocdb/logout_completed.php');
    \SimpleSAML\Utils\HTTP::redirectTrustedURL($url);
  }

}
