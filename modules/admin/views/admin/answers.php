<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Оксфордский тест личности | ' . $user->username . ' ' .
        $user->sirname . ' | Результаты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-sm-3"><?=
    Html::a('Обратно', Url::toRoute('index'),
            [
                'class' => 'btn btn-default btn-block'
            ]
    );
    ?></div>
<?php if (isset($user->last_question) && ($user->last_question == 200)) { ?>
    <div class="col-sm-3"><?=
        Html::button('Показать результат',
                [
                    'id' => 'results',
                    'class' => 'btn btn-default btn-block'
                ]
        );
        ?></div>
<?php } ?>
<iframe style="display:none;"></iframe>
<table class="table table-sm table-hover">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Вопрос</th>
            <th scope="col">Ответ</th>
            <th scope="col">Дата</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($answersData as $answerData) { ?>
            <tr class="clickable">
                <th scope="row"><?= $answerData['id_question'] ?></th>
                <td><?= $answerData['question'] ?></td>
                <td><?= $answerData['answer'] ?></td>
                <td><?= $answerData['date'] ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php
if (isset($user->last_question) && ($user->last_question == 200)) {
    $url = Url::toRoute('result');
    $js = <<<JS
        $('#results').on('click', function(){
            let test = new Array();
            $.ajax({
                url: '{$url}',
                data: {id: '$user->id'},
                type: 'POST',
                success: function(response) {
                    $(this).hide();
                    $('iframe').attr('src', response);
                    $('iframe').show();
                }
            });
    });
JS;
    $this->registerJs($js);
}
