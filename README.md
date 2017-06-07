# Handler for PHP monolog
## Установка
```bash
composer require rml/logger
```
## Для чего это
Позволяет создавать древовидную структуру логов, вида

![Imgur](http://i.imgur.com/VTl0ZvR.jpg)
## Как использовать
```php
<?php

namespace App\Http\Controllers;

use Monolog\Logger;
use Rml\Logger\RmlHandler;

class TestController extends Controller
{
    public function index(){
        $log = new Logger("local");
        $log->pushHandler(
            new RmlHandler("path/to/folder")
        );
        $log->info("Hello, I'm into the path/to/folder/{Y.m.d}/INFO.log file");
    }
}
```

## Как использовать с Laravel 5+
В файле bootstrap/app.php перед
```php
return $app;
```
прописать
```php
$app->configureMonologUsing(function ($monolog) {
    $monolog->pushHandler(
        $handler = new \Rml\Logger\RmlHandler(
            storage_path() . "/logs"
        )
    );
    $handler->setFormatter(new \Monolog\Formatter\LineFormatter(null, null, true, true));
});
```