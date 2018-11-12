<?php
namespace Guestbook\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class MessagesTable
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

    public function fetchAllOrderBy($orderBy)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['theme','text','pictures', 'filepath', 'date', 'ip', 'browser']);
        $select->join('users', 'users.user_id = messages.id_user', ['name', 'email'], 'inner');
        $select->order($orderBy);

        return $this->tableGateway->selectWith($select);
    }

    public function getMessages($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['message_id' => $id]);
        $row = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
              'Could not find row with identifier %d', $id
            ));
        }
        return $row;
    }

    public function saveMessages(Messages $messages)
    {
        $data = [
            'theme' => $messages->theme,
            'text' => $messages->text,
            'pictures' => $messages->pictures,
            'filepath' => $messages->filepath,
            'date' => $messages->date,
            'ip' => $messages->ip,
            'browser' => $messages->browser,
            'id_user' => $messages->id_user
        ];
        $id = (int) $messages->message_id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (!$this->getMessages($id)){
            throw new RuntimeException(sprintf(
                'Cannot update message with identifier %d; does not exist', $id
            ));
        }

        $this->tableGateway->update($data, ['id '=> $id]);
    }

    public function deleteMessages($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
