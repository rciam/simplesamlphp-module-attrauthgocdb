# simplesamlphp-module-attrauthgocdb

A SimpleSAMLphp module for retrieving attributes from the Grid Configuration Database (GOCDB) and adding them to the list of attributes received from the identity provider.

## Configuration

The following configuration options are available:

 * `api_base_path`: The base path of the GOCDB API.
 * `api_base_path.slaves`: Array list of fallback GOCDB instances.
 * `subject_attributes`: The name of one or more attributes whose value(s) will be used for querying the user's roles.
 * `role_attribute`: The name of the attribute that will contain the retrieved user's role(s).
 * `role_urn_namespace`: The namespace of the URN that will be used for generating the role attribute values.
 * `role_scope`: (Optional) When specified, the generated role attribute values will be scoped at the supplied administrative domain.
 * `ssl_client_cert`: (Optional) The name of the certificate file to use for client authentication against the GOCDB API.
 * `ssl_verify_peer`: (Optional) Whether to verify the SSL certificate of the HTTPS server providing access to the GOCDB API. Defaults to `true`.

### Example configuration

```
'authproc' => array(
    ...
    '60' => array(
         'class' => 'attrauthgocdb:Client',
         'api_base_path' => 'https://gocdb.aa.org/api',
         'api_base_path.slaves' => array('https://gocdb.aa.org/slave/api'),
         'subject_attributes' => array(
             'distinguishedName',
         ),
         'role_attribute' => 'eduPersonEntitlement',
         'role_urn_namespace' => 'urn:mace:aa.org',
         'role_scope' => 'vo.org',
         'ssl_client_cert' => 'client_example_org.chained.pem',
         'ssl_verify_peer' => true,
    ),
```

## Compatibility matrix

This table matches the module version with the supported SimpleSAMLphp version.

| Module |  SimpleSAMLphp |
|:------:|:--------------:|
| v1.0   | v1.14          |

# License

Licensed under the Apache 2.0 license, for details see `LICENSE`.
