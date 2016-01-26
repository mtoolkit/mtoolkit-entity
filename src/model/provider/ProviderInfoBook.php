<?php 
namespace mtoolkit\entity\model\provider;

use mtoolkit\model\sql\MPDOQuery;
use mtoolkit\core\MDataType;
use mtoolkit\entity\model\provider\exception\InsertProviderException;

final class ProviderInfoBook{
	public function __construct(\PDO $connection = null)
    {
        $this->connection = $connection;
    }
    
    /**
     * @param ReadableProviderInfo $provider
     * @return int Provider id
     * @throws InsertProviderException
     * @throws \Exception
     */
    public function save(ReadableProviderInfo $provider)
    {
        $sql = "CALL mt_provider_save(?, ?, ?);";
        $query = new MPDOQuery($sql, $this->connection);
        $query->bindValue($provider->getName());
        $query->bindValue($provider->getAppKey());
        $query->bindValue($provider->getSecretKey());
        $queryResult = $query->exec();

        if ($queryResult == false)
        {
            throw new InsertProviderException($query->getLastError()->getDriverText());
        }

        $result = $query->getResult();
        $id = $result[0]["id"];

        return (int)$id;
    }
    
    /**
     * @param ReadableProviderInfo $provider
     * @return bool
     * @throws \Exception
     */
    public function delete(ReadableProviderInfo $provider)
    {
        $sql = "CALL mt_provider_delete(?);";
        $query = new MPDOQuery($sql, $this->connection);
        $query->bindValue($provider->getId());
        return $query->exec();
    }
    
    /**
     * @param int $providerId
     * @return ProviderInfo[]
     * @throws \Exception
     */
    public function get($providerId = null)
    {
        MDataType::mustBe(array(MDataType::INT));

        $toReturn = array();
        $sql = "CALL mt_provider_get(?);";
        $query = new MPDOQuery($sql, $this->connection);
        $query->bindValue($providerId);
        $queryResult = $query->exec();

        if ($queryResult == false || $query->getResult()->rowCount() <= 0)
        {
            return $toReturn;
        }

        foreach ($query->getResult() as $row)
        {
            $userLogins = new ProviderInfo();

            $userLogins->setName($row['name'])
                ->setAppKey($row['app_key'])
                ->setSecretKey($row['secret_key'])
                ->setId((int)$row['id']);

            $toReturn[] = $userLogins;
        }

        return $toReturn;
    }
}

