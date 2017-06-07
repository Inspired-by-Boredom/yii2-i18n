<?php
/**
 * @link https://github.com/Vintage-web-production/yii2-i18n
 * @copyright Copyright (c) 2017 Vintage Web Production
 * @license BSD 3-Clause License
 */

namespace vintage\i18n\models\search;

use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use vintage\i18n\models\Message;
use vintage\i18n\models\SourceMessage;

/**
 * Model for search in [[SourceMessage]] records
 *
 * @author Aleksandr Zelenin <aleksandr@zelenin.me>
 * @since 1.0
 */
class SourceMessageSearch extends SourceMessage
{
    const STATUS_TRANSLATED = 1;
    const STATUS_NOT_TRANSLATED = 2;

    /**
     * @var int
     */
    public $status;
    /**
     * @var string
     */
    public $translation;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['category', 'safe'],
            ['message', 'safe'],
            ['status', 'safe'],
            ['translation', 'safe'],
        ];
    }

    /**
     * Search models
     *
     * @param array|null $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SourceMessage::find()->joinWith('message');
        $dataProvider = new ActiveDataProvider(['query' => $query]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->status == static::STATUS_TRANSLATED) {
            $query->translated();
        }
        if ($this->status == static::STATUS_NOT_TRANSLATED) {
            $query->notTranslated();
        }

        $query
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', Message::tableName().'.translation', $this->translation]);
        return $dataProvider;
    }

    /**
     * Returns status
     *
     * @param null $id
     * @return array|mixed
     */
    public static function getStatus($id = null)
    {
        $statuses = [
            self::STATUS_TRANSLATED => 'Переведено',
            self::STATUS_NOT_TRANSLATED => 'Не переведено',
        ];
        if ($id !== null) {
            return ArrayHelper::getValue($statuses, $id, null);
        }
        return $statuses;
    }
}
