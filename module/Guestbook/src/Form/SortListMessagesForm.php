<?php
namespace Guestbook\Form;

use Zend\Form\Form;

class SortListMessagesForm extends Form
{
    public function __construct($name = null)
    {

        parent::__construct('listmessages');

        $this->setAttribute('method', 'POST');

        $this->add([
            'name' => 'name_desc',
            'type' => 'image',
            'attributes' => [
                'width' => '10',
                'height' => '10',
                'value' => 'name_desc',
                'id'    => 'name_desc',
            ],
        ]);
        $this->add([
            'name' => 'name_asc',
            'type' => 'image',
            'attributes' => [
                'width' => '10',
                'height' => '10',
                'value' => 'name_asc',
             ],
        ]);
        $this->add([
            'name' => 'email_desc',
            'type' => 'image',
            'attributes' => [
                'width' => '10',
                'height' => '10',
                'value' => 'email_desc',
            ],
        ]);
        $this->add([
            'name' => 'email_asc',
            'type' => 'image',
            'attributes' => [
                'width' => '10',
                'height' => '10',
                'value' => 'email_asc',
            ],
        ]);
        $this->add([
            'name' => 'date_desc',
            'type' => 'image',
            'attributes' => [
                'width' => '10',
                'height' => '10',
                'value' => 'date_desc',
            ],
        ]);
        $this->add([
            'name' => 'date_asc',
            'type' => 'image',
            'attributes' => [
                'width' => '10',
                'height' => '10',
                'value' => 'date_asc',
            ],
        ]);

    }

}
