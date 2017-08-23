Internalization module for Yii2 Framework
=========================================
Internalization module for automatic adding of translations to database.

Based on [Zelenin](https://github.com/zelenin/yii2-i18n-module) I18N module.


[![Total Downloads](https://poser.pugx.org/vintage/yii2-i18n/downloads)](https://packagist.org/packages/vintage/yii2-i18n)
[![Latest Stable Version](https://poser.pugx.org/vintage/yii2-i18n/v/stable)](https://packagist.org/packages/vintage/yii2-i18n)
[![Latest Unstable Version](https://poser.pugx.org/vintage/yii2-i18n/v/unstable)](https://packagist.org/packages/vintage/yii2-i18n)

Installation
------------
#### Install package
Run command
```
composer require vintage/yii2-i18n
```
or add
```json
"vintage/yii2-i18n": "~1.1"
```
to the require section of your composer.json.

Configuration
-------------
1) Configure I18N component in common part of your application
```php
'components' => [
    'i18n' => [
        'class' => vintage\i18n\components\I18N::className(),
        'languages' => ['ru-RU', 'de-DE', 'it-IT'],
    ],
    // ...
],
```

2) Configure module in backend part of your application

```php
'modules' => [
	'i18n' => vintage\i18n\Module::className(),
	// ...
],
```

3) Applying migrations
```
./yii migrate --migrationPath=@vintage/i18n/migrations
```

Usage
-----
Go to `http://backend.your-app.dev/translations` for translating of messages.

### PHP to database import
If you have an old project with PHP-based i18n you may migrate to DbSource via console.

Run command
```
./yii i18n/import @common/messages
```
where `@common/messages` is path to app translations

### Database to PHP export
Run command
```
./yii i18n/export @vintage/i18n/messages vintage/i18n
```
where `@vintage/i18n/messages` is path to app translations and `vintage/i18n` is translations category in DB.

### Using `yii` category with database
Import translations from PHP files
```
./yii i18n/import @yii/messages
```

Configure I18N component
```php
'components' => [
    'i18n' => [
        'class'=> vintage\i18n\components\I18N::className(),
        'languages' => ['ru-RU', 'de-DE', 'it-IT'],
        'translations' => [
            'yii' => [
                'class' => yii\i18n\DbMessageSource::className()
            ],
        ],
    ],
],
```

### Caching
Cache will be updates automaticly if you updates translations in dashboard.

If you using `\yii\caching\FileCache` your config should be like following
```php
// common/config/main.php
`components` => [
    // ...
    'i18n' => [
        'class'=> vintage\i18n\components\I18N::className(),
        'languages' => ['ru-RU', 'de-DE', 'it-IT'],
        'enableCaching' => true,
        'cache' => 'i18nCache',
    ],
    'i18nCache' => [
        'class' => \yii\caching\FileCache::className(),
        'cachePath' => '@frontend/runtime/cache',
    ],
],
```

Other
-----
Component uses yii\i18n\MissingTranslationEvent for auto-add of missing translations to database.

Read more about I18N in [official guide](https://github.com/yiisoft/yii2/blob/master/docs/guide/tutorial-i18n.md).

Licence
-------
[![License](https://poser.pugx.org/vintage/yii2-i18n/license)](https://packagist.org/packages/vintage/yii2-i18n)

This project is released under the terms of the BSD-3-Clause [license](LICENSE).

Copyright (c) 2017, [Vintage Web Production](https://vintage.com.ua/)
