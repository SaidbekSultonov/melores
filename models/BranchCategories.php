<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "branch_categories".
 *
 * @property int $id
 * @property int|null $branch_id
 * @property int|null $category_id
 * @property int|null $status
 */
class BranchCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'branch_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_id', 'category_id', 'status'], 'default', 'value' => null],
            [['branch_id', 'category_id', 'status'], 'integer'],
            [['branch_id', 'category_id'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'branch_id' => 'Branch ID',
            'category_id' => 'Category ID',
            'status' => 'Status',
        ];
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::classname(), ['id' => 'branch_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::classname(), ['id' => 'category_id']);
    }
}
