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
 * I18N extended component
 *
 * @author Aleksandr Zelenin <aleksandr@zelenin.me>
 * @since 1.0
 */
class I18N extends \yii\i18n\I18N
{
    /**
     * @var string
     */
    public $sourceMessageTable = '{{%source_message}}';
    /**
     * @var string
     */
    public $messageTable = '{{%message}}';
    /**
     * @var array
     */
    public $languages;
    /**
     * @var array
     */
    public $missingTranslationHandler = ['Zelenin\yii\modules\I18n\Module', 'missingTranslation'];
    /**
     * Message categories which will not be automatically added on MissingTranslationEvent
     *
     * @var array
     */
    public $excludedCategories = [];
    /**
     * @var string
     */
    public $db = 'db';


    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->languages) {
            throw new InvalidConfigException('You should configure i18n component [language]');
        }

        if (!isset($this->translations['*'])) {
            $this->translations['*'] = [
                'class' => DbMessageSource::className(),
                'db' => $this->db,
                'sourceMessageTable' => $this->sourceMessageTable,
                'messageTable' => $this->messageTable,
                'on missingTranslation' => $this->missingTranslationHandler
            ];
        }
        if (!isset($this->translations['app']) && !isset($this->translations['app*'])) {
            $this->translations['app'] = [
                'class' => DbMessageSource::className(),
                'db' => $this->db,
                'sourceMessageTable' => $this->sourceMessageTable,
                'messageTable' => $this->messageTable,
                'on missingTranslation' => $this->missingTranslationHandler
            ];
        }
        parent::init();
    }
}
