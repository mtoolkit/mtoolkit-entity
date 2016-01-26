<?php 
namespace mtoolkit\entity\model\provider;

use mtoolkit\model\sql\MPDOQuery;
use mtoolkit\core\MDataType;
use mtoolkit\entity\model\provider\exception\DeleteProviderException;
use mtoolkit\entity\model\provider\exception\InsertProviderUserException;

final class ProviderUserBook{
	public function __construct(\PDO $connection = null)
    {
        $this->connection = $connection;
    }
    
    public function save(ReadableProviderUser $provider)
    {
        $sql = "CALL mt_provider_user_save(?,?,?);";
        $query = new MPDOQuery($sql, $this->connection);
        $query->bindValue($provider->getUserId());
        $query->bindValue($provider->getId());
        $query->bindValue($provider->getProviderUserId());
        $queryResult = $query->exec();

        if ($queryResult == false)
        {
            throw new InsertProviderUserException($query->getLastError()->getDriverText());
        }
    }
    
    public function get($userId = null, $providerId = null, $providerUserId = null)
    {
    	MDataType::mustBeNullableInt($userId);
        MDataType::mustBeNullableInt($providerId);
        MDataType::mustBeNullableString($providerUserId);

        $providerUserList = array();
        $sql = "CALL mt_provider_user_get(?, ?, ?);";
        $query = new MPDOQuery($sql, $this->connection);
        $query->bindValue($userId);
        $query->bindValue($providerId);
        $query->bindValue($providerUserId);
        $queryResult = $query->exec();

        if ($queryResult == false || $query->getResult()->rowCount() <= 0)
        {
            return $providerUserList;
        }

        foreach ($query->getResult() as $row)
        {
            $providerUser = new ProviderUser();

            $providerUser->setProviderName($row['name'])
                ->setProviderUserId($row['provider_user_id'])
                ->setProviderId((int)$row['id'])
                ->setUserId((int)$row['user_id']);

            $providerUserList[] = $providerUser;
        }

        return $providerUserList;
    }
    
    public function delete($userId = null, $providerId = null, $providerUserId = null)
    {
    	MDataType::mustBeNullableInt($userId);
        MDataType::mustBeNullableInt($providerId);
        MDataType::mustBeNullableString($providerUserId);

        $sql = "CALL mt_provider_user_delete(?,?, ?);";
        $query = new MPDOQuery($sql, $this->connection);
        $query->bindValue($userId);
        $query->bindValue($providerId);
        $query->bindValue($providerUserId);
        $queryResult = $query->exec();

        if ($queryResult == false)
        {
            throw new DeleteProviderException($query->getLastError());
        }
    }
}
