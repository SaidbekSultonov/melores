<?php
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\ArrayHelper;
    use yii\widgets\ActiveForm;
?>
    <?php
        $i = 1;
        if (!empty($event) and isset($event)){
            foreach($event as $key => $value) {
                echo "<tr>";
                echo  "<td>".$i."</td>";
                $selectSecondName = \app\models\Users::find()->where(['id' => $value['user_id']])->one();
                if (isset($selectSecondName) and !empty($selectSecondName)){
                    echo  "<td>". $selectSecondName->second_name ."</td>";
                } else {
                    echo "<td><span class='label label-default'>O`chirilgan	</span></td>";
                }
                echo  "<td>". $value['created_date'] ."</td>";
                echo  "<td>". $value['title'] ."</td>";
                echo  "<td>". $value['event'] ."</td>";
                echo  "<td>". $value['section_title'] ."</td>";
                echo "</tr>";
                $i++;
            }
        } else {
            echo "<tr>";
            echo  "<td class='text-center' colspan='6'>Ma'lumot yo'q</td>";
            echo "</tr>";
        }
    ?>
