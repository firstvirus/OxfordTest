<?php

namespace app\modules\OxfordTest\controllers;

use yii;
use yii\helpers\Url;
use yii\web\Controller;
use app\modules\OxfordTest\models\Answers;
use app\modules\OxfordTest\models\Questions;
use app\modules\OxfordTest\models\UserForm;
use app\modules\OxfordTest\models\Users;

/**
 * Описание UserController
 * Это основной контроллер модуля OxfordTest.
 * Контроллер реализует методы регистрации и авторизации пользователей,
 * вывода вопросов и сбора ответов.
 * 
 * @package OxfordTest
 * @version 0.1.63
 *
 * @author virus
 */
class UserController extends Controller {

    /**
     * public function actionIndex()
     * Выводит форму аутентификации пользователя, проверяет на существующего
     * пользователя, автоматически регистрирует пользователя
     * 
     * @return string
     */
    public function actionIndex() {
        if (Yii::$app->user->isGuest) {
            // Проверка на гостя
            $userModel = new UserForm();
            if ($userModel->load(Yii::$app->request->post())) {
                if ($userModel->validate()) {
                    // Ищем пользователя
                    $response = UserForm::find()->where([
                                'username' => $userModel->username,
                                'sirname' => $userModel->sirname,
                                'age' => $userModel->age,
                            ])->one();
                    // Если не находим, то регистрируем
                    if ($response === null) {
                        $userModel->save();
                        $role = Yii::$app->authManager - getRole('user');
                        Yii::$app->authManager->assign($role, $userModel->id);
                    } else {
                        // Если находим, то переводим на аутентификацию
                        $userModel->id = $response->id;
                    }
                    Yii::$app->user->login(
                            $userModel->login($userModel->id),
                            60 * 60 * 24 * 365
                    );
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка');
                }
            } else {
                // Вывод формы аутентификации
                return $this->render('main', compact('userModel'));
            }
        }
        // Админа шлем в админку
        if (Users::getRole(Yii::$app->user->id) == 'admin') {
            Yii::$app->response->redirect(Url::toRoute(['/OxfordTest/admin']));
        } else {
            // Остальных шлем отвечать на вопросы
            Yii::$app->response->redirect(Url::toRoute([
                        'questions',
                        'id' => Yii::$app->user->identity->last_question + 1,
            ]));
        }
    }

    /**
     * public function actionQuestions($id)
     * Метод реализует вывод первого вопроса из списка, на который не дан ответ.
     * Если все ответы даны, предупреждает об этом пользователя.
     * 
     * @return string 
     */

    public function actionQuestions($id = 1) {
        $user = UserForm::findOne(Yii::$app->user->identity->id);
        // Выясняем на какой вопрос последним был дан ответ
        if ($user->last_question < $id) {
            $id = $user->last_question + 1;
        }
        // Если на этот вопрос ответа нет, помечаем его новым
        if ($user->last_question + 1 == $id) {
            $new = 1;
            // ... иначе старым
        } else {
            $new = 0;
        }
        // Если вопросы кончились, пишем об этом, иначе находим текст вопроса
        if ($id > 200 || $user->last_question == 200) {
            $questions['question'] = 'Вы ответили на все вопросы. За результатом обратитесь к администратору сайта.';
        } else {
            $questions = Questions::findOne($id);
        }
        // И все это добро выводим
        return $this->render('questions', compact('questions', 'new'));
    }

    /**
     * public function actionQuestion() Ajax method only.
     * Метод для выборки вопросов по порядку
     * 
     * @return string
     */

    public function actionQuestion() {
        if (Yii::$app->request->isAjax) {
            // Находим пользователя и декодируем данные из $_POST
            $user = UserForm::findOne(Yii::$app->user->identity->id);
            $data = json_decode(Yii::$app->request->post('data'), true);
            // Если пришел ответ на вопрос проверяем, был ли ответ
            // на данных вопрос
            if (isset($data['answer'])) {
                $data['answer']['id_client'] = Yii::$app->user->identity->id;
                $modelQuestion = Answers::find()->
                                where([
                                    'id_client' => $data['answer']['id_client'],
                                    'id_question' => $data['answer']['id_question']
                                ])->limit(1)->one();
                // Если ответа не нашли, значит вопрос новый, сохраняем ответ
                if (!isset($modelQuestion)) {
                    $modelQuestion = new Answers();
                    $modelQuestion->load($data);
                    $user->last_question++;
                    $user->save();
                }
                if ($modelQuestion->validate()) {
                    $modelQuestion->save();
                    $id_question = $modelQuestion['id_question'] + 1;
                }
                // Если получили не ответ, а запрос другого вопроса,
                // значит у кого-то чешутся руки и он листает вопросы назад
            } elseif (isset($data['id_question']) && ($data['id_question'] > 0)) {
                $id_question = $data['id_question'];
            } else {
                return 'Ошибка данных';
            }
            // Собираем массив данных для фронт-енда, незабываем про
            // проверку на новизну вопроса.
            $new = 0;
            unset($data);
            $data['id'] = $id_question;
            if (($user->last_question + 1) == $data['id']) {
                $new = 1;
            } elseif (($user->last_question + 1) < $data['id']) {
                $data['id'] = $user->last_question + 1;
            }
            // Если пользователь не ответил на последний вопрос, то выводим
            // ему эти самые вопросы. По одному.
            if ($data['id'] <= 200) {
                $questions = Questions::findOne($data['id']);
                $data['question'] = $questions['question'];
                $data['new'] = $new;
            } else {
                // Иначе сообщаем, что вопросы кончились.
                $data['id'] == 201;
                $data['question'] = 'Вы ответили на все вопросы. За результатом обратитесь к администратору сайта.';
                $user->date = date('Y-m-d H:i:s');
                $user->save();
            }
            // И все данные скидываем ява-скрипту.
            return json_encode($data);
        } else {
            return 'Ошибка связи.';
        }
    }

}
