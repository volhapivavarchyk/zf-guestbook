<?php
namespace Guestbook\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class UsersTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getUsers($user_id)
    {
        $user_id = (int) $user_id;
        $rowset = $this->tableGateway->select(['user_id' => $user_id]);
        $row = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
              'Could not find row with identifier %d', $user_id
            ));
        }
        return $row;
    }

    public function saveUsers(Users $users)
    {
        $data = [
            'name' => $users->name,
            'email' => $users->email
        ];

        $user_id = (int) $users->user_id;

        if ($user_id === 0) {
            $this->tableGateway->insert($data);
            return $this->tableGateway->getLastInsertValue();
        }

        if (!$this->getUsers($user_id)){
            throw new RuntimeException(sprintf(
                'Cannot update message with identifier %d; does not exist', $user_id
            ));
        }

        $this->tableGateway->update($data, ['user_id '=> $user_id]);

        return $user_id;
    }

    public function deleteUsers($user_id)
    {
        $this->tableGateway->delete(['user_id' => (int) $user_id]);
    }
}
