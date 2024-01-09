<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sections".
 *
 * @property int $id
 * @property string $title
 * @property string $created_date
 * @property int $status
 */
class Sections extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sections';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_date'], 'safe'],
            [['status'], 'default', 'value' => null],
            [['status','order_column'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['title'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Nomi',
            'created_date' => 'Qo`shilgan sana',
            'status' => 'Holati',
        ];
    }


    public function getOrders()
    {
        $model = SectionOrders::find()
        ->where(
            [
                'section_id' => $this->id,
                'step' => NULL,
            ]
        )
        ->orderBy([
            'id' => SORT_DESC
        ])
        ->all();

        return $model;

        // echo "<pre>";
        // print_r($this);
        // die();
    }

    public function getTimes()
    {
        $model = SectionTimes::find()
        ->where(
            [
                'section_id' => $this->id,
            ]
        )
        ->orderBy([
            'id' => SORT_DESC
        ])
        ->all();

        return $model;

        // echo "<pre>";
        // print_r($this);
        // die();
    }


    public function getCurrent_time()
    {
        $model = SectionTimes::find()
        ->where(['section_id' => $this->id])
        ->andWhere([
            '<=','start_date',date('Y-m-d')
        ])
        ->andWhere([
            '>=','end_date',date('Y-m-d')
        ])
        ->one();

        if (isset($model)) {
            return $model->work_time;
        }
        else{
            return '<span class="label label-default">Ish vaqti belgilanmagan</span>';
        }


        // echo "<pre>";
        // print_r($this);
        // die();
    }


    public function getOrderscontrol()
    {
        $model = SectionOrdersControl::find()
        ->where(
            [
                'section_id' => $this->id,
                'exit_date' => NULL
            ]
        )
        ->orderBy([
            'id' => SORT_DESC
        ])
        ->all();

        return $model;

        // echo "<pre>";
        // print_r($this);
        // die();
    }
}
