<?php
namespace mtoolkit\entity\model\role;

use mtoolkit\core\MDataType;
use mtoolkit\entity\model\provider\exception\DeleteRoleException;
use mtoolkit\entity\model\role\exception\InsertRoleException;
use mtoolkit\model\sql\MPDOQuery;

class RoleBook
{
    private $connection;

    /**
     * RoleBook constructor.
     *
     * @param \PDO|null $connection
     */
    public function __construct(\PDO $connection = null)
    {
        $this->connection = $connection;
    }

    /**
     * @param ReadableRole $role
     * @return int
     * @throws InsertRoleException
     * @throws \Exception
     */
    public function save(ReadableRole $role)
    {
        $sql = "CALL mt_role_save(?);";
        $query = new MPDOQuery($sql, $this->connection);
        $query->bindValue($role->getName());
        $queryResult = $query->exec();

        if ($queryResult == false)
        {
            throw new InsertRoleException($query->getLastError());
        }

        $result = $query->getResult();
        $id = $result[0]["id"];

        return (int)$id;
    }

    /**
     * @param ReadableRole $role
     * @return bool
     * @throws \Exception
     */
    public function delete(ReadableRole $role)
    {
        $sql = "CALL mt_role_delete(?);";
        $query = new MPDOQuery($sql, $this->connection);
        $query->bindValue($role->getId());
        return $query->exec();
    }

    /**
     * @param int $userId
     * @param int $roleId
     * @throws DeleteRoleException
     * @throws \Exception
     */
    public function deleteRole($userId, $roleId)
    {
        MDataType::mustBeInt($userId);
        MDataType::mustBeInt($roleId);

        $sql = "CALL mt_user_delete_role(?,?);";
        $query = new MPDOQuery($sql, $this->connection);
        $query->bindValue($userId);
        $query->bindValue($roleId);
        $queryResult = $query->exec();

        if ($queryResult == false)
        {
            throw new DeleteRoleException($query->getLastError());
        }
    }

    /**
     * @param int $userId
     * @param ReadableRole $role
     * @return Role
     * @throws InsertRoleException
     * @throws \Exception
     */
    public function saveRole($userId, ReadableRole $role)
    {
        MDataType::mustBeInt($userId);
        $id = (int)$this->save($role);

        $sql = "CALL mt_user_save_role(?,?);";
        $query = new MPDOQuery($sql, $this->connection);
        $query->bindValue($userId);
        $query->bindValue($id);
        $queryResult = $query->exec();

        if ($queryResult == false)
        {
            throw new InsertRoleException($query->getLastError());
        }
    }

    /**
     * @param int|null $userId
     * @return array
     * @throws \Exception
     */
    public function get($userId=null)
    {
        MDataType::mustBeNullableInt($userId);

        $toReturn = array();
        $sql = "CALL mt_role_get(?);";
        $query = new MPDOQuery($sql, $this->connection);
        $query->bindValue($userId);
        $queryResult = $query->exec();

        if ($queryResult == false || $query->getResult()->rowCount() <= 0)
        {
            return $toReturn;
        }

        foreach ($query->getResult() as $row)
        {
            $role = new Role();

            $role->setId($row['id'])
                ->setName($row['name']);

            $toReturn[] = $role;
        }

        return $toReturn;
    }
}