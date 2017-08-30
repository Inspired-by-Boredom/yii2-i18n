<?php
/**
 * @link https://github.com/Vintage-web-production/yii2-i18n
 * @copyright Copyright (c) 2017 Vintage Web Production
 * @license BSD 3-Clause License
 */

namespace vintage\i18n\components;

use yii\base\InvalidConfigException;
use yii\i18n\DbMessageSource;

/**
 * I18N extended component.
 *
 * @author Aleksandr Zelenin <aleksandr@zelenin.me>
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 * @since 1.0
 */
class I18N extends \yii\i18n\I18N
{
    /**
     * @var string
     * @see DbMessageSource::$sourceMessageTable
     */
    public $sourceMessageTable = '{{%source_message}}';
    /**
     * @var string
     * @see DbMessageSource::$messageTable
     */
    public $messageTable = '{{%message}}';
    /**
     * @var \yii\db\Connection|array|string
     * @see DbMessageSource::$db
     */
    public $db = 'db';
    /**
     * @var \yii\caching\Cache|string|array
     * @see DbMessageSource::$cache
     * @since 1.2
     */
    public $cache = 'cache';
    /**
     * @var int
     * @see DbMessageSource::$cachingDuration
     * @since 1.2
     */
    public $cachingDuration = 0;
    /**
     * @var bool
     * @see DbMessageSource::$enableCaching
     * @since 1.2
     */
    public $enableCaching = false;
    /**
     * @var bool
     * @see \yii\i18n\MessageSource::$forceTranslation
     * @since 1.3
     */
    public $forceTranslation = true;
    /**
     * @var array Languages list.
     */
    public $languages;
    /**
     * @var array Handler for missing translations.
     * @example
     * ```php
     * ['handlerClassName', 'handlerMethodName']
     * ```
     */
    public $missingTranslationHandler = ['vintage\i18n\Module', 'missingTranslation'];
    /**
     * @var array Message categories which will not be automatically added on MissingTranslationEvent.
     */
    public $excludedCategories = [];


    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->languages) {
            throw new InvalidConfigException('Languages list cannot be blank');
        }

        $sourceMessagesConfig = [
            'class' => DbMessageSource::className(),
            'db' => $this->db,
            'cache' => $this->cache,
            'cachingDuration' => $this->cachingDuration,
            'enableCaching' => $this->enableCaching,
            'sourceMessageTable' => $this->sourceMessageTable,
            'messageTable' => $this->messageTable,
            'forceTranslation' => $this->forceTranslation,
            'on missingTranslation' => $this->missingTranslationHandler,
        ];

        if (!isset($this->translations['*'])) {
            $this->translations['*'] = $sourceMessagesConfig;
        }
        if (!isset($this->translations['app']) && !isset($this->translations['app*'])) {
            $this->translations['app'] = $sourceMessagesConfig;
        }

        parent::init();
    }
}
