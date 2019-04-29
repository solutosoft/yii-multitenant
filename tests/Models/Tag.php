<?php

namespace Soluto\Multitenant\Tests\Models;

use soluto\base\db\MultiTenantRecord;

/**
 * @property string $description
 * @property string $link
 */
class Tag extends MultiTenantRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'link'], 'string'],
            [['description'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function resourceKey()
    {
        return 'link';
    }
}
