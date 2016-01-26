<?php
namespace mtoolkit\entity\model\user;

use mtoolkit\core\MDataType;
use mtoolkit\entity\model\provider\ProviderUserBook;
use mtoolkit\entity\model\role\RoleBook;
use mtoolkit\entity\model\user\exception\InsertUserException;
use mtoolkit\model\sql\MPDOQuery;

class UserBook
{
    /**
     * @var null|\PDO
     */
    private $connection;

    /**
     * @var ProviderUserBook
     */
    private $providerUserBook;

    /**
     * @var RoleBook
     */
    private $roleBook;

    /**
     * UserBook constructor.
     *
     * @param \PDO|null $connection
     */
    public function __construct(\PDO $connection = null)
    {
        $this->connection = $connection;

        $this->providerUserBook = new ProviderUserBook($this->connection);
        $this->roleBook = new RoleBook($this->connection);
    }

    /**
     * @param ReadableUser $user
     * @return int
     * @throws InsertUserException
     * @throws \Exception
     * @throws \mtoolkit\entity\model\role\exception\InsertRoleException
     */
    public function save(ReadableUser $user)
    {
        $sql = "CALL mt_user_save(?, ?, ?, ?, ?, ?, ?, ?);";
        $query = new MPDOQuery($sql, $this->connection);
        // $query->bindValue($user->getId());
        $query->bindValue($user->getEmail());
        $query->bindValue($user->getPassword());
        $query->bindValue($user->getPhoneNumber());
        $query->bindValue((int)$user->isTwoFactorEnabled());
        $query->bindValue($user->getEnabledDate()->format("Y-m-d H:i:s"));
        $query->bindValue((int)$user->isEnabled());
        $query->bindValue($user->getAccessFailedCount());
        $query->bindValue($user->getUserName());
        $queryResult = $query->exec();

        if ($queryResult == false) {
            throw new InsertUserException($query->getLastError()->getDriverText());
        }

        $result = $query->getResult();
        $id = (int)$result[0]["id"];

        for ($k = 0; $k < count($user->getRoleList()); $k++) {
            $role = $user->getRoleList()[$k];
            $this->roleBook->saveRole($id, $role);
        }

        for ($k = 0; $k < count($user->getProviderUserList()); $k++) {
            $providerUser = $user->getProviderUserList()[$k];
            $this->providerUserBook->save($id, $providerUser);
        }

        return (int)$id;
    }

    /**
     * @param ReadableUser|User $user
     * @return bool
     * @throws \Exception
     */
    public function delete(ReadableUser $user)
    {
        $sql = "CALL mt_user_delete(?);";
        $query = new MPDOQuery($sql, $this->connection);
        $query->bindValue($user->getId());
        return $query->exec();
    }

    /**
     * @param int|null $userId
     * @param string|null $username
     * @param string|null $email
     * @return User[]
     * @throws \Exception
     */
    public function get($userId = null, $username = null, $email = null)
    {
        MDataType::mustBeNullableInt($userId);
        MDataType::mustBeNullableString($username);
        MDataType::mustBeNullableString($email);

        $userList = array();
        $sql = "CALL mt_user_get(?, ?, ?);";
        $query = new MPDOQuery($sql, $this->connection);
        $query->bindValue($userId);
        $query->bindValue($username);
        $query->bindValue($email);
        $queryResult = $query->exec();

        if ($queryResult == false || $query->getResult()->rowCount() <= 0) {
            return $userList;
        }

        foreach ($query->getResult() as $row) {
            $user = new User();

            $enabledDate = \DateTime::createFromFormat('Y-m-d H:i:s', $row['enabled_date']);

            $user->setId((int)$row['id'])
                ->setEmail($row['email'])
                ->setPassword($row['password'])
                ->setPhoneNumber($row['phone_number'])
                ->setTwoFactorEnabled(($row['two_factor_enabled'] == 1) ? true : false)
                ->setEnabledDate($enabledDate)
                ->setEnabled(($row['enabled'] == 1) ? true : false)
                ->setAccessFailedCount((int)$row['access_failed_count'])
                ->setUserName($row['username'])
                ->setRoleList($this->roleBook->get((int)$row['id']))
                ->setProviderUserList($this->providerUserBook->get((int)$row['id']));

            $userList[] = $user;
        }

        return $userList;
    }
}