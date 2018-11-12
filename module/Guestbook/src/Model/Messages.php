<?php
namespace Guestbook\Model;

use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Messages implements InputFilterAwareInterface
{
    public $message_id;
    public $theme;
    public $text;
    public $pictures;
    public $filepath;
    public $date;
    public $ip;
    public $browser;
    public $id_user;
    public $name;
    public $email;

    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->message_id = !empty($data['message_id']) ? $data['message_id'] : null;
        $this->theme = !empty($data['theme']) ? $data['theme'] : null;
        $this->text = !empty($data['text']) ? $data['text'] : null;
        $this->pictures = !empty($data['pictures']) ? $data['pictures'] : null;
        $this->filepath = !empty($data['filepath']) ? $data['filepath'] : null;
        $this->date = !empty($data['date']) ? $data['date'] : null;
        $this->ip = !empty($data['ip']) ? $data['ip'] : null;
        $this->browser = !empty($data['browser']) ? $data['browser'] : null;
        $this->id_user = !empty($data['id_user']) ? $data['id_user'] : null;
        $this->name = !empty($data['name']) ? $data['name'] : null;
        $this->email = !empty($data['email']) ? $data['email'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter', __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter){
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'name',
            'required' => 'true',
            'filters' => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 150,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'email',
            'required' => 'true',
            'filters' => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
            ],
        ]);
        $inputFilter->add([
            'name' => 'theme',
            'required' => 'true',
            'filters' => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 250,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'text',
            'required' => 'true',
            'filters' => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                    ],
                ],
            ],
        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}
