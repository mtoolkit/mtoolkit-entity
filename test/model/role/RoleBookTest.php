<?php

namespace mtoolkit\entity\test\model\role;

use mtoolkit\entity\model\role\Role;
use mtoolkit\entity\model\role\RoleBook;
use mtoolkit\entity\test\model\Connection;
use mtoolkit\entity\test\model\RandomBook;

class RoleBookTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Role[]
     */
    private $roleList = array();

    /**
     * @var RoleBook
     */
    private $roleBook;

    /**
     * @var \PDO
     */
    private $connection;

    public function testDelete()
    {
        foreach ($this->roleBook->get() as $role) {
            $this->roleBook->delete($role);
        }

        $this->assertEquals(0, count($this->roleBook->get()));
    }

    /**
     * @throws \mtoolkit\entity\model\role\exception\InsertRoleException
     * @depends testDelete
     */
    public function testSaveAndGet()
    {
        for ($i = 0; $i < count($this->roleList); $i++) {
            /* @var $role Role */
            $role = $this->roleList[$i];
            $role->setId($this->roleBook->save($this->roleList[$i]));
            $this->roleList[$i] = $role;
        }

        $rolesInDB = $this->roleBook->get();
        $this->assertEquals(count($this->roleList), count($rolesInDB));

        for ($i = 0; $i < count($this->roleList); $i++) {
            $currentRole = $this->roleList[$i];
            $rolesInDb = $this->roleBook->get($currentRole->getId());
            $roleInDB = $rolesInDb[0];

            $this->assertEquals($currentRole, $roleInDB);
        }
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->connection = Connection::get();
        $this->roleBook = new RoleBook($this->connection);

        for ($k = 0; $k < 10; $k++) {
            $role = new Role();

            $role->setName(RandomBook::getRandomString());

            $this->roleList[] = $role;
        }
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

}
