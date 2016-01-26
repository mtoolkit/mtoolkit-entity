<?php

namespace mtoolkit\entity\test\model\provider;

use mtoolkit\entity\model\provider\ProviderInfo;
use mtoolkit\entity\model\provider\ProviderInfoBook;
use mtoolkit\entity\test\model\Connection;
use mtoolkit\entity\test\model\RandomBook;

class ProviderInfoBookTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProviderInfo[]
     */
    private $providerInfoList = array();

    /**
     * @var ProviderInfoBook
     */
    private $providerInfoBook;

    /**
     * @var \PDO
     */
    private $connection;

    public function testDelete()
    {
        foreach ($this->providerInfoBook->get() as $providerInfo) {
            $this->providerInfoBook->delete($providerInfo);
        }

        $this->assertEquals(0, count($this->providerInfoBook->get()));
    }

    public function testSaveAndGet()
    {
        for ($i = 0; $i < count($this->providerInfoList); $i++) {
            /* @var $providerInfo ProviderInfo */
            $providerInfo = $this->providerInfoList[$i];
            $providerInfo->setId($this->providerInfoBook->save($this->providerInfoList[$i]));
            $this->providerInfoList[$i] = $providerInfo;
        }

        $providerInfosInDB = $this->providerInfoBook->get();
        $this->assertEquals(count($this->providerInfoList), count($providerInfosInDB));

        for ($i = 0; $i < count($this->providerInfoList); $i++) {
            $currentProviderInfo = $this->providerInfoList[$i];
            $providerInfosInDb = $this->providerInfoBook->get($currentProviderInfo->getId());
            $providerInfoInDB = $providerInfosInDb[0];

            $this->assertEquals($currentProviderInfo, $providerInfoInDB);
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
        $this->providerInfoBook = new ProviderInfoBook($this->connection);

        for ($k = 0; $k < 10; $k++) {
            $providerInfo = new ProviderInfo();

            $providerInfo->setName(RandomBook::getRandomString())
                ->setAppKey(RandomBook::getRandomString())
                ->setSecretKey(RandomBook::getRandomString());

            $this->providerInfoList[] = $providerInfo;
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
