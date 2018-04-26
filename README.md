Yii2 Publication Module
=======================
A module for publications

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist dmstr/yii2-publication-module "*"
```

or add

```
"dmstr/yii2-publication-module": "*"

```

to the require section and 

```
{
    "type": "vcs",
    "url": "https://git.hrzg.de/dmstr/yii2-publication-module.git"
}
```

to the repositories section of your `composer.json` file.



Configuration
-------------

Once the extension is installed, simply use it in your code by adding the two module to the module section of your config

```php
'modules' => [
    'publication' => [
        'class' => dmstr\modules\publication\Module::class
    ]
]
```

Run migrations from `@dmstr/modules/publication/migrations`.
