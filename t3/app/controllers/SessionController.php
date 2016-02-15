<?php

use Phalcon\Mvc\Controller;

class SessionController extends Controller
{
    public function indexAction() {
        
    }

    public function startAction() {
        if ($this->request->isPost()) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            /** @var Users $user */
            $user = Users::findFirst(['(email = :email: OR name = :email:)', 'bind' => ['email' => $email, 'password' => sha1($password)]]);
            if ($user != false) {
                $this->_registerSession($user);
                $this->flash->success('Welcome ' . $user->name);

                $this->dispatcher->forward(['controller' => 'invoices', 'action' => 'index']);
            }
        }

        $this->dispatcher->forward(['controller' => 'session', 'action' => 'index']);
    }
    
    private function _registerSession($user) {
        $this->session->set('auth', ['id' => $user->id, 'name' => $user->name]);
    }
}
