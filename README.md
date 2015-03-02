# :package_name

[![Latest Version](https://img.shields.io/github/release/buagern/slim-json.svg?style=flat-square)](https://github.com/buagern/slim-json/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/buagern/slim-json.svg?style=flat-square)](https://packagist.org/packages/buagern/slim-json)

This is an extension to the [Slim Framework](https://github.com/codeguy/Slim) to implement JSON output.

## Install

Via Composer

``` bash
$ composer require buagern/slim-json
```

or adding this line to your composer.json file

``` bash
"buagern/slim-json": "0.1.*"
```

## Usage

To include the middleware and view you just have to load them using the default _Slim_ way.
Read more about Slim Here (https://github.com/codeguy/Slim#getting-started)

``` php
require 'vendor/autoload.php';

$app = new \Slim\Slim();

$app->view(new \Buagern\SlimJson\View);
$app->add(new \Buagern\SlimJson\Middleware);
```

### Using Routing Middleware method

``` php
function jsonResponse()
{
    $app = \Slim\Slim::getInstance();
    $app->view(new \Buagern\SlimJson\View);
    $app->add(new \Buagern\SlimJson\Middleware);
}


$app->get('/', function () use ($app)
{
    // normal view render
    
    return $app->render('view.php');
});

$app->get('/json', 'jsonResponse', function () use ($app)
{
    // this request will return json response

    return $app->render(200, [
        'message' => 'JSON response',
    ]);
});
```

## Security

If you discover any security related issues, please email buagern@buataitom.com instead of using the issue tracker.

## Credits

- [Settawat Jamsai](https://github.com/buagern)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
