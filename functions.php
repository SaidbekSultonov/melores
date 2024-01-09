<?php 

namespace app\controllers;
use app\models\AuthAssignment;
use app\models\Events;
use Yii;

	function pre($arr){
		echo '<pre>'.print_r($arr, true).'</pre>';
		die();
	}

    function eventUser($user_id, $created_date, $title, $event, $section_title){

        $model = new Events();
        $model->user_id = $user_id;
        $model->created_date = $created_date;
        $model->title = $title;
        $model->event = $event;
        $model->section_title = $section_title;
        $model->save();

    }

    function getRole()
    {
        $role = AuthAssignment::find()->where(['user_id' => Yii::$app->user->id])->one();
        return $role;
    }
?>