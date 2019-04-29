<?php

namespace Soluto\Multitenant\Tests\Fixtures;

use Soluto\Multitenant\Tests\Models\Person;
use yii\test\ActiveFixture;

class PersonFixture extends ActiveFixture
{
    public $modelClass = Person::class;
}
