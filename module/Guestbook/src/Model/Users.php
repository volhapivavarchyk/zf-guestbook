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

class Users implements InputFilterAwareInterface
{
    public $user_id;
    public $name;
    public $email;

    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->user_id = !empty($data['user_id']) ? $data['user_id'] : null;
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

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}
