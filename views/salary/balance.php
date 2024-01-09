<?php
    use app\models\Users;
    use app\models\SalaryCategory;
?>
<?php
    if (isset($event) and !empty($event)){
        $i = 1;
        foreach ($event as $key => $value) {
            echo "<tr>";
            echo "<td>".$i."</td>";
            $selectUsers = Users::find()->where(['id' => $value['user_id']])->one();
            if (isset($selectUsers) and !empty($selectUsers)){
                echo "<td>".$selectUsers->second_name."</td>";
            }
            $selectUserSecond = Users::find()->where(['chat_id' => $value['receiver']])->one();
            if (isset($selectUserSecond) and !empty($selectUserSecond)){
                echo "<td>".$selectUserSecond->second_name."</td>";
            }
            echo "<td>".$value['quantity']."</td>";
            $selectCategory = SalaryCategory::find()->where(['id' => $value['category_id']])->one();
            if (isset($selectCategory) and !empty($selectCategory)){
                echo "<td>".$selectCategory->title."</td>";
            }
            echo "<td>".date('Y-m-d H:i', strtotime($value['date']))."</td>";
            echo "</tr>";
            $i++;
        }
    } else {
        echo "<tr>";
        echo  "<td class='text-center' colspan='6'>Ma'lumot yo'q</td>";
        echo "</tr>";
    }
?><?php
