<?php

namespace Soluto\Multitenant;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;

class MultiTenantRecord extends ActiveRecord
{
    /**
     *@inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!Yii::$app->user->isGuest) {
            $identity = Yii::$app->user->identity;
            if ($identity instanceof TenantInterface) {
                if ($insert && $this->beforeApplyTenant() && !$this->tenant_id) {
                    $this->tenant_id = $identity->getTenantId();
                    $this->afterApplyTenant();
                }
            } else {
                throw new NotSupportedException("Identity does not implements TenantInteface");
            }
        }
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public static function find()
    {
        return Yii::createObject(MultiTenantQuery::class, [get_called_class()]);
    }


    /**
     * This method is invoked before assign the attribute tenant id
     * You may override this method to do preliminary checks before apply tenant id.
     * @return boolean whether the tenant id should be assigned. Defaults to true.
     */
    protected function beforeApplyTenant()
    {
        return true;
    }

    /**
     * This method is invoked after assign the attribute tenant id
     * You may override this method to do postprocessing after apply tenant id.
     */
    protected function afterApplyTenant()
    {

    }
}
