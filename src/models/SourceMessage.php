<?php
/**
 * @link https://github.com/Vintage-web-production/yii2-i18n
 * @copyright Copyright (c) 2017 Vintage Web Production
 * @license BSD 3-Clause License
 */

namespace vintage\i18n\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use vintage\i18n\models\query\SourceMessageQuery;
use vintage\i18n\Module;

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
            'category' => 'Категория',
            'message' => 'Сообщение',
            'status' => 'Статус',
            'translation' => 'Перевод['.$firstLang.']',
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

        return $this->hasOne(Message::className(), ['id' => 'id'])->where(['language' => $firstLang])->indexBy('language');
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
        /** @var Message $message */
        foreach ($this->messages as $message) {
            $this->link('messages', $message);
            $message->save();
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
