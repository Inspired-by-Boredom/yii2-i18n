<?php

namespace greeflas\yii\modules\I18n;

use Yii;
use yii\i18n\MissingTranslationEvent;
use greeflas\yii\modules\I18n\models\SourceMessage;

class Module extends \yii\base\Module
{
    public $pageSize = 50;

    public static function t($message, $params = [], $language = null)
    {
        return Yii::t('zelenin/modules/i18n', $message, $params, $language);
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
