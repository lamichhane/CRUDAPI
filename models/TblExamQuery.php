<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TblExam]].
 *
 * @see TblExam
 */
class TblExamQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TblExam[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TblExam|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
