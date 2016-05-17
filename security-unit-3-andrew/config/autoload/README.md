# Create a file ldap.local.php which has LDAP configuration

## Return an array with keys as follows:
### service_manager
#### services
###### ldap-config // with LDAP config details as outlined in lab guide
#### invokables
###### ldap-auth-service // authentication service
#### factories
###### ldap-auth-adapter // authentication adapter

