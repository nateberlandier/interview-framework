<?php

declare(strict_types = 1);

namespace Example\Tests\Unit\Model;

use Example\Tests\BaseCase;
use Mini\Controller\Exception\BadInputException;
use Example\Model\ExampleModel;

/**
 * Example view builder test.
 */
class ExampleModelTest extends BaseCase
{
    /**
     * Test getting a field from model object
     *
     * @return void
     */
    public function testGetField(): void
    {
        //creation of ExampleModel object
        $ex_model = new ExampleModel();
        $ex_model->fields["id"] = 1;
        $ex_model->fields["created"] = '2020-07-14 12:00:00';
        $ex_model->fields["code"] = 'TESTCODE';
        $ex_model->fields["description"] = 'Test description';
        
        $ret = $ex_model->getField("code");
        
        $this->assertNotEmpty($ret);
        $this->assertIsString($ret);

        $this->assertStringContainsString('TESTCODE', $ret);
    }
    
    /**
     * Test getting a field from ExampleModel object and getting an error because it is not populated
     *
     * @return void
     */
    public function testGetFieldErrorMissing(): void
    {
        $this->expectException(BadInputException::class);
        
        //creation of ExampleModel object
        $ex_model = new ExampleModel();
        $ex_model->fields["id"] = 1;
        $ex_model->fields["created"] = '2020-07-14 12:00:00';
        $ex_model->fields["description"] = 'Test description';
        
        $ret = $ex_model->getField("code");
        
        $ex_model->fields["code"] = 'TESTCODE';
        $ret = $this->getClass('Example\Model\ExampleModel')->getField("code");
        
        $this->assertNotEmpty($ret);
        $this->assertIsString($ret);

        $this->assertStringContainsString('TESTCODE', $ret);
        
        $ret = $this->getClass('Example\Model\ExampleModel')->getField("key");
    }
    
    /**
     * Test getting a field in the ExampleModel object and getting an error because it does not exist
     *
     * @return void
     */
    public function testGetFieldErrorNoExist(): void
    {
        $this->expectException(BadInputException::class);
        
        //creation of ExampleModel object
        $ex_model = new ExampleModel();
        $ex_model->fields["id"] = 1;
        $ex_model->fields["created"] = '2020-07-14 12:00:00';
        $ex_model->fields["description"] = 'Test description';
        
        $ret = $ex_model->getField("key");
    }
    
    /**
     * Test setting the value of a field in the ExampleModel object
     *
     * @return void
     */
    public function testSetField(): void
    {
        $ex_model = new ExampleModel();
        $ex_model->fields["created"] = '2020-07-14 12:00:00';
        
        $ex_model->setField("code", 'TESTCODE');
        
        $this->assertNotEmpty($ex_model->fields['code']);
        $this->assertStringContainsString('TESTCODE', $ex_model->fields['code']);
    }
    
    /**
     * Test setting the value of a field in the ExampleModel object and getting an error that the field does not exist
     *
     * @return void
     */
    public function testSetFieldError(): void
    {
        $this->expectException(BadInputException::class);
        $this->getClass('Example\Model\ExampleModel')->setField("key", "value");
    }
    
    /**
     * Test getting an example from db
     * 
     * @return void
     */
    public function testGet(): void
    {
        $this->mockDatabaseGetProcess();
        
        $ret = $this->getClass('Example\Model\ExampleModel')->get(1);

        $this->assertNotEmpty($ret);
        $this->assertStringContainsString('TESTCODE', $ret['code']);
        $this->assertStringContainsString('Test description', $ret['description']);
    }
    
    /**
     * Test creating an example in the db using object created in class
     *
     * @return void
     */
    public function testCreate(): void
    {
        $ex_model = new ExampleModel();
        $ex_model->fields["created"] = '2020-07-14 12:00:00';
        $ex_model->fields["code"] = 'TESTCODE';
        $ex_model->fields["description"] = 'Test description';
        
        $ex_model->create();

        $ret = $ex_model->get($ex_model->fields['id']);
        
        $this->assertNotEmpty($ret);
        $this->assertEquals($ex_model->fields, $ret);
    }

    /**
     * Mock the database process for the example create endpoint.
     *
     * @return void
     */
    protected function mockDatabaseGetProcess(): void
    {
        $database = $this->getMock('Mini\Database\Database');

        // Setup the database mock
        $database->shouldReceive('select')
            ->once()
            ->withArgs($this->withDatabaseInput([1]))
            ->andReturn([
                'id'          => 1,
                'created'     => '2020-07-14 12:00:00',
                'code'        => 'TESTCODE',
                'description' => 'Test description'
            ]);

        $this->setMockDatabase($database);
    }

    /**
     * Mock the database process for the example create endpoint.
     *
     * @return void
     */
    protected function mockDatabaseGetUnkownIdProcess(): void
    {
        $database = $this->getMock('Mini\Database\Database');

        // Setup the database mock
        $database->shouldReceive('select')
            ->once()
            ->withArgs($this->withDatabaseInput([2]))
            ->andReturn([]);

        $this->setMockDatabase($database);
    }
}
