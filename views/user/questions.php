<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Вопросы';
$this->params['breadcrumbs'][] = $this->title;

if (!isset($questions['id'])) {
    $questions['id'] = 201;
}
?>

<div class="jumbotron">
    <div class="row">
        <div class="col-sm-4">
            <?php
            $show = '';
            if (($questions['id'] <= 1) || ($questions['id'] > 200)) {
                $show = 'display:none';
            }
            echo Html::button('Предыдущий вопрос',
                    [
                        'id' => 'previous',
                        'class' => 'btn btn-default btn-block',
                        'style' => $show
            ]);
            ?>
        </div>
        <div class="col-sm-4">&nbsp;</div>
        <div class="col-sm-4">
            <?php
            $show = '';
            if (($new == 1) || (($questions['id'] < 1) && ($questions['id'] > 200))) {
                $show = 'display:none';
            }
            echo Html::button('Следующий вопрос',
                    [
                        'id' => 'next',
                        'class' => 'btn btn-default btn-block',
                        'style' => $show
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <p id="question">
<?= $questions['question']; ?>
        </p>
    </div>

<?php if (($questions['id'] >= 1) && ($questions['id'] <= 200)) { ?>
        <div class="row" id="answers">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
    <?= Html::button('Да', ['value' => 'Y', 'class' => 'btn btn-default btn-block']) ?>
            </div>
            <div class="col-sm-4"></div>
        </div>
        <div class="row" id="answers">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
    <?= Html::button('Может быть', ['value' => 'M', 'class' => 'btn btn-default btn-block']) ?>
            </div>
            <div class="col-sm-4"></div>
        </div>
        <div class="row" id="answers">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
    <?= Html::button('Нет', ['value' => 'N', 'class' => 'btn btn-default btn-block']) ?>
            </div>
            <div class="col-sm-4"></div>
        </div>
        <div class="row">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: <?= ($questions['id'] - 1) / 2 ?>%" aria-valuenow="<?= $questions['id'] ?>" aria-valuemin="0" aria-valuemax="200"></div>
            </div>
        </div>    
<?php } ?>
</div>

<?php
if ($questions['id'] <= 200) {
    $url = Url::toRoute('question');
    $js = <<<JS
        $('.row').css('padding-top', '15px');
        var id = {$questions['id']};
        var wait = false;
        $('#answers .btn').on('click', function(){
            if (!wait) {
                let answer = {
                    id_question: id,
                    answer: $(this).attr('value')
                }
                let query = {
                    answer: answer
                }
                sendQuery(query);
            }
        });
        $('#previous').on('click', function(){
            if (!wait) {
                let query = {
                    id_question: id - 1
                }
                sendQuery(query);
            }
        });
        $('#next').on('click', function(){
            if (!wait) {
                let query = {
                    id_question: id + 1
                }
                sendQuery(query);
            }
        });
        function sendQuery(query) {
            wait = true;
            $.ajax({
                url: '{$url}',
                data: {data: JSON.stringify(query)},
                type: 'POST',
                success: function(res) {
                    res = JSON.parse(res);
                    id = res['id'];
                    $('#question').text(res['question']);
                    if (res['new'] == 1) {
                        $('.progress-bar').width((id - 1) / 2 + '%');
                        $('#next').hide();
                    } else {
                        $('#next').show();
                    }
                    if (id <= 1) {
                        $('#previous').hide();
                    } else {
                        $('#previous').show(); 
                    }
                    if (id == 201) {
                        $('.btn').hide();
                        $('.progress').hide();
                    }
                    wait = false;
                }
            });
        }
    JS;

    $this->registerJs($js);
}
?>
