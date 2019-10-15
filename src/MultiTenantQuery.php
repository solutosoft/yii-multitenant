<?php

namespace solutosoft\multitenant;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;

class MultiTenantQuery extends ActiveQuery
{
    private $_withTenant = true;

    /**
     * {@inheritDoc}
     */
    public function prepare($builder)
    {
        if ($this->_withTenant) {
            $this->addTenantCondition();
        }
        return parent::prepare($builder);
    }

    /**
     * Disables where tenant condition
     * @return $this
     */
    public function withoutTenant()
    {
        $this->_withTenant = false;
        return $this;
    }

    /**
     * Enables where tenant condition
     * @return $this
     */
    public function withTenant()
    {
        $this->_withTenant = true;
        return $this;
    }

    /**
     * Adds an additional WHERE tenant condition to the existing one.
     * @return void
     */
    private function addTenantCondition()
    {
        $identity = Yii::$app->user->identity;
        if ($identity instanceof TenantInterface) {
            list(,$alias) = $this->getTableNameAndAlias();
            $this->andOnCondition(["{$alias}.tenant_id" => $identity->getTenantId()]);
        } else {
            throw new NotSupportedException("Identity does not implements TenantInteface");
        }
    }
}