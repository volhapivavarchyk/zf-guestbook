<?php
namespace Guestbook\Form;

use Zend\Form\Form;

class GuestbookForm extends Form
{
    public function __construct($name = null)
    {

        parent::__construct('guestbook');

        $this->setAttribute('method', 'POST');
        $this->setAttribute('enctype', 'multipart/form-data');

        $this->add([
            'name' => 'name',
            'type' => 'text',
            'options' => [
                'label' => 'ФИО*',
            ],
            'attributes' => [
                'minlength' => '1',
                'maxlength' => '150',
                'id'    => 'name',
                'required' => true,
            ],
        ]);
        $this->add([
            'name' => 'email',
            'type' => 'email',
            'options' => [
                'label' => 'E-mail*',
          ],
          'attributes' => [
              'id'    => 'email',
              'required' => true,
          ],
        ]);
        $this->add([
            'name' => 'id_user',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'theme',
            'type' => 'text',
            'options' => [
               'label' => 'Тема сообщения*',
            ],
            'attributes' => [
                'id'    => 'theme',
                'required' => true,
            ],
        ]);
        $this->add([
            'name' => 'text',
            'type' => 'textarea',
            'options' => [
               'label' => 'Текст сообщения*',
            ],
            'attributes' => [
                'id'    => 'text',
                'required' => true,
            ],
        ]);
        $this->add([
            'name' => 'formatA',
            'type' => 'button',
            'attributes' => [
                'value' => 'link',
            ],
        ]);
        $this->add([
            'name' => 'formatCode',
            'type' => 'button',
            'attributes' => [
                'value' => 'code',
            ],
        ]);
        $this->add([
            'name' => 'formatItalic',
            'type' => 'button',
            'attributes' => [
                'value' => 'italic',
            ],
        ]);
        $this->add([
            'name' => 'formatStrike',
            'type' => 'button',
            'attributes' => [
                'value' => 'strike',
            ],
        ]);
        $this->add([
            'name' => 'formatStronge',
            'type' => 'button',
            'attributes' => [
                'value' => 'stronge',
            ],
        ]);
        $this->add([
            'name' => 'previewButton',
            'type' => 'button',
            'attributes' => [
                'value' => 'Отобразить',
                'id' => 'preview-button',
            ],
        ]);
        $this->add([
            'name' => 'pictures',
            'type' => 'file',
            'options' => [
               'label' => 'Изображение',
            ],
            'attributes' => [
                'id'    => 'pictures',
            ],
      ]);
        $this->add([
            'name' => 'filepath',
            'type' => 'file',
            'options' => [
               'label' => 'Файл',
            ],
            'attributes' => [
                'id'    => 'filepath',
            ],
        ]);
        $this->add([
            'name' => 'send',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Добавить',
                'id'    => 'send',
            ],
        ]);
        $this->add([
            'name' => 'throw',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Отменить',
                'id'    => 'throw',
            ],
        ]);
    }
}
