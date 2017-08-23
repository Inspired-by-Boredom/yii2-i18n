<?php
/**
 * @link https://github.com/Vintage-web-production/yii2-i18n
 * @copyright Copyright (c) 2017 Vintage Web Production
 * @license BSD 3-Clause License
 */

namespace vintage\i18n\models;

use Yii;
use yii\caching\Cache;
use yii\db\ActiveRecord;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\i18n\DbMessageSource;
use vintage\i18n\Module;
use vintage\i18n\models\query\SourceMessageQuery;

/**
 * SourceMessage model class
 *
 * @property int $id
 * @property string $category
 * @property string $message
 *
 * @author Aleksandr Zelenin <aleksandr@zelenin.me>
 * @since 1.0
 */
class SourceMessage extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function getDb()
    {
        return Yii::$app->get(Yii::$app->getI18n()->db);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        $i18n = Yii::$app->getI18n();
        if (!isset($i18n->sourceMessageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }

        return $i18n->sourceMessageTable;
    }

    /**
     * @inheritdoc
     * @return SourceMessageQuery the newly created [[SourceMessageQuery]] instance.
     */
    public static function find()
    {
        return new SourceMessageQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['message', 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $firstLang = Yii::$app->getI18n()->languages[0];

        return [
            'id' => Module::t('ID'),
            'category' => Module::t('Category'),
            'message' => Module::t('Message'),
            'status' => Module::t('Status'),
            'translation' => Module::t('Translation') . '[' . $firstLang . ']',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['id' => 'id'])
            ->indexBy('language');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessage()
    {
        $firstLang = Yii::$app->getI18n()->languages[0];

        return $this->hasOne(Message::className(), ['id' => 'id'])
            ->where(['language' => $firstLang])->indexBy('language');
    }

    /**
     * @return array|SourceMessage[]
     */
    public static function getCategories()
    {
        return SourceMessage::find()->select('category')->distinct('category')->asArray()->all();
    }

    /**
     * Init messages
     */
    public function initMessages()
    {
        $messages = [];
        foreach (Yii::$app->getI18n()->languages as $language) {
            if (!isset($this->messages[$language])) {
                $message = new Message;
                $message->language = $language;
                $messages[$language] = $message;
            } else {
                $messages[$language] = $this->messages[$language];
            }
        }
        $this->populateRelation('messages', $messages);
    }

    /**
     * Save messages
     */
    public function saveMessages()
    {
        /* @var \vintage\i18n\components\I18N $i18n */
        $i18n = Yii::$app->getI18n();
        /* @var Cache $cache */
        $cache = $i18n->enableCaching ? Instance::ensure($i18n->cache, Cache::className()) : null;

        /* @var Message $message */
        foreach ($this->messages as $message) {
            $this->link('messages', $message);
            $message->save();

            if ($i18n->enableCaching) {
                $key = [
                    DbMessageSource::className(),
                    $this->category,
                    $message->language,
                ];
                $cache->delete($key);
            }
        }
    }

    /**
     * Check is message translated
     *
     * @return bool
     */
    public function isTranslated()
    {
        foreach ($this->messages as $message) {
            if (!$message->translation) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return null|string
     */
    public function getDefaultLangTranslation()
    {
        return $this->getMessage()->exists()
            ? $this->getMessage()->one()->translation
            : null;
    }
}
