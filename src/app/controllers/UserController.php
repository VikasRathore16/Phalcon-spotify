<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;


class UserController extends Controller
{
    public function loginAction()
    {
        if ($this->request->isPost()) {

            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $checkemail = Users::findByEmail($email);
            $user = Users::query()
                ->where("email = '$email'")
                ->andWhere("password = '$password'")
                ->execute();
            if (count($checkemail) == 0) {
                $this->view->msg = 'User does not exist . Please Sign Up';
            }

            if (count($user) > 0) {
                $this->session->set('user_id', $user[0]->user_id);
                $this->response->redirect('spotify/index');
            }
        }
    }
    public function logoutAction()
    {
    }
    public function signupAction()
    {
        $user = new Users();
        $request = new Request();
        if ($request->has('email')) {
            $checkemail = Users::findByEmail($this->request->getPost('email'));
            if (count($checkemail) > 0) {
                $this->view->message = "User Exists! Please use Another email address";
            }
            $escape = new \App\Components\Myescaper();
            $data = $request->get();
            $postdata = $escape->santize($data);

            $user->assign(
                $postdata,
                [
                    'username',
                    'email',
                    'password'
                ]
            );

            if (count($checkemail) == 0) {
                $success = $user->save();
                $this->view->success = $success;

                if ($success) {
                    $this->response->redirect('user/login');
                } else {
                    $this->view->message = "Not Register succesfully due to following reason: <br>" . implode("<br>", $user->getMessages());
                }
            }
        }
    }
    public function dashboardAction()
    {
    }
}
