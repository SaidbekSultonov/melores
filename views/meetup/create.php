<?php

use yii\helpers\Html;

if (isset($_GET) && !empty($_GET) && $_GET['type'] == 7) {
    $this->title = 'Ishchilar savol yaratish';
}elseif(isset($_GET) && !empty($_GET) && $_GET['type'] == 4) {
    $this->title = 'Sotuvchilar savol yaratish';
}
$this->params['breadcrumbs'][] = ['label' => 'Quizzes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__rendered li{
        color: #222222 !important;
    }
</style>
<div class="quiz-create">

    <?php 
    if(Yii::$app->session->hasFlash('error')){
        Yii::$app->session->getFlash('error');
    }
     ?>


    <?php 
    if (isset($select_list) && !empty($select_list)) {
        echo $this->render('create_form', [
            'question' => $question,
            'users' => $users,
            'select_list' => $select_list,
        ]);
    }else {
        echo $this->render('create_form', [
            'question' => $question,
            'users' => $users
        ]);
    }
    ?>

</div>
