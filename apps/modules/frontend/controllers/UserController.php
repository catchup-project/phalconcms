<?php

namespace Multiple\Frontend\Controllers;
use Multiple\Components\Base;
use Multiple\Models\User;
use Phalcon\Http\Response;

class UserController extends Base
{

    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction($id=0){

        //$query   = $this->modelsManager->createQuery();
        $queryBuilder   = $this->modelsManager->createBuilder()->from(array('a'=>'Multiple\Models\Products'))->leftJoin('Multiple\Models\User','a.user_id=b.id','b');
        if($id){
            $queryBuilder->where('b.id =1');
        }else{
            $queryBuilder->where('b.id =1');
        }

        //->where('name LIKE :name:', array('name' => '%' . $name . '%'));

        $totalBuilder = clone $queryBuilder;
        $totalBuilder->columns("count(*) as counts");

        $queryBuilder->columns(array('bid'=>'b.id','name'=>'a.name'));
        //
        $data = $queryBuilder->limit(100)->offset(10)->orderBy('a.id DESC')->getQuery()->execute();

        $total = $totalBuilder->getQuery()->execute()->toArray();

        foreach($data as $row){
            echo $row->name."<br/>";
        }

        var_dump($total);

        die();

        $this->persistent->conditions = null;
        $this->view->form = new UsersForm();

    }


