Apidox
=================

[Apidox](http://apidox.net) Live Interactive API Documentation and Browsing for RESTful APIs, `Apidox`

For more information see Apidox [apidox.net](http://apidox.net).

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

#### method.xml

This is the most important file in api definition. Every API method information is defined by a ```xml``` file with the following structure:

```xml
<!DOCTYPE method SYSTEM "method.dtd">
<method hidden="N" type="GET" uri="/" description="Obtain movie information, all content and images on the site are contributed and maintained by our users.">

	<param name="i" type="string" required="N" description="A valid IMDb ID (e.g. tt1285016)." />
	<param name="t" type="string" required="N" value="Game of Thrones" description="Movie title to search for." />
	<param name="type" type="enumerated" required="Y" value="series" description="Type of result to return.">
		<option value="movie" description="Movie data type." />
		<option value="series" description="Series data type." />
		<option value="episode" description="Episode data type." />
	</param>
	<param name="y" type="string" required="N" description="Year of release." />
	<param name="season" type="string" required="N" value="1" description="Season to return." />
	<param name="episode" type="string" required="N" value="1" description="Selected episode." />

	<errors>
		<error code="100" />
		<error code="200" />
	</errors>
	<example><![CDATA[
{

    "Title": "Interstellar",
    "Year": "2014",
    "Rated": "PG-13",
    "Released": "07 Nov 2014",
    "Runtime": "169 min",
    "Genre": "Adventure, Drama, Sci-Fi",
    "Director": "Christopher Nolan",
    "Writer": "Jonathan Nolan, Christopher Nolan",
    "Actors": "Ellen Burstyn, Matthew McConaughey, Mackenzie Foy, John Lithgow",
    "Plot": "A team of explorers travel through a wormhole in space in an attempt to ensure humanity's survival.",
    "Language": "English",
    "Country": "USA, UK",
    "Awards": "Won 1 Oscar. Another 36 wins & 122 nominations.",
    "Poster": "http://ia.media-imdb.com/images/M/MV5BMjIxNTU4MzY4MF5BMl5BanBnXkFtZTgwMzM4ODI3MjE@._V1_SX300.jpg",
    "Metascore": "74",
    "imdbRating": "8.6",
    "imdbVotes": "845,024",
    "imdbID": "tt0816692",
    "Type": "movie",
    "Response": "True"

}	
	]]></example>
	<information><![CDATA[
Please note while both "i" and "t" are optional at least one argument is required.	
	]]></information>
</method>
```

```method``` node may contain the following attributes:

- ```hidden``` (optional): hides the method (```hidden="Y"```). If an endpoint has no methods or all methods are hidden, then the endpoint doesn't appear in the API documentation.
- ```type``` (required): http method (```type="GET"``` or ```type="POST"```).
- - ```uri``` (required): URI to test the method.
- ```description``` (required): synopsis.

```method``` node may contain a set of ```param``` nodes. Each ```param``` node may contain the following attributes:

- ```name``` (required): the parameter's name.
- ```type``` (required): parameter data type represented as a string, e.g. ```"int"```. If ```type="enumerated"```, you can define a set of ```option``` nodes inside ```param``` node specifying the available options for the param. Each ```option``` may contain the following attributes:
  - ```value``` (required): the parameter's value.
  - ```description``` (required): description of the value for the parameter.
- ```required``` (required): indicates if the parameter is required (```required="Y"```) for the method.
- ```value``` (optional): indicates the default value for the parameter.
 

You can define error codes returned by the method on the ```errors``` node. This node may contains ```error``` nodes defining the error code. This error code has to be defined on ```errors.xml``` file (see documentation ```errors.xml```).

```example``` and ```information``` nodes contains a ```CDATA``` with the info to show, e.g. a static JSON sample. This is useful when someone doesn't have the information or ability to test the method, the user can have an idea about the response data.

```information``` nodes contains a ```CDATA``` with some other information about the method. Here it is possible to use basic HTML fromatting to make the information looks clear for users.

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
