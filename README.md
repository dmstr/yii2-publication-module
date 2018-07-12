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



Usage
-----

Create widget-templates `/widgets/crud/widget-template/index` for teaser 

```
{
    "title": "News Teaser",
    "type": "object",
    "properties": {
        "teaser": {
            "type": "string",
            "title": "Teaser",
            "default": "subline"
        }
    }
}
```

and content.

```
{
    "title": "News Content",
    "type": "object",
    "properties": {
        "content": {
            "type": "string",
            "title": "Content",
            "default": "Content"
        }
    }
}
```

Create a category, ie. `News` and assign templates to it.

Create an item.

---

`publication/default/index?categoryId=1`


Use crud data in widget

Add crud model to view component twig globals:

```php
'view' => [
    'renderers' => [
        'twig' => [
            'globals' => [
                'Bike' => ['class' => \project\modules\crud\models\Bike::class],
            ]
        ]
    ]
]
```

Add action for crud model to JsonSchemaController


in actions() method

#####For CRUD Models
```php
...
$actions['bikes'] = [
    'class' => SchemaAction::class,
    'modelClass' => Bike::class,
    'primaryKey' => 'id',
    'labelAttribute' => 'name'
];
...

```

#####For Setting
```php
...
$actions['categories'] = [
    'class' => SettingAction::class,
    'key' => 'categories'
];
...

```


Widget Template

JSON:
```json
{
    "title": "Bike",
    "type": "object",
    "required": [
        "bikeId"
    ],
    "properties": {
        "bikeId": {
            "$ref": "http://localhost:21548/en/publication/json-schema/bikes"
        }
    }
}
```

TWIG:

```twig
{% set bike = Bike.findOne(bikeId) %}

{% if bike is not null %}
<div>{{ bike.name }}</div>
{% endif %}
```