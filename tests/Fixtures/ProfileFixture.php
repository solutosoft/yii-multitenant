<?php

namespace Soluto\Multitenant\Tests\Fixtures;

use Soluto\Multitenant\Tests\Models\Profile;
use yii\test\ActiveFixture;

class ProfileFixture extends ActiveFixture
{
    public $modelClass = Profile::class;
}
