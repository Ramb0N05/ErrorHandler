# Class ErrorHandler - Documentation

## Table of Contents

* [ErrorHandler](#errorhandler)
    * [__construct](#__construct)
    * [setConfig](#setconfig)
    * [throwError](#throwerror)
    * [registerHandler](#registerhandler)

## ErrorHandler

Class ErrorHandler



* Full name: \ramon1611\Libs\ErrorHandler


### __construct

Constructor

```php
ErrorHandler::__construct( array $confArr ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$confArr` | **array** | Array of settings |




---

### setConfig

Sets the config

```php
ErrorHandler::setConfig( array $confArr ): void
```

<pre>pattern of $confArr:
[
     'excludeFiles'      => [ 'fileToExclude1.xyz', 'excludeMe2.php' ],
     'errorStylesheet'   => './css/theCustomErrorStylesheet.css',       # Must be a valid path on HTML-level
     'noticeCaption'     => 'Caption of a notice',
     'warningCaption'    => 'MyWarningMessage',
     'errorCaption'      => 'MyOwnError'
]</pre>


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$confArr` | **array** | Array of settings |




---

### throwError

Handles errors and throws them

```php
ErrorHandler::throwError( integer $errno, string $errstr, string $errfile, integer $errline, array $errcontext ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$errno` | **integer** | The level of the error raised |
| `$errstr` | **string** | Error message |
| `$errfile` | **string** | The filename that the error was raised in |
| `$errline` | **integer** | The line number the error was raised at |
| `$errcontext` | **array** | Array of every variable that existed in the scope the error was triggered in |




---

### registerHandler

Registers the handler in PHP

```php
ErrorHandler::registerHandler(  ): mixed
```





**Return Value:**

Returns the output of set_error_handler() or false if config is not completed



---



--------
> This document was automatically generated from source code comments on 2018-02-02 using [phpDocumentor](http://www.phpdoc.org/) and [cvuorinen/phpdoc-markdown-public](https://github.com/cvuorinen/phpdoc-markdown-public)
