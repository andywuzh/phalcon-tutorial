<?php

use Phalcon\Mvc\Controller;

class SignupController extends Controller
{
    public function indexAction() {

    }

    public function registerAction() {
        $user = new Users();

        $success = $user->save($this->request->getPost(), ['name', 'email']);
        if ($success) {
            echo 'Thanks for registering!';
        } else {
            echo 'Sorry, the following problems were generated: ';
            foreach ($user->getMessages() as $messages) {
                echo $messages->getMessage(), '<br />';
            }
        }

        $this->view->disable();
    }
}
