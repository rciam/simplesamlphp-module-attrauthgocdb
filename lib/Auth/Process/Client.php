<?php

/**
 * Authproc filter for retrieving attributes from the Grid Configuration 
 * Database (GOCDB) and adding them to the list of attributes received from the
 * identity provider.
 *
 * Example configuration:
 *
 *    'authproc' => array(
 *       ...
 *       '60' => array(
 *            'class' => 'attrauthgocdb:Client',
 *            'api_base_path' => 'https://gocdb.aa.org/api',
 *            'subject_attribute' => 'distinguishedName',
 *            'role_attribute' => 'eduPersonEntitlement',
 *            'role_urn_namespace' => 'urn:mace:aa.org',
 *            'role_scope' => 'vo.org',
 *            'ssl_client_cert' => 'client_example_org.chained.pem',
 *            'ssl_verify_peer' => true,
 *       ),
 *
 * @author Nicolas Liampotis <nliam@grnet.gr>
 */
class sspmod_attrauthgocdb_Auth_Process_Client extends SimpleSAML_Auth_ProcessingFilter
{
    private $config = array();

    public function __construct($config, $reserved)
    {
        parent::__construct($config, $reserved);
        $params = array(
            'api_base_path', 
            'subject_attribute', 
            'role_attribute',
            'role_urn_namespace',
        );
        foreach ($params as $param) {
            if (!array_key_exists($param, $config)) {
                throw new SimpleSAML_Error_Exception(
                    'Missing required configuration parameter: ' .$param);
            }
            $this->config[$param] = $config[$param];
        }
        $optional_params = array(
            'role_scope',
            'ssl_client_cert',
            'ssl_verify_peer', 
        );
        foreach ($optional_params as $optional_param) {
            if (array_key_exists($optional_param, $config)) {
                $this->config[$optional_param] = $config[$optional_param];
            }
        }
    }

    public function process(&$state)
    {
        try {
            assert('is_array($state)');
            if (!array_key_exists($this->config['subject_attribute'], $state['Attributes'])) {
                SimpleSAML_Logger::debug("[aagocdb]"
                    ." Skipping query to GOCDB AA at "
                    .$this->config['api_base_path']
                    .": No attribute named '"
                    .$this->config['subject_attribute']
                    ."' in state information.");
                return;
            }
            $t0 = round(microtime(true) * 1000); // TODO
            $subject_ids = $state['Attributes'][$this->config['subject_attribute']];
            foreach ($subject_ids as $subject_id) {
                $newAttributes = $this->getAttributes($subject_id);
                SimpleSAML_Logger::debug("[aagocdb]"
                    ." process: newAttributes="
                    .var_export($newAttributes, true));
                foreach($newAttributes as $key => $value) {
                    if (empty($value)) {
                        unset($newAttributes[$key]);
                    }
                }
                if(!empty($newAttributes)) {
                    if (!isset($state['Attributes'][$this->config['role_attribute']])) {
                        $state['Attributes'][$this->config['role_attribute']] = array();
                    }
                    $state['Attributes'][$this->config['role_attribute']] = array_merge(
                        $state['Attributes'][$this->config['role_attribute']],
                        $newAttributes[$this->config['role_attribute']]
                    );
                }
            }
            $t1 = round(microtime(true) * 1000); // TODO 
            SimpleSAML_Logger::debug(
                "[aagocdb] process: dt=" . var_export($t1-$t0, true) . "msec");
        } catch (\Exception $e) {
            $this->showException($e);
        }

    }

    public function getAttributes($subject_id)
    {
        $attributes = array();
        SimpleSAML_Logger::debug('[aagocdb] getAttributes: subject_id='.var_export($subject_id, true));

        // Set up config
        $config = $this->config;

        // Setup cURL
        $url = $this->config['api_base_path'].'/?method=get_user&dn=' 
            . urlencode($subject_id);
        SimpleSAML_Logger::debug('[aagocdb] getAttributes: url='.var_export($url, true));
        $ch = curl_init($url);
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSLCERT => \SimpleSAML\Utils\Config::getCertPath($this->config['ssl_client_cert']),
                CURLOPT_CONNECTTIMEOUT => 8,
            )
        );

        // Send the request
        $response = curl_exec($ch);
        $http_response = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Check for error; not even redirects are allowed here
        if ($http_response == 507) {
            throw new SimpleSAML_Error_Exception("Out of resources: " . $response);
        } elseif ($response === false || !($http_response >= 200 && $http_response < 300)) {
            SimpleSAML_Logger::error('[aagocdb] API query failed: HTTP response code: '.$http_response.', curl error: "'.curl_error($ch)).'"';
            SimpleSAML_Logger::debug('[aagocdb] API query failed: curl info: '.var_export(curl_getinfo($ch), 1));
            SimpleSAML_Logger::debug('[aagocdb] API query failed: HTTP response: '.var_export($response, 1));
            throw new SimpleSAML_Error_Exception("Error at REST API response: ". $response . $http_response);
        } else {
            $data = new SimpleXMLElement($response);
            SimpleSAML_Logger::debug('[aagocdb] API query result: '.var_export($data, true));
            if ($data->count() < 1) {
                return $attributes;
            } 
            $attributes[$this->config['role_attribute']] = array();
            foreach($data->{'EGEE_USER'}->{'USER_ROLE'} as $user_role) {
                $value = $this->config['role_urn_namespace']
                    . ':' . urlencode($user_role->{'PRIMARY_KEY'})
                    . ':' . urlencode($user_role->{'ON_ENTITY'})
                    . ':' . urlencode($user_role->{'USER_ROLE'});
                if (isset($this->config['role_scope'])) {
                    $value .= '@' . $this->config['role_scope'];
                }
                $attributes[$this->config['role_attribute']][] = $value;
            }
        }
        SimpleSAML_Logger::debug('[aagocdb] getAttributes: attributes=' . var_export($attributes, true));
        return $attributes;
    }

    private function showException($e)
    {
        $globalConfig = SimpleSAML_Configuration::getInstance();
        $t = new SimpleSAML_XHTML_Template($globalConfig, 'attrauthgocdb:exception.tpl.php');
        $t->data['e'] = $e->getMessage();
        $t->show();
        exit();
    }
}
