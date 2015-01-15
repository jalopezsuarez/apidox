![IPv6](apidox.png)

#APIDox
 Interactive Documentation for RESTful web APIs

MAJOR CHANGE LOG
================

### 2015-01-15 - APIDox First release for the PHP web application.
* First Version 

SYNOPSIS
--------
APIDox is a WebApplication wrotten in PHP to help creating APIs Documentations to test using XMLs to define endpoints and methods.

APIDox gives you:

- Fast, easy docs layout: No more worrying about how to format docs. Our clean, functional layout is a favorite for developers.

- One place for docs and testing: Developers execute calls right from the docs, so there's no need to jump back and forth to a separate console.

APIDox support online testing APIs as well as static samples definitions including Errors Codes and Success JSON.

INSTALLATION INSTRUCTIONS FOR PHP
---------------------------------
1. PHP 5.4 or greater.

Just copy the apidox folder on any PHP server (even MAMP, LAMP, WAMP or similar) an point your browser to {server}/apidox/apidox.php

CONFIGURING API DEFINITION LOCATION
-----------------------------------

The API definition is automatically generated using a tree on /api/ directory. Each subdirectory on api/ folder is a ENDPOINT and each XML file in ENDPOINT directories is a METHOD of this ENDPOINT.


QUICK API CONFIGURATION EXAMPLE
-------------------------------

/apidox/api/
+ endpoint1/
   + method1.xml
   + method2.xml
+ endpoint2/
   + method3.xml
   + method4.xml

CREDITS
=======

Jose Antonio Lopez Suarez (https://github.com/jalopezsuarez)

Sebastian Garcia Rodriguez

SUPPORT
=======
If you need any help with APIDox, you can reach out to us via the GitHub Issues page at:
<code>[https://github.com/jalopezsuarez/apidox](https://github.com/jalopezsuarez/apidox)</code>
