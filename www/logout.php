<?php
/**
 * This is the handler for logout started from the error presentation page.
 *
 * @package SimpleSAMLphp
 */

if (!array_key_exists('StateId', $_GET)) {
    throw new SimpleSAML_Error_BadRequest('Missing required StateId query parameter.');
}
$state = SimpleSAML_Auth_State::loadState($_GET['StateId'], 'attrauthgocdb:error_state');

$state['Responder'] = array('sspmod_attrauthgocdb_Logout', 'postLogout');

$idp = SimpleSAML_IdP::getByState($state);
$idp->handleLogoutRequest($state, NULL);
assert('FALSE');