    /**
     * Confirms an e-mail, if the user must change thier password then changes it
     */
    public function confirmEmailAction()
    {
        $code = $this->dispatcher->getParam('code');

        $confirmation = EmailConfirmations::findFirstByCode($code);

        if (!$confirmation) {
            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }

        if ($confirmation->confirmed != 'N') {
            return $this->dispatcher->forward(array(
                'controller' => 'session',
                'action' => 'login'
            ));
        }

        $confirmation->confirmed = 'Y';

        $confirmation->user->active = 'Y';

        /**
         * Change the confirmation to 'confirmed' and update the user to 'active'
         */
        if (!$confirmation->save()) {

            foreach ($confirmation->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }

        /**
         * Identify the user in the application
         */
        $this->auth->authUserById($confirmation->user->id);

        /**
         * Check if the user must change his/her password
         */
        if ($confirmation->user->mustChangePassword == 'Y') {

            $this->flash->success('The email was successfully confirmed. Now you must change your password');

            return $this->dispatcher->forward(array(
                'controller' => 'users',
                'action' => 'changePassword'
            ));
        }

        $this->flash->success('The email was successfully confirmed');

        return $this->dispatcher->forward(array(
            'controller' => 'users',
            'action' => 'index'
        ));
    }

    public function resetPasswordAction()
    {
        $code = $this->dispatcher->getParam('code');

        $resetPassword = ResetPasswords::findFirstByCode($code);

        if (!$resetPassword) {
            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }

        if ($resetPassword->reset != 'N') {
            return $this->dispatcher->forward(array(
                'controller' => 'session',
                'action' => 'login'
            ));
        }

        $resetPassword->reset = 'Y';

        /**
         * Change the confirmation to 'reset'
         */
        if (!$resetPassword->save()) {

            foreach ($resetPassword->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }

        /**
         * Identify the user in the application
         */
        $this->auth->authUserById($resetPassword->usersId);

        $this->flash->success('Please reset your password');

        return $this->dispatcher->forward(array(
            'controller' => 'users',
            'action' => 'changePassword'
        ));
    }

    /**
     * Allow a user to signup to the system
     */
    public function signupAction()
    {
        $form = new SignUpForm();

        if ($this->request->isPost()) {

            if ($form->isValid($this->request->getPost()) != false) {

                $user = new Users();

                $user->assign(array(
                    'name' => $this->request->getPost('name', 'striptags'),
                    'email' => $this->request->getPost('email'),
                    'password' => $this->security->hash($this->request->getPost('password')),
                    'profilesId' => 2
                ));

                if ($user->save()) {
                    return $this->dispatcher->forward(array(
                        'controller' => 'index',
                        'action' => 'index'
                    ));
                }

                $this->flash->error($user->getMessages());
            }
        }

        $this->view->form = $form;
    }

    /**
     * Starts a session in the admin backend
     */
    public function loginAction()
    {
        $form = new LoginForm();

        try {

            if (!$this->request->isPost()) {

                if ($this->auth->hasRememberMe()) {
                    return $this->auth->loginWithRememberMe();
                }
            } else {

                if ($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {

                    $this->auth->check(array(
                        'email' => $this->request->getPost('email'),
                        'password' => $this->request->getPost('password'),
                        'remember' => $this->request->getPost('remember')
                    ));

                    return $this->response->redirect('users');
                }
            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }

        $this->view->form = $form;
    }

    /**
     * Shows the forgot password form
     */
    public function forgotPasswordAction()
    {
        $form = new ForgotPasswordForm();

        if ($this->request->isPost()) {

            if ($form->isValid($this->request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {

                $user = Users::findFirstByEmail($this->request->getPost('email'));
                if (!$user) {
                    $this->flash->success('There is no account associated to this email');
                } else {

                    $resetPassword = new ResetPasswords();
                    $resetPassword->usersId = $user->id;
                    if ($resetPassword->save()) {
                        $this->flash->success('Success! Please check your messages for an email reset password');
                    } else {
                        foreach ($resetPassword->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                    }
                }
            }
        }

        $this->view->form = $form;
    }

    /**
     * Closes the session
     */
    public function logoutAction()
    {
        $this->auth->remove();

        return $this->response->redirect('index');
    }


    /**
     * Searches for users
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Vokuro\Models\Users', $this->request->getPost());
            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = array();
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }

        $users = Users::find($parameters);
        if (count($users) == 0) {
            $this->flash->notice("The search did not find any users");
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $users,
            "limit" => 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Creates a User
     */
    public function createAction()
    {
        if ($this->request->isPost()) {

            $user = new Users();

            $user->assign(array(
                'name' => $this->request->getPost('name', 'striptags'),
                'profilesId' => $this->request->getPost('profilesId', 'int'),
                'email' => $this->request->getPost('email', 'email')
            ));

            if (!$user->save()) {
                $this->flash->error($user->getMessages());
            } else {

                $this->flash->success("User was created successfully");

                Tag::resetInput();
            }
        }

        $this->view->form = new UsersForm(null);
    }

    /**
     * Saves the user from the 'edit' action
     */
    public function editAction($id)
    {
        $user = Users::findFirstById($id);
        if (!$user) {
            $this->flash->error("User was not found");
            return $this->dispatcher->forward(array(
                'action' => 'index'
            ));
        }

        if ($this->request->isPost()) {

            $user->assign(array(
                'name' => $this->request->getPost('name', 'striptags'),
                'profilesId' => $this->request->getPost('profilesId', 'int'),
                'email' => $this->request->getPost('email', 'email'),
                'banned' => $this->request->getPost('banned'),
                'suspended' => $this->request->getPost('suspended'),
                'active' => $this->request->getPost('active')
            ));

            if (!$user->save()) {
                $this->flash->error($user->getMessages());
            } else {

                $this->flash->success("User was updated successfully");

                Tag::resetInput();
            }
        }

        $this->view->user = $user;

        $this->view->form = new UsersForm($user, array(
            'edit' => true
        ));
    }

    /**
     * Deletes a User
     *
     * @param int $id
     */
    public function deleteAction($id)
    {
        $user = Users::findFirstById($id);
        if (!$user) {
            $this->flash->error("User was not found");
            return $this->dispatcher->forward(array(
                'action' => 'index'
            ));
        }

        if (!$user->delete()) {
            $this->flash->error($user->getMessages());
        } else {
            $this->flash->success("User was deleted");
        }

        return $this->dispatcher->forward(array(
            'action' => 'index'
        ));
    }

    /**
     * Users must use this action to change its password
     */
    public function changePasswordAction()
    {
        $form = new ChangePasswordForm();

        if ($this->request->isPost()) {

            if (!$form->isValid($this->request->getPost())) {

                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {

                $user = $this->auth->getUser();

                $user->password = $this->security->hash($this->request->getPost('password'));
                $user->mustChangePassword = 'N';

                $passwordChange = new PasswordChanges();
                $passwordChange->user = $user;
                $passwordChange->ipAddress = $this->request->getClientAddress();
                $passwordChange->userAgent = $this->request->getUserAgent();

                if (!$passwordChange->save()) {
                    $this->flash->error($passwordChange->getMessages());
                } else {

                    $this->flash->success('Your password was successfully changed');

                    Tag::resetInput();
                }
            }
        }

        $this->view->form = $form;
    }
}