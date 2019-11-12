<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Оксфордский тест личности | Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>

<table class="table table-sm table-hover">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Имя</th>
            <th scope="col">Фамилия</th>
            <th scope="col">Возраст</th>
            <th scope="col">Выполнение теста</th>
            <th scope="col">Дата окончания теста</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) { ?>
            <tr class="clickable">
                <th scope="row"><?= $user['id'] ?></th>
                <td><?= $user['username'] ?></td>
                <td><?= $user['surname'] ?></td>
                <td><?= $user['age'] ?></td>
                <td><?= $user['last_question'] / 2 . '%' ?></td>
                <td><?= (isset($user['date'])) ? date('d F Y', strtotime($user['date'])) . ' года' : '&nbsp;' ?></td>
                <td><?=
                    Html::a(
                            'Показать ответы',
                            Url::toRoute(['answers', 'id' => $user['id']]),
                            [
                                'class' => 'btn btn-default btn-block'
                            ]
                    );
                    ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
