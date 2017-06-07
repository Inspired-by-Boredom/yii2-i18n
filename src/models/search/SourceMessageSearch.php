<?php

namespace greeflas\yii\modules\I18n\models\search;

use greeflas\yii\modules\I18n\models\Message;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use greeflas\yii\modules\I18n\models\SourceMessage;

class SourceMessageSearch extends SourceMessage
{
    const STATUS_TRANSLATED = 1;
    const STATUS_NOT_TRANSLATED = 2;

    public $status;
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
