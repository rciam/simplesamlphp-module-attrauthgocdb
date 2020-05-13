<?php
/**
 * User Inform Form
 *
 * This script displays a page to the user, which requests that the user
 * authorizes the release of attributes.
 *
 * @package SimpleSAMLphp
 */
/**
 * Explicit instruct attribute selection page to send no-cache header to browsers to make
 * sure the users attribute information are not store on client disk.
 *
 * In an vanilla apache-php installation is the php variables set to:
 *
 * session.cache_limiter = nocache
 *
 * so this is just to make sure.
 */
session_cache_limiter('nocache');
$globalConfig = SimpleSAML_Configuration::getInstance();
SimpleSAML_Logger::info('attrAuthGOCDB - error_state: Accessing error state interface');
if (!array_key_exists('StateId', $_REQUEST)) {
  throw new SimpleSAML_Error_BadRequest(
    'Missing required StateId query parameter.'
  );
}
$id = $_REQUEST['StateId'];
$state = SimpleSAML_Auth_State::loadState($id, 'attrauthgocdb:error_state');

// Get the spEntityId for the privace policy section
if (array_key_exists('core:SP', $state)) {
  $spentityid = $state['core:SP'];
} else if (array_key_exists('saml:sp:State', $state)) {
  $spentityid = $state['saml:sp:State']['core:SP'];
} else {
  $spentityid = 'UNKNOWN';
}

// The user has pressed the yes-button
if ( array_key_exists('yes', $_REQUEST) || array_key_exists('no', $_REQUEST) ) {
  // Remove the fields that we do not want any more
  if (array_key_exists('attrauthgocdb:error_msg', $state)) {
    unset($state['attrauthgocdb:error_msg']);
  }
}

// The user has pressed the yes-button
// The resumeProcessing function needs a ReturnUrl or a ReturnCall in order to proceed
if (array_key_exists('yes', $_REQUEST)) {
  SimpleSAML_Auth_ProcessingChain::resumeProcessing($state);
}


////////////// End of handling users choice
///
///

// Make, populate and layout informed failure consent form
$t = new SimpleSAML_XHTML_Template($globalConfig, 'attrauthgocdb:user_in_form.tpl.php');
$t->data['srcMetadata'] = $state['Source'];
$t->data['dstMetadata'] = $state['Destination'];
$t->data['yesTarget'] = SimpleSAML_Module::getModuleURL('attrauthgocdb/user_in_form.php');
$t->data['yesData'] = array('StateId' => $id);
$t->data['error_msg'] = $state['attrauthgocdb:error_msg'];
$t->data['logoutLink'] = SimpleSAML_Module::getModuleURL('attrauthgocdb/logout.php');
$t->data['logoutData'] = array('StateId' => $id);
// Fetch privacypolicy
if (array_key_exists('privacypolicy', $state['Destination'])) {
  $privacypolicy = $state['Destination']['privacypolicy'];
} elseif (array_key_exists('privacypolicy', $state['Source'])) {
  $privacypolicy = $state['Source']['privacypolicy'];
} else {
  $privacypolicy = false;
}
if ($privacypolicy !== false) {
  $privacypolicy = str_replace(
    '%SPENTITYID%',
    urlencode($spentityid),
    $privacypolicy
  );
}
$t->data['sppp'] = $privacypolicy;
$t->show();
