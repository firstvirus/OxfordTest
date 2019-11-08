<?php

namespace app\modules\OxfordTest\modules\admin\controllers;

use yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\modules\OxfordTest\models\Answers;
use app\modules\OxfordTest\models\Questions;
use app\modules\OxfordTest\models\Users;

/**
 * Описание AdminController
 * Это основной контроллер субмодуля admin модуля OxfordTest.
 * Контроллер реализует методы просмотра списка пользователей,
 * ответы пользователей на вопросы теста и просмотр результата теста.
 * 
 * @package OxfordTest
 * @subpackage admin
 * @version 0.1.63
 *
 * @author virus
 */
class AdminController extends Controller {

    /**
     * public function behaviors()
     * Содержит правила использования контроллера AdminController
     * зарегистрированными пользователями.
     * 
     * @return array Содержит набор правил
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * public function actionIndex()
     * Метод контроллера AdminController. Выводит всех зарегистрированных
     * пользователей ресурса.
     * 
     * @return string
     */
    public function actionIndex() {
        Yii::$app->formatter->locale = 'ru-RU';

        $users = Users::find()->all();
        foreach ($users as $user) {
            $user->date = Yii::$app->formatter->asDate($user->date);
        }
        return $this->render('main', compact('users'));
    }

    /**
     * public function actionAnswers($id = 1)
     * Метод контроллера, выводящий все ответы на вопросы теста определенного
     * зарегистрированного пользователя.
     * 
     * @return string
     */
    public function actionAnswers($id = 1) {
        Yii::$app->formatter->locale = 'ru-RU';
        // Находим все ответы, принадлежащие пользователю
        $ids = array();
        $answers = Answers::find()->where(['id_client' => $id])->
                        orderBy(['id_question' => SORT_ASC])->all();
        foreach ($answers as $answer) {
            $ids[] = $answer['id_question'];
        }
        // Находим строки вопросов, на которые даны ответы
        $questions = Questions::find($ids)->asArray()->where(['id' => $ids])->
                all();
        unset($ids);
        $answersData = array();
        // Расшифровываем ответы в удобочитаемый вид и собираем все данные
        // в едином массиве
        foreach ($answers as $answer) {
            switch ($answer->answer) {
                case 'Y' :
                    $decriptedAnswer = 'Да';
                    break;
                case 'M' :
                    $decriptedAnswer = 'Может быть';
                    break;
                case 'N' :
                    $decriptedAnswer = 'Нет';
                    break;
            }
            array_push($answersData, [
                'id_question' => $answer['id_question'],
                'question' => $questions[$answer['id_question'] - 1]['question'],
                'answer' => $decriptedAnswer,
                'date' => Yii::$app->formatter->asDate($answer['date'])
            ]);
        }
        // Подхватываем Id пользователя и отправляем на рендер
        $user = Users::findIdentity($id);
        return $this->render('answers', compact('answersData', 'user'));
    }

    /**
     * public function actionResult() Ajax access only.
     * Метод собирает все ответы пользователя в единую строку и 
     * отсылает их на дешифровку
     * 
     * @return string Строка запроса на дешифровку
     */
    public function actionResult() {
        if (Yii::$app->request->isAjax) {
            $idUser = Yii::$app->request->post('id');
            $user = Users::findOne($idUser);
            if (!isset($user->answer)) {
                $answers = Answers::find()->asArray()->
                                where(['id_client' => $idUser])->all();
                $answersStr = '';
                foreach ($answers as $answer) {
                    $answersStr .= $answer['answer'];
                }
                $user->answer = $answersStr;
                $user->save();
            } else {
                $answersStr = $user->answer;
            }
            $userDate = strtotime($user->date);
            return 'https://www.oxfordcapacityanalysis.org/update.action' .
                    '?answers=' . $answersStr .
                    '&refresh=' . $userDate;
        } else {
            return 'Ошибка связи';
        }
    }

}
