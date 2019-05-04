<?php

/**
* @author Yogendra Lamichhane
*/

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_exam".
 *
 * @property int $id
 * @property string $name
 * @property string $start_time
 * @property string $end_time
 */
class TblExam extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_exam';
    }
    /**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['start_time', 'end_time'], 'safe'],
            [['name'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
        ];
    }

    /**
     * {@inheritdoc}
     * @return TblExamQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblExamQuery(get_called_class());
    }
}
