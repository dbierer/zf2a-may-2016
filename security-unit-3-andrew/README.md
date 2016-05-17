Security Module: Unit 3 -- LDAP Authentication
==============================================

# Scenario

Your customer wishes to expand the functionality of their rapidly growing micro-blog website zf2widder by providing for an overall site 
administrator and tying the userbase into an OpenLDAP server. After some analysis you have decided to use the Zend\Authentication\Adapter\Ldap 
class for authentication. In addition, you will need to modify the core logic for adding and updating users, converting away from a database 
table methodology, towards the LDAP equivalents, using the Zend\Ldap\* family of components. 

# LDAP Server Configuration

Here is a the tree structure for this lab:

dc=company,dc=com

ou=zf2widder

  cn=adminTwo

	departmentNumber: 2

  cn=clark

    departmentNumber: 1

  cn=doug

    departmentNumber: 1

  cn=guest

    departmentNumber: 0

  cn=bob

NOTE: all passwords = "password"

# zf2widder configuration

1. Database dump is located under /docs
2. Make sure /data/* structure is writeable
3. All passwords are "password"
4. Admin user: "admin@zend.com"
5. Test users: "doug@unlikelysource.com", "clark.e@zend.com"
6. Modify /config/autoload/*.local.php with local params
7. Assumes your ZF2 distribution is available

