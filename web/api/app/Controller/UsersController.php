<?php

App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 */
class UsersController extends AppController {

    public $components = array('RequestHandler');

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $canView = $this->Session->Read('systemPermission');
        if ($canView =='None')
        {
                throw new UnauthorizedException(__('Insufficient Privileges'));
        }
        $options = '';
        $users = $this->User->find('all', $options);
        $this->set(array(
                'users' => $users,
                '_serialize' => array('users')
        ));
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        $canView = $this->Session->Read('systemPermission');
        if ($canView =='None')
        {
            throw new UnauthorizedException(__('Insufficient Privileges'));
        }
        $options = array('conditions' => array(
                                array('User.' . $this->User->primaryKey => $id), ''
                        )
                    );
        $user = $this->User->find('first', $options);
        $this->set(array(
            'user' => $user,
            '_serialize' => array('user')
        ));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {

            if ($this->Session->Read('systemPermission') != 'Edit')
            {
                throw new UnauthorizedException(__('Insufficient privileges'));
            }

            $this->User->create();
            if ($this->User->save($this->request->data)) {
                return $this->flash(__('The user has been saved.'), array('action' => 'index'));
            }
        }
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        $this->User->id = $id;

        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->Session->Read('systemPermission') != 'Edit')
        {
            throw new UnauthorizedException(__('Insufficient privileges'));
        }
        if ($this->User->save($this->request->data)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }

        $this->set(array(
            'message' => $message,
            '_serialize' => array('message')
        ));
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->Session->Read('systemPermission') != 'Edit')
        {
            throw new UnauthorizedException(__('Insufficient privileges'));
        }
        $this->request->allowMethod('post', 'delete');

        if ($this->User->delete()) {
            return $this->flash(__('The user has been deleted.'), array('action' => 'index'));
        } else {
            return $this->flash(__('The user could not be deleted. Please, try again.'), array('action' => 'index'));
        }
    }
}
?>