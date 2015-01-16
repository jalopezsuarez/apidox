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
