<?php
/**
 * @link https://github.com/Vintage-web-production/yii2-i18n
 * @copyright Copyright (c) 2017 Vintage Web Production
 * @license BSD 3-Clause License
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;
use vintage\i18n\Module;

/**
 * @var \yii\web\View $this
 * @var \vintage\i18n\models\SourceMessage $model
 */

$this->title = Module::t('Editing') . ': ' . $model->message;
echo Breadcrumbs::widget(['links' => [
    ['label' => Module::t('Translations'), 'url' => ['index']],
    ['label' => $this->title]
]]);
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= Module::t('Editing') ?></h3>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <?php foreach ($model->messages as $language => $message) : ?>
                <?= $form->field($model->messages[$language], '[' . $language . ']translation', ['options' => ['class' => 'form-group col-sm-6']])->textarea()->label($language) ?>
            <?php endforeach; ?>
        </div>
        <div class="form-group">
            <?= Html::submitButton(
                $model->getIsNewRecord() ? Module::t('Create') : Module::t('Save'),
                ['class' => $model->getIsNewRecord() ? 'btn btn-success' : 'btn btn-primary']
            ) ?>
        </div>
        <?php $form::end(); ?>
    </div>
</div>
