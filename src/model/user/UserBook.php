<?php
namespace mtoolkit\entity\model\user;

use mtoolkit\entity\model\role\RoleBook;
use mtoolkit\entity\model\user\exception\InsertUserException;
use mtoolkit\model\sql\MPDOQuery;

class UserBook
{
    /**
     * @param User $user
     * @param \PDO|null $connection
     * @return User
     * @throws InsertUserException
     * @throws \Exception
     */
    public function save(User $user, \PDO $connection=null){
        $sql = "CALL mentity_user_save(?, ?, ?, ?, ?, ?, ?, ?);";
        $query = new MPDOQuery($sql, $connection);
        $query->bindValue($user->getId());
        $query->bindValue($user->getEmail());
        $query->bindValue($user->getPassword());
        $query->bindValue($user->getPhoneNumber());
        $query->bindValue($user->isTwoFactorEnabled());
        $query->bindValue($user->getLockoutEndDateUtc());
        $query->bindValue($user->isLockoutEnabled());
        $query->bindValue($user->getAccessFailedCount());
        $queryResult = $query->exec();

        if ($queryResult == false) {
            throw new InsertUserException($query->getLastError());
        }

        $result = $query->getResult();
        $id = $result[0]["id"];
        $user->setId($id);

        for($k=0; $k<count($user->getRoleList()); $k++){
            $role=$user->getRoleList()[$k];
            $user->getRoleList()[$k]=RoleBook::saveRole($id, $role);
        }

        for($k=0; $k<count($user->getUserLoginsList()); $k++){
            $role=$user->getUserLoginsList()[$k];
            $user->getRoleList()[$k]=RoleBook::saveLoginsList($id, $role);
        }

        return $user;
    }

    /**
     * @param User $user
     * @param \PDO|null $connection
     * @return bool
     */
    public function delete(ReadableUser $user, \PDO $connection=null){
        $sql = "CALL mentity_user_delete(?);";
        $query = new MPDOQuery($sql, $connection);
        $query->bindValue($user->getId());
        return $query->exec();
    }

    /**
     * @param $userId
     * @param \PDO|null $connection
     * @return User
     * @throws \Exception
     */
    public function get($userId, \PDO $connection=null){
        $toReturn = new User();
        $sql = "CALL mentity_user_get(?);";
        $query = new MPDOQuery($sql, $connection);
        $query->bindValue($userId);
        $queryResult = $query->exec();

        if ($queryResult == false || $query->getResult()->rowCount() <= 0) {
            return $toReturn;
        }

        foreach ($query->getResult() as $row) {
            $toReturn->setId($row['id'])
                ->setEmail($row['email'])
                ->setPassword($row['password'])
                ->setPhoneNumber($row['phone_numer'])
                ->setTwoFactorEnabled($row['two_factory_enabled'])
                ->setLockoutEndDateUtc($row['lockout_end'])
                ->setLockoutEnabled($row['lockount_enabled'])
                ->setAccessFailedCount($row['access_failed_count'])
                ->setUserName($row['user_name'])
                ->setRoleList(RoleBook::getList($row['id']))
                ->setUserLoginsList(UserLoginsBook::getList($row['id']));
        }

        return $toReturn;
    }

    /**
     * @param $userId
     * @param \PDO|null $connection
     * @return User[]
     * @throws \Exception
     */
    public function getList($userId, \PDO $connection=null){
        $toReturn = array();
        $sql = "CALL mentity_user_get_list(?);";
        $query = new MPDOQuery($sql, $connection);
        $query->bindValue($userId);
        $queryResult = $query->exec();

        if ($queryResult == false || $query->getResult()->rowCount() <= 0) {
            return $toReturn;
        }

        foreach ($query->getResult() as $row) {
            $user=new User();

            $user->setId($row['id'])
                ->setEmail($row['email'])
                ->setPassword($row['password'])
                ->setPhoneNumber($row['phone_numer'])
                ->setTwoFactorEnabled($row['two_factory_enabled'])
                ->setLockoutEndDateUtc($row['lockout_end'])
                ->setLockoutEnabled($row['lockount_enabled'])
                ->setAccessFailedCount($row['access_failed_count'])
                ->setUserName($row['user_name'])
                ->setRoleList(RoleBook::getList($row['id']))
                ->setUserLoginsList(UserLoginsBook::getList($row['id']));

            $toReturn[]=$user;
        }

        return $toReturn;
    }
}