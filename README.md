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
        'class' => dmstr\modules\publication\Module::class,
        'previewItemRole' => null // This describes a rbac role which allowed the user which has grants for this privilege to access the default detail action even if the item is not published
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

### Use variables from content schema in teaser template

```
{{ content.headline }}
{{ content.image }}
```
