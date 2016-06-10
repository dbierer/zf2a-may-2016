Advanced View Layer Module: Unit 3: I18N
========================================

Scenario
--------
You are creating a proposal in a bid to win a lucrative web development contract.
The customer has announced that the website must be based on a highly performant
open source software platform, and must support multiple languages.

After some research you decide to adopt Zend Framework 2 as your platform.
As a demonstration you have decided to use the Zend Skeleton App, and to include
a drop down menu representing 17 major languages already translated as part
of the skeleton app.

Exercise
--------
Look for the "/** -- Task" tags and follow the instructions

1. Define a "translator" factory service in the Application/config/module.config.php file
2. Expand the Application/Module.php "onDispatch()" method to override the default locale
3. Modify Application/view/* files incorporating the "translate()" view helper
