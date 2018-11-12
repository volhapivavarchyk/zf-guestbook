<?php
namespace Guestbook\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Guestbook\Model\Messages;
use Guestbook\Model\MessagesTable;
use Guestbook\Model\Users;
use Guestbook\Model\UsersTable;
use Guestbook\Form\GuestbookForm;
use Guestbook\Form\SortListMessagesForm;

class GuestbookController extends AbstractActionController
{
    private $messagesTable;
    private $usersTable;

    public function __construct(MessagesTable $messagesTable, UsersTable $usersTable)
    {
        $this->messagesTable = $messagesTable;
        $this->usersTable = $usersTable;
    }

    public function indexAction()
    {

        $form = new SortListMessagesForm();
        $request = $this->getRequest();
        $params = $request->getPost();
        $form->setData($params);
        $sort = substr(key($params),0,-2);
        if (strlen($sort) == 0) {
            $sort = 'name_desc';
        }
        $orderBy = str_replace('_', ' ', $sort);
        $i = 0;
        $j = 0;
        $messages = $this->messagesTable->fetchAllOrderBy(str_replace('_', ' ', $orderBy));
        $blocksOfMessages = array();
        foreach ($messages as $message) {
            $blocksOfMessages[$i][$j] = $message;
            if ($j == 25) {
                $i += 1;
                $j = 0;
            }
            $j += 1;
        }

        return new ViewModel([
            'blocksOfMessages' => $blocksOfMessages,
            'form' => $form,
            'sort' => $sort,
        ]);
    }

    public function addAction()
    {
        $form  = new GuestbookForm();

        $request = $this->getRequest();
        if(!$request->isPost()){
            return ['form' => $form];
        }

        $userId=0;
        foreach($this->usersTable->fetchAll() as $user){
            if (!strcmp($user->name, $request->getPost()->name) && !strcmp($user->email, $request->getPost()->email)){
                $userId = $user->user_id;
            }
        }

        if ($userId == 0){
            $users = new Users();
            $form->setInputFilter($users->getInputFilter());
            $form->setData($request->getPost());
            if(!$form->isValid()){
                return ['form' => $form];
            }
            $users->exchangeArray($form->getData());
            $userId = $this->usersTable->saveUsers($users);
        }

        $message = new Messages();
        $form->setInputFilter($message->getInputFilter());

        /*
        $params = array_merge_recursive(
            $request->getPost()->toArray(),
            ['id_user' => $userId]
        );
        */

        $params = $request->getPost();
        $params->id_user = $userId;
        $params->text = $this->changeTags($params->text);

        $fileParams = $request->getFiles();
        // обработка изображения
        if ($fileParams->pictures['tmp_name'] != ''){
            $this->resizeAndMoveImage($fileParams->pictures['name'], $fileParams->pictures['tmp_name'], "public/img/upload/", 320, 240);
            $this->resizeAndMoveImage($fileParams->pictures['name'], $fileParams->pictures['tmp_name'], "public/img/upload/small/", 60, 50);
            unlink($fileParams->pictures['tmp_name']);
            $params->pictures = $fileParams->pictures['name'];
        } else {
            $params->pictures = '';
        }
        // обработка текстового файла
        if ($fileParams->filepath['tmp_name'] != ''){
            $result = move_uploaded_file($fileParams->filepath['tmp_name'], "public/files/upload/txt/".$fileParams->filepath['name']);
            $params->filepath = "upload/txt/".$fileParams->filepath['name'];
        } else {
            $params->filepath = '';
        }

        $form->setData($params);
        if(!$form->isValid()){
            return ['form' => $form];
        }

        $message->exchangeArray($form->getData());
        $this->messagesTable->saveMessages($message);
        return $this->redirect()->toRoute('guestbook');
    }

    protected function changeTags($text)
    {
        $bbcode = array("[strong]", "[strike]", "[italic]", "[code]", "[/strong]", "[/strike]", "[/italic]", "[/code]");
        $htmltag   = array("<strong>", "<strike>", "<i>", "<code>", "</strong>", "</strike>", "</i>", "</code>");
        $text = str_replace($bbcode, $htmltag, $text);
        $text = preg_replace_callback('/\[url=(.*)\](.*)\[\/url\]/Usi', function($match) {
            return '<a href="'.$match[1].'" target="_blank">'.(empty($match[2]) ? $match[1] : $match[2]).'</a>';
         }, $text);
         return $text;
    }

    protected function resizeAndMoveImage($filename, $tmpFilename, $path, $maxWidth, $maxHeight)
    {
        list($width, $height, $type) = getimagesize($tmpFilename);
        $size = getimagesize($tmpFilename);
        if (($width > $maxWidth) || ($height > $maxHeight)) {
            $indexWidth = $maxWidth / $width;
            $indexHeight = $maxHeight / $height;
            $newWidth = $indexWidth>$indexHeight ? $width*$indexHeight : $width*$indexWidth;
            $newHeight = $indexWidth>$indexHeight ? $height*$indexHeight : $height*$indexWidth;
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        switch ($type) {
            case 3:
                $image = imagecreatefrompng($tmpFilename);
                break;
            case 2:
                $image = imagecreatefromjpeg($tmpFilename);
                break;
            case 1:
                $image = imagecreatefromgif($tmpFilename);
                break;
            default:
        }
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        switch ($type) {
            case 3:
                imagepng($newImage, $path.$filename);
                break;
            case 2:
                imagejpeg($newImage, $path.$filename);
                break;
            case 1:
                imagegif($newImage, $path.$filename);
                break;
            default:
        }
    }
}
