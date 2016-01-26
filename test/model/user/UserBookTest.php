<?php
/**
 * Created by PhpStorm.
 * User: michelepagnin
 * Date: 26/01/16
 * Time: 11.31
 */

namespace mtoolkit\entity\test\model\user;

use mtoolkit\entity\model\provider\ProviderInfoBook;
use mtoolkit\entity\model\role\RoleBook;
use mtoolkit\entity\model\user\User;
use mtoolkit\entity\model\user\UserBook;
use mtoolkit\entity\test\model\Connection;
use mtoolkit\entity\test\model\RandomBook;

class UserBookTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var User[]
     */
    private $userList = array();

    /**
     * @var UserBook
     */
    private $userBook;

    /**
     * @var ProviderInfoBook
     */
    private $providerInfoBook;

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
        foreach ($this->userBook->get() as $user) {
            $this->userBook->delete($user);
        }

        $this->assertEquals(0, count($this->userBook->get()));
    }

    /**
     * @throws \mtoolkit\entity\model\user\exception\InsertUserException
     * @depends testDelete
     */
    public function testSaveAndGet()
    {
        for ($i = 0; $i < count($this->userList); $i++) {
            /* @var $user User */
            $user = $this->userList[$i];
            $user->setId($this->userBook->save($this->userList[$i]));
            $this->userList[$i] = $user;
        }

        $userInDB = $this->userBook->get();
        $this->assertEquals(count($this->userList), count($userInDB));

        for ($i = 0; $i < count($this->userList); $i++) {
            $currentUser = $this->userList[$i];
            $usersInDb = $this->userBook->get($currentUser->getId());
            $userInDb = $usersInDb[0];

            $this->assertEquals($currentUser, $userInDb);
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
        $this->userBook = new UserBook($this->connection);
        $this->providerInfoBook = new ProviderInfoBook($this->connection);
        $this->roleBook = new RoleBook($this->connection);

        foreach ($this->providerInfoBook->get() as $providerInfo) {
            $this->providerInfoBook->delete($providerInfo);
        }

        foreach ($this->roleBook->get() as $role) {
            $this->roleBook->delete($role);
        }

        for ($k = 0; $k < 10; $k++) {
            $user = new User();

            $user->setEnabled(RandomBook::getRandomBoolean())
                ->setEnabledDate(RandomBook::getRandomDate())
                ->setAccessFailedCount(rand(1, 5))
                ->setEmail(RandomBook::getRandomEmail())
                ->setPassword(RandomBook::getRandomString())
                ->setPhoneNumber(RandomBook::getRandomPhoneNumber())
                ->setTwoFactorEnabled(RandomBook::getRandomBoolean())
                ->setUserName(RandomBook::getRandomString());

            $this->userList[] = $user;
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
