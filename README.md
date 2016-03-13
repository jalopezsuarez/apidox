Apidox
=================

[Apidox](https://apidox.net) Live Interactive API Documentation and Browsing for RESTful APIs, `Apidox`

## Description
Apidox is a small, simple and powerfull PHP application that creates a documentation from API XML structure definitions or annotations in your source code.

With Apidox, your developers spend less time toggling, cutting, and pasting—and more time coding great apps even testing API in severals envirnoments, because Apidox lets you change the base URL on-fly.

APIDox gives you:

- XML Powered: Easy generation with a simple XML structure.
- Live API: Developers execute calls right from the API documentation.
- Documentation: One place for docs and testing.
- Easy Playing: Pre-populated testing values over APIs.
- Switch Servers: Change server environment before testing.
- Share API: Share it with your teammates or customers.

### Apidox Web Application Minimum Requirements

- Any Web Server/PHP 5.4+ with CURL enabled.
- A Web Browser ;)

## How Apidox Works

Apidox automatically generate your API documentation on fly. Apidox has two ways to generate the API documentation: XML file and folders structure and inline Annotations on sorce code.

### XML Mode

The API definition is automatically generated using a tree on /api/ directory. Each subdirectory on api/ folder is a ENDPOINT and each XML file in ENDPOINT directories is a METHOD of this ENDPOINT. The server supports full API versioning.

#### API XML Documenation Structure

APIDox definition structure starts from API folder in apidox homefolder.

```
apidox/api
├── config.xml
└── ├── index.xml
    ├── errors.xml
    ├── endpoint1/
    │   ├── method_a.xml
    │   ├── method_b.xml
    │   ├── method_c.xml
    │   └── index.xml
    ├── endpoint2/
    │   ├── method_d.xml
    │   └── method_e.xml
    └── endpoint(n)/
        ├── method_f.xml
        ├── method_g.xml
        └── index.xml
```

#### config.xml

This file defines basic API's information:

```xml
<!DOCTYPE config SYSTEM "config.dtd">
<config title="OMDb API The Open Movie Database" version="1.0" uri="www.omdbapi.com/" scheme="http://" />
```

```config``` node may contain the following attributes:
- ```title```: main title for the API.
- ```version```: indicates the api version.
- ```uri```: URI to test the API.
- ```scheme```: scheme used on main server URI.

#### index.xml

The ```index.xml``` files define the listing order of endpoints and methods. The root folder (of each version) may contain an ```index.xml``` file for endpoints, whereas each endpoint may contain (in its folder) an ```index.xml``` file for methods. The ```xml``` syntax is:

```xml
<!DOCTYPE index SYSTEM "index.dtd">
<index>
	<order name="search" />
	<order name="movie" />
</index>
```

#### errors.xml

An ```errors.xml``` file can be placed on each version root folder to define different error codes returned by API calls. These error codes can be categorized according to this file structure:

```xml
<!DOCTYPE errors SYSTEM "errors.dtd">
<errors>
	<category name="General">
		<error code="100" description="Something went wrong." />
		<error code="101" description="Movie not found!" />
	</category>
	<category name="Search">
		<error code="200" description="Series or season not found!" />
	</category>
</errors>
```

### Annotations Mode

TODO!

## Author & Contributors

- Jose Antonio Lopez | [github.com/jalopezsuarez](https://github.com/jalopezsuarez)
- Sebastian Garcia Rodriguez | [github.com/segarci](https://github.com/segarci)
- Eduardo Estrella Rosario | [github.com/eduestrella](https://github.com/eduestrella)

## Support & Contributing

If you need any help with Apidox, you can reach out to us via the GitHub Issues page at:
<code>[https://github.com/jalopezsuarez/apidox/issues](https://github.com/jalopezsuarez/apidox/issues)</code>

Keep track of development and community updates. Pull requests are welcome!

## Copyright and license

Code and documentation copyright 2015-2016 Apidox. Code released under [the MIT license](https://github.com/jalopezsuarez/apidox/blob/master/apidox/LICENSE).
