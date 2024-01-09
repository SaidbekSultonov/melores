<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $title
 * @property int $branch_id
 * @property int $user_id
 * @property int $client_id
 * @property string $created_date
 * @property string $dead_line
 * @property int $status
 * @property double $feedback_user
 * @property double $feedback_client
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_id', 'user_id', 'client_id', 'pause','status','description','parralel'], 'default', 'value' => null],
            [['branch_id', 'user_id', 'client_id', 'status','chat_id','category_id'], 'integer'],
            [['created_date', 'dead_line'], 'safe'],
            [['feedback_user', 'feedback_client'], 'number'],
            [['title'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'branch_id' => 'Branch ID',
            'user_id' => 'User ID',
            'client_id' => 'Client ID',
            'created_date' => 'Created Date',
            'dead_line' => 'Dead Line',
            'status' => 'Status',
            'feedback_user' => 'Feedback User',
            'feedback_client' => 'Feedback Client',
        ];
    }

    // ClientSalary
    public function getSalary()
    {
        return $this->hasOne(ClientSalary::classname(), ['order_id' => 'id']);
    }

    

    public function getUser()
    {
        return $this->hasOne(Users::classname(), ['id' => 'user_id']);
    }

    public function getClient()
    {
        return $this->hasOne(Clients::classname(), ['id' => 'client_id']);
    }

    public function getCategories()
    {
        $model = OrderCategories::find()
        ->where([
            'order_id' => $this->id
        ])
        ->all();

        return $model;

    }

    public function getBranch()
    {
        return $this->hasOne(Branch::classname(), ['id' => 'branch_id']);
    }

    public function getTeam()
    {
        return $this->hasOne(Team::classname(), ['id' => 'team_id']);
    }

    
    public function getOrder_step()
    {
        return $this->hasMany(OrderStep::classname(), ['order_id' => 'id'])->orderBy(['order_column' => SORT_ASC]);
    }

    public static function Order_step_one($order_id,$section_id)
    {
        $model = OrderStep::find()
        ->where(['order_id' => $order_id, 'section_id' => $section_id])
        ->orderBy(['order_column' => SORT_ASC])
        ->one();
        
        return $model;
    }

    

    public static function Section_orders($order_id,$section)
    {
        $model = SectionOrders::find()
        ->where([
            'order_id' => $order_id,
            'section_id' => $section,
        ])
        ->one();

        if (isset($model)) {
            return $model->exit_date;    
        }
        else{
            return '-';    
        }
        
    }


    public static function Section_orders_enter($order_id,$section)
    {
        $model = SectionOrders::find()
        ->where([
            'order_id' => $order_id,
            'section_id' => $section,
        ])
        ->one();

        if (isset($model)) {
            return $model->enter_date;    
        }
        else{
            return '-';    
        }
        
    }



    public static function order_step($order_id,$section)
    {
        $model = OrderStep::find()
        ->where([
            'order_id' => $order_id,
            'section_id' => $section
        ])
        ->one();

        if (isset($model)) {
            return $model->deadline;    
        }
        else{
            return '-';    
        }
        
    }


    public function getPause()
    {
        $model = PausedOrders::find()
        ->where([
            'order_id' => $this->id,
            'end_date' => '9999-12-31 00:00:00'
        ])
        ->orderBy(['id' => SORT_DESC])
        ->one();

        if (isset($model)) 
            return $model;
        else
            return false;
        
    }

    public function getRequired()
    {
        $model = RequiredMaterialOrder::find()->where([
            'order_id' => $this->id
        ])
        ->all();

        return $model;
    }

    public function getFiles()
    {
        $model = OrderMaterials::find()->where([
            'order_id' => $this->id
        ])
        ->count();

        return $model;
    }


    public function getPauses()
    {

        $model = PausedOrders::find()
        ->where([
            'order_id' => $this->id
        ])
        ->andWhere(['!=', 'end_date','9999-12-31 00:00:00'])
        ->orderBy(['id' => SORT_DESC])
        ->all();

        $arr = 0;
        foreach ($model as $key => $value) {
            $t1 = strtotime($value->end_date); //kottasi
            $t2 = strtotime($value->start_date);
            $diff = $t1 - $t2;
            $hours = $diff / ( 60 * 60 );    
            $arr += $hours;
        }

        return $arr;
    }

    
}
