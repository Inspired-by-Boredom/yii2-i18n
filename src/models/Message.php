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
use vintage\i18n\Module;

/**
 * Message model class
 *
 * @property int $id
 * @property string $language
 * @property string $translation
 *
 * @author Aleksandr Zelenin <aleksandr@zelenin.me>
 * @since 1.0
 */
class Message extends ActiveRecord
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
        if (!isset($i18n->messageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }
        return $i18n->messageTable;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['language', 'required'],
            ['language', 'string', 'max' => 16],
            ['translation', 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('ID'),
            'language' => Module::t('Language'),
            'translation' => Module::t('Translation')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceMessage()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'id']);
    }

    /**
     * Check is model translated
     *
     * @param int $modelId
     * @return bool
     */
    public static function isModelFullyTranslated($modelId)
    {
        return static::find()
                ->where(['id' => $modelId])
                ->andWhere([
                    'AND',
                    ['is not', 'translation', null],
                    ['<>', 'translation', ''],
                ])
                ->count() == count(Yii::$app->getI18n()->languages);
    }
}
