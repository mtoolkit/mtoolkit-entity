<?php
namespace mtoolkit\entity\model\provider;

use mtoolkit\entity\model\provider\exception\InsertProviderException;
use mtoolkit\model\sql\MPDOQuery;

class ProviderBook
{
    /**
     * @param Provider $role
     * @param \PDO|null $connection
     * @return Provider
     * @throws InsertProviderException
     * @throws \Exception
     */
    public static function save(Provider $role, \PDO $connection=null){
        $sql = "CALL mt_provider_save(?, ?, ?);";
        $query = new MPDOQuery($sql, $connection);
        $query->bindValue($role->getLoginProvider());
        $query->bindValue($role->getProviderKey());
        $query->bindValue($role->getUserId());
        $queryResult = $query->exec();

        if ($queryResult == false) {
            throw new InsertProviderException($query->getLastError());
        }

        $result = $query->getResult();
        $id = $result[0]["id"];
        $role->setId($id);

        return $role;
    }

    /**
     * @param ReadableProvider $provider
     * @param \PDO|null $connection
     * @return bool
     */
    public static function delete(ReadableProvider $provider, \PDO $connection=null){
        $sql = "CALL mt_provider_delete(?,?);";
        $query = new MPDOQuery($sql, $connection);
        $query->bindValue($provider->getId());
        return $query->exec();
    }

    /**
     * @param $userId
     * @param Provider $userLogins
     * @param \PDO|null $connection
     * @return Provider
     * @throws InsertProviderException
     * @throws \Exception
     */
    public static function saveUserLogins($userId, Provider $userLogins, \PDO $connection=null){
        $userLogins=self::save($userLogins);

        $sql = "CALL mt_user_save_provider(?,?);";
        $query = new MPDOQuery($sql, $connection);
        $query->bindValue($userId, $userLogins->getId());
        $queryResult = $query->exec();

        if ($queryResult == false) {
            throw new InsertProviderException($query->getLastError());
        }

        return $userLogins;
    }

    /**
     * @param $userLoginsId
     * @param \PDO|null $connection
     * @return array
     * @throws \Exception
     */
    public static function get($userLoginsId, \PDO $connection=null){
        $toReturn = array();
        $sql = "CALL mt_provider_get_list(?);";
        $query = new MPDOQuery($sql, $connection);
        $query->bindValue($userLoginsId);
        $queryResult = $query->exec();

        if ($queryResult == false || $query->getResult()->rowCount() <= 0) {
            return $toReturn;
        }

        foreach ($query->getResult() as $row) {
            $userLogins=new Provider();

            $userLogins->setLoginProvider($row['login_provider'])
                ->setProviderKey($row['providers_key'])
                ->setUserId($row['user_id'])
                ->setId($userLoginsId);

            $toReturn[]=$userLogins;
        }

        return $toReturn;
    }
}