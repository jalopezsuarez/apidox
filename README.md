![IPv6](apidox.png)

# [APIDox](https://github.com/jalopezsuarez/apidox)

Interactive Documentation for RESTful web APIs
 
[![APIDox](http://img.youtube.com/vi/uP9oTPn8umI/0.jpg)](http://www.youtube.com/watch?v=uP9oTPn8umI)

## Major Changelog

#### 2015-01-15: APIDox first release.
* First version PHP Web application.

## Sypnosys

APIDox is a WebApplication wrotten in PHP to help creating APIs Documentations using XML files to define endpoints and methods.

APIDox gives you:

- Fast, easy docs layout: No more worrying about how to format docs. Our clean, functional layout is a favorite for developers.

- One place for docs and testing: Developers execute calls right from the docs, so there's no need to jump back and forth to a separate console.

APIDox support online testing APIs as well as static samples definitions including Errors Codes and Success JSON.

### APIDox Requirements & Installation

- Simple Apache/PHP 5.4+ server is needed in order to run the webapp. No BBDD needed.
- An Updated Web Browser ;)

Just copy the APIDOX folder on any Apache/PHP server (MAMP, LAMP, WAMP or native installed server) and point your browser to {server}/apidox/apidox.php

## Definition API

The API definition is automatically generated using a tree on /api/ directory. Each subdirectory on api/ folder is a ENDPOINT and each XML file in ENDPOINT directories is a METHOD of this ENDPOINT. The server supports full API versioning.

### Quick API Configuration Structure

APIDox definition structure starts from API folder in apidox homefolder.

```
apidox/api
├── api_config.xml
├── v#1
└── ├── index.xml
    ├── endpoint1/
    │   ├── method_a.xml
    │   ├── method_b.xml
    │   ├── method_c.xml
    │   └── index.xml
    ├── endpoint2/
    │   ├── method_d.xml
    │   └── method_e.xml
    └── endpoint...n/
        ├── method_f.xml
        ├── method_g.xml
        ├── method_h.xml
        ├── method_i.xml
        └── index.xml
```

#### api_config.xml

```api_config.xml``` defines basic API's information:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<config title="Google  URL Shortener API" uri="www.googleapis.com" secure="Y"/>
```

```config``` node may contain the following attributes:

- ```title``` (required): main title for the API.
- ```uri``` (required): URI to test the API.
- ```secure``` (optional): specifies the use of https (```secure="Y"```) or http (```secure="N"```).

#### method.xml

The method's info is defined by a ```xml``` file with the following structure:

```xml
<?xml version="1.0" encoding="UTF-8"?>

<method type="GET" uri="urlshortener/v1/url"
	description="To look up a short URLs analytics, issue an expand request, adding a parameter to ask for additional details."
	status="PRODUCTION">

	<param name="shortUrl" type="string" description="Is the short URL
		to expand."
		required="Y" defaultValue="http://goo.gl/fbsS" />

	<param name="projection" type="enumerated"
		description="Additional information to return (using the projection query parameter)"
		required="N">
		<option value="ANALYTICS_CLICKS" description="Analytics clicks projection." />
		<option value="ANALYTICS_TOP_STRINGS" description="Analytics top strings projection." />
		<option defaultValue="Y" value="FULL" description="Full projection." />
	</param>

	<response>
		<success>
<![CDATA[
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "longUrl": "http://www.google.com/",
 "status": "OK",
 "created": "2009-12-13T07:22:55.000+00:00",
 "analytics": {
  "allTime": {
   "shortUrlClicks": "3227",
   "longUrlClicks": "9358",
   "referrers": [ { "count": "2160", "id": "Unknown/empty" } /* , ... */ ],
   "countries": [ { "count": "1022", "id": "US" } /* , ... */ ],
   "browsers": [ { "count": "1025", "id": "Firefox" } /* , ... */ ],
   "platforms": [ { "count": "2278", "id": "Windows" } /* , ... */ ]
  },
  "month": { /* ... */ },
  "week": { /* ... */ },
  "day": { /* ... */ },
  "twoHours": { /* ... */ }
 }
}
]]></success>

		<information>
<![CDATA[
<p>To look up a short URL's analytics, issue an expand request, adding a parameter to ask for additional details. Add &projection=FULL to the API URL, like this:<br></p>
If successful, the response will look like:<br><br>
    + <strong>created</strong> is the time at which this short URL was created. It is specified in ISO 8601.<br>
    + <strong>analytics</strong> contains all the click statistics, broken down into the various time slices. That is, month will contain click statistics for the past month, and so on. For each time slice, shortUrlClicks and longUrlClicks should be present, but the rest may not be (e.g. if there were no clicks).<br>
]]></information>
	</response>

</method>
```

```method``` node may contain the following attributes:

- ```type``` (required): http method (```type="GET"``` or ```type="POST"```).
- ```status``` (required): string indicating the status of the method, e.g. ```DEVELOPMENT```.
- ```uri``` (required): URI to test the method.
- ```description``` (required): synopsis.
- ```deprecated``` (optional): mark the method as deprecated (```deprecated="Y"```).

```method``` node may contain a set of ```param``` nodes. Each ```param``` node may contain the following attributes:

- ```name``` (required): the parameter's name.
- ```required``` (required): indicates if the parameter is required (```required="Y"```) for the method.
- ```defaultValue``` (optional): indicates the default value for the parameter.
- ```type``` (required): parameter data type represented as a string, e.g. ```"int"```. If ```type="enumerated"```, you can define a set of ```option``` nodes inside ```param``` node specifying the available options for the param. Each ```option``` may contain the following attributes:
  - ```value``` (required): the parameter's value.
  - ```description``` (required): description of the value for the parameter.
  - ```defaultValue``` (optional): indicates that option is the default value for the param (```defaultValue="Y"```).

```method``` node also may contain a ```response``` node. This node may contain other nodes: ```success```, ```error``` and ```information```. These nodes contains a ```CDATA``` with the info to show, e.g. a JSON for ```success``` and ```error``` and HTML for ```information```.

#### index.xml

The ```index.xml``` files defines the listing order of endpoints and methods. The root folder (of each version) may contain an ```index.xml``` file for endpoints, whereas each endpoint may contain (in its folder) an ```index.xml``` file for methods. The ```xml``` syntax is:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<index>
	<order name="shorten" />	
</index>
```

The ```xml``` may contain a set of ```order``` nodes indicating the name of the endpoint or method. The first to appear is the first on the listing.

## Creators & Product Development

**Jose Antonio Lopez**
- [github.com/jalopezsuarez](https://github.com/jalopezsuarez)

**Sebastian Garcia Rodriguez**
- [github.com/segarci](https://github.com/segarci)

## Support & Contributing

If you need any help with APIDox, you can reach out to us via the GitHub Issues page at:
<code>[https://github.com/jalopezsuarez/apidox/issues](https://github.com/jalopezsuarez/apidox/issues)</code>

Keep track of development and community updates. Pull requests are welcome!

## Copyright and license

Code and documentation copyright 2015 APIDox. Code released under [the MIT license](https://github.com/jalopezsuarez/apidox/blob/master/apidox/LICENSE).
