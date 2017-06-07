<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;

/**
 * @var \yii\web\View $this
 * @var \greeflas\yii\modules\I18n\models\SourceMessage $model
 */

$this->title = 'Редактирование' . ': ' . $model->message;
echo Breadcrumbs::widget(['links' => [
    ['label' => 'Переводы', 'url' => ['index']],
    ['label' => $this->title]
]]);
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Редактирование</h3>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <?php foreach ($model->messages as $language => $message) : ?>
                <?= $form->field($model->messages[$language], '[' . $language . ']translation', ['options' => ['class' => 'form-group col-sm-6']])->textarea()->label($language) ?>
            <?php endforeach; ?>
        </div>
        <div class="form-group">
            <?=
            Html::submitButton(
                $model->getIsNewRecord() ? 'Cоздать' : 'Сохранить',
                ['class' => $model->getIsNewRecord() ? 'btn btn-success' : 'btn btn-primary']
            ) ?>
        </div>
        <?php $form::end(); ?>
    </div>
</div>
