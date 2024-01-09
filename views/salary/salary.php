<?php
    use app\models\Users;
use app\models\SalaryCategory;
?>

<?php
    if (isset($salary) and !empty($salary)){
        $i = 1;
        foreach ($salary as $key => $value) {
            echo "<tr>";
            echo "<td>".$i."</td>";
            $selectUser = Users::find()->where(['id' => $value['user_id']])->one();
            if (isset($selectUser) and !empty($selectUser)){
                echo "<td>".$selectUser->second_name."</td>";
            }
            $selectCategory = SalaryCategory::find()->where(['id' => $value['category_id']])->one();
            if (isset($selectCategory) and !empty($selectCategory)){
                echo "<td>".$selectCategory->title."</td>";
            }
            echo "<td>".$value['price']."</td>";
            echo "<td>".base64_decode($value['comment'])."</td>";
            echo "<td>".date('Y-m-d', strtotime($value['date']))."</td>";
            echo "<td><a href='/index.php/salary/salarydelete?id=".$value['id']."' data-confirm='O`chirish istaysizmi ?' data-method='post'><i class ='fa fa-trash'></i></a></td>";
            echo "</tr>";
            $i++;
        }
    } else {
        echo "<tr>";
        echo  "<td class='text-center' colspan='6'>Ma'lumot yo'q</td>";
        echo "</tr>";
    }
?>
