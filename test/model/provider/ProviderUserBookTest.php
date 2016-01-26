<?php

namespace mtoolkit\entity\test\model\provider;

use mtoolkit\entity\model\provider\ProviderInfo;
use mtoolkit\entity\model\provider\ProviderInfoBook;
use mtoolkit\entity\model\provider\ProviderUser;
use mtoolkit\entity\model\provider\ProviderUserBook;
use mtoolkit\entity\model\user\User;
use mtoolkit\entity\model\user\UserBook;
use mtoolkit\entity\test\model\Connection;
use mtoolkit\entity\test\model\RandomBook;

class ProviderUserBookTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProviderUser[]
     */
    private $providerUserList = array();

    /**
     * @var ProviderUserBook
     */
    private $providerUserBook;

    /**
     * @var ProviderInfo[]
     */
    private $providerInfoList = array();

    /**
     * @var ProviderInfoBook
     */
    private $providerInfoBook;

    /**
     * @var User[]
     */
    private $userList = array();

    /**
     * @var UserBook
     */
    private $userBook;

    /**
     * @var \PDO
     */
    private $connection;

    public function testSaveAndGet()
    {
        for ($i = 0; $i < count($this->providerUserList); $i++) {
            /* @var $providerUser ProviderUser */
            $providerUser = $this->providerUserList[$i];
            $this->providerUserBook->save($this->providerUserList[$i]);
            $this->providerUserList[$i] = $providerUser;
        }

        $providerUsersInDB = $this->providerUserBook->get();
        $this->assertEquals(count($this->providerUserList), count($providerUsersInDB));

        for ($i = 0; $i < count($this->providerUserList); $i++) {
            $currentProviderUser = $this->providerUserList[$i];
            $providerUsersInDb = $this->providerUserBook->get(
                $currentProviderUser->getUserId(),
                $currentProviderUser->getProviderId(),
                $currentProviderUser->getProviderUserId()
            );
            $providerUserInDB = $providerUsersInDb[0];

            $this->assertEquals($currentProviderUser, $providerUserInDB);
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
        $this->providerUserBook = new ProviderUserBook($this->connection);

        foreach ($this->providerInfoBook->get() as $providerInfo) {
            $this->providerInfoBook->delete($providerInfo);
        }

        foreach ($this->userBook->get() as $user) {
            $this->userBook->delete($user);
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
            $user->setId($this->userBook->save($user));

            $this->userList[] = $user;

            for ($k = 0; $k < 10; $k++) {
                $providerInfo = new ProviderInfo();

                $providerInfo->setName(RandomBook::getRandomString())
                    ->setAppKey(RandomBook::getRandomString())
                    ->setSecretKey(RandomBook::getRandomString());

                $providerInfo->setId((int)$this->providerInfoBook->save($providerInfo));

                $this->providerInfoList[] = $providerInfo;

                $providerUser=new ProviderUser();
                $providerUser->setUserId($user->getId())
                    ->setProviderId($providerInfo->getId())
                    ->setProviderName($providerInfo->getName())
                    ->setProviderUserId(RandomBook::getRandomString());

                $this->providerUserList[]=$providerUser;
            }
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
