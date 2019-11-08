<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Оксфордский тест личности';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="OxfordTest-default-index">
    <?php $form = ActiveForm::begin() ?>
    <?= $form->field($userModel, 'username')//->label('Имя') ?>
    <?= $form->field($userModel, 'sirname')//->label('Фамилия') ?>
    <?= $form->field($userModel, 'age')->input('number')//->label('Возраст') ?>
    <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    <?php ActiveForm::end() ?>
</div>
