Internalization module for Yii2 Framework
=========================================

I18N module makes the translation of your application so simple.

Based on [Zelenin](https://github.com/zelenin/yii2-i18n-module) Yii2 I18N module.

Installation
------------
#### Install package
Run command
```
composer require greeflas/yii2-i18n
```
or add
```json
"greeflas/yii2-i18n": "dev-master"
```
to the require section of your composer.json.

Configuration
-------------
1) Configure I18N component in common part of your application
```php
'components' => [
    'i18n' => [
        'class' => greeflas\i18n\components\I18N::className(),
        'languages' => ['ru-RU', 'de-DE', 'it-IT'],
    ],
    // ...
],
```

2) Configure module in backend part of your application

```php
'modules' => [
	'i18n' => greeflas\i18n\Module::className(),
	// ...
],
```


Usage
-----
Run command
```
./yii migrate --migrationPath=@greeflas/i18n/migrations
```

Go to `http://backend.yourdomain.com/translations` for translating of messages

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
./yii i18n/export @greeflas/i18n/messages greeflas/i18n
```
where `@greeflas/i18n/messages` is path to app translations and `greeflas/i18n` is translations category in DB

### Using `yii` category with database
Import translations from PHP files
```
./yii i18n/import @yii/messages
```

Configure I18N component
```php
'i18n' => [
    'class'=> greeflas\i18n\components\I18N::className(),
    'languages' => ['ru-RU', 'de-DE', 'it-IT'],
    'translations' => [
        'yii' => [
            'class' => yii\i18n\DbMessageSource::className()
        ]
    ]
],
```

Other
-----

Component uses yii\i18n\MissingTranslationEvent for auto-add of missing translations to database.

Read more about I18N in [official guide](https://github.com/yiisoft/yii2/blob/master/docs/guide/tutorial-i18n.md)
