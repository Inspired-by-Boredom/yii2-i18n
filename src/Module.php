<?php
/**
 * @link https://github.com/Vintage-web-production/yii2-i18n
 * @copyright Copyright (c) 2017 Vintage Web Production
 * @license BSD 3-Clause License
 */

namespace vintage\i18n;

use Yii;
use yii\i18n\MissingTranslationEvent;
use vintage\i18n\models\SourceMessage;

/**
 * I18N module definition class
 *
 * @author Aleksandr Zelenin <aleksandr@zelenin.me>
 * @since 1.0
 */
class Module extends \yii\base\Module
{
    /**
     * @var int
     */
    public $pageSize = 50;


    /**
     * Wrapper for `Yii::t()`
     *
     * @param string $message
     * @param array $params
     * @param null|string $language
     * @return string
     */
    public static function t($message, $params = [], $language = null)
    {
        return Yii::t('app/i18n', $message, $params, $language);
    }

    /**
     * @param MissingTranslationEvent $event
     */
    public static function missingTranslation(MissingTranslationEvent $event)
    {
        $i18n = Yii::$app->getI18n();
        if (isset($i18n->excludedCategories)) {
            $excludeCategories = $i18n->excludedCategories;
        } else {
            $excludeCategories = [];
        }
        $driver = Yii::$app->getDb()->getDriverName();
        $caseInsensitivePrefix = $driver === 'mysql' ? 'binary' : '';

        if (!in_array($event->category, $excludeCategories)) {
            $sourceMessage = SourceMessage::find()
                ->where('category = :category and message = ' . $caseInsensitivePrefix . ' :message', [
                    ':category' => $event->category,
                    ':message' => $event->message
                ])
                ->with('messages')
                ->one();

            if (!$sourceMessage) {
                $sourceMessage = new SourceMessage;
                $sourceMessage->setAttributes([
                    'category' => $event->category,
                    'message' => $event->message
                ], false);
                $sourceMessage->save(false);
            }
            $sourceMessage->initMessages();
            $sourceMessage->saveMessages();
        }
    }
}
