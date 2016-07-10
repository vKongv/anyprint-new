<?php

namespace common\models\print_request;

/**
 * This is the ActiveQuery class for [[PrintRequest]].
 *
 * @see PrintRequest
 */
class PrintRequestQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PrintRequest[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PrintRequest|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
