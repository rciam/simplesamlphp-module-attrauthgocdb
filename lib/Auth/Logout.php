<?php

/**
 *
 * @package SimpleSAMLphp
 */
class sspmod_attrauthgocdb_Logout {

  public static function postLogout(SimpleSAML_IdP $idp, array $state) {
    $url = SimpleSAML_Module::getModuleURL('attrauthgocdb/logout_completed.php');
    \SimpleSAML\Utils\HTTP::redirectTrustedURL($url);
  }

}
