<?php

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Acl\Adapter\Memory as AclList;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;

class SecurityPlugin extends Plugin
{
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher) {
        $auth = $this->session->get('auth');
        $role = $auth ? 'Users' : 'Guests';

        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        /** @var AclList $acl */
        $acl = $this->getAcl();

        $allowed = $acl->isAllowed($role, $controller, $action);
        if ($allowed != Acl::ALLOW) {
            $this->flash->error('You do not have access to this module');
            $dispatcher->forward(['controller' => 'index', 'action' => 'index']);

            return false;
        }
    }

    public function getAcl() {
        if (!isset($this->persistent->acl)) {
            $acl = new AclList();

            $acl->setDefaultAction(Acl::DENY);

            $roles = ['users' => new Role('Users'), 'guests' => new Role('Guests')];
            foreach ($roles as $role) {
                $acl->addRole($role);
            }

            $privateResources = [
                'companies' => ['index', 'search', 'new', 'edit', 'save', 'create', 'delete'],
                'products' => ['index', 'search', 'new', 'edit', 'save', 'create', 'delete'],
                'producttypes' => ['index', 'search', 'new', 'edit', 'save', 'create', 'delete'],
                'invoices' => ['index', 'profile'],
            ];
            foreach ($privateResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }

            $publicResources = [
                'index' => ['index'],
                'about' => ['index'],
                'register' => ['index'],
                'errors' => ['show401', 'show404', 'show500'],
                'session' => ['index', 'register', 'start', 'end'],
                'contact' => ['index', 'send'],
            ];
            foreach ($publicResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }

            /** @var Role $role */
            foreach ($roles as $role) {
                foreach ($publicResources as $resource => $actions) {
                    foreach ($actions as $action) {
                        $acl->allow($role->getName(), $resource, $action);
                    }
                }
            }

            foreach ($privateResources as $resource => $actions) {
                foreach ($actions as $action) {
                    $acl->allow('Users', $resource, $action);
                }
            }

            $this->persistent->acl = $acl;
        }

        return $this->persistent->acl;
    }
}