<?php

namespace solutosoft\multitenant\tests;

use solutosoft\multitenant\tests\fixtures\PersonFixture;
use solutosoft\multitenant\tests\fixtures\PersonTagFixture;
use solutosoft\multitenant\tests\fixtures\PessoaTagFixture;
use solutosoft\multitenant\tests\fixtures\TagFixture;
use solutosoft\multitenant\tests\models\Person;

class MultiTenantRecordTest extends TestCase
{

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->mockApplication();
        $this->loggedInAs(1);
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            'people' => PersonFixture::class,
            'tags' => TagFixture::class,
            'persontags' => PersonTagFixture::class
        ];
    }

    public function testSave()
    {
        $test = new Person([
            'name' => 'Test'
        ]);

        $forced = new Person([
            'name' => 'Forced',
            'tenant_id' => 2
        ]);

        $this->assertTrue($test->save());
        $this->assertTrue($forced->save());

        $forced = Person::findOne($forced->id);
        $test = Person::findOne($test->id);

        $this->assertEquals(1, $test->tenant_id);
        $this->assertNull($forced);
    }

    public function testFind()
    {
        $data = $this->getFixture('people')->data;

        $filtered = array_filter($data, function($person){
            return isset($person['tenant_id']) && $person['tenant_id'] == 1;
        });

        $this->assertCount(count($data), Person::find()->withoutTenant()->all());
        $this->assertCount(count($filtered), Person::find()->all());
    }

    public function testToArray()
    {
        $person = $this->people('bob');
        $data = $person->toArray([], ['tags']);

        $this->assertArrayNotHasKey('tenant_id', $data);
        $this->assertArrayHasKey('tags', $data);

        foreach ($data['tags'] as $tag) {
            $this->assertArrayNotHasKey('tenant_id', $tag);
        }
    }
}
