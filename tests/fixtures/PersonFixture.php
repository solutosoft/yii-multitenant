<?php

namespace solutosoft\multitenant\tests\fixtures;

use solutosoft\multitenant\tests\models\Person;
use yii\test\ActiveFixture;

class PersonFixture extends ActiveFixture
{
    public $modelClass = Person::class;
}
