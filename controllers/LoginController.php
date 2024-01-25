<?php

class LoginController extends Controller
{

    public function signinAction()
    {
        if ($this->session->isAuthenticated()) {

            return $this->redirect('/post/list');
        
        }
        
        $this->_logger->info("login");

        return $this->render(array(
            'email' => '', 
            'password' => '',
            '_token' => $this->generateCsrfToken('/login/signin'),
        ));
    }

    public function authenticateAction()
    {
        if ($this->session->isAuthenticated()) {
        
            return $this->redirect('/post/list');
        
        }

        if (! $this->request->isPost()) {
        
            $this->forward404();
        
        }

        $token = $this->request->getPost('_token');
        if (! $this->checkCsrfToken('/login/signin', $token)) {
        
            return $this->redirect('/login/signin');
        
        }

        //POSTされた値の取得
        $email = $this->request->getPost('email'); 
        $password = $this->request->getPost('password');

        //エラー配列の初期化 
        $errors = array();

        //メールアドレス未入力チェック
        if (empty($email)) { 
        
            $errors['email'] = sprintf(ERR_MSG_CONST_REQUIRED, 'メールアドレス'); 
        
        } elseif (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        
            $errors['email'] = sprintf(ERR_MSG_CONST_ADDRESS_FORMAT_CORRECTED);
        
        }

        //パスワード未入力チェック
        if (empty($password)) {
        
            $errors['password'] = sprintf(ERR_MSG_CONST_REQUIRED, 'パスワード');
        
        }

        //if文のネスト化を防ぐためにこの時点で判別
        if (count($errors) > 0) {
        
            $this->_logger->info('Login error');
            
            return $this->render(array(
                'email' => $email,
                'password' => '',
                '_token' => $this->generateCsrfToken('/login/signin'),
                'errors' => $errors
                ), 'signin');
        
        }

        //メールアドレスを引数に、DBにアクセスしてユーザ情報を取得
        $user = $this->db_manager->get('User')->fetchByVar(array('email' => $email, 'is_delete' => "0"));


        $hash_password = $this->hashPassword($password);

        if ($user) {

            //メールアドレス合致チェック
            if ($user['email'] !== $email) {
        
                $errors['email'] = sprintf(ERR_MSG_CONST_CORRECTED, 'メールアドレス');
        
            }

            //パスワード合致チェック
            if ($user['password'] !== $hash_password) {
        
                $errors['password'] = sprintf(ERR_MSG_CONST_CORRECTED, 'パスワード');
        
            }

        } else {
        
            $errors['user'] = sprintf(ERR_MSG_CONST_VALIDATED);
        
        }

        if ( count($errors) == 0) {

            $this->session->setAuthenticated(true);
            $this->session->set('user', $user);

            $this->_logger->info('Login!!!');
            
            return $this->redirect('/post/list');
        }

        
        $this->_logger->error("login failed");

            
        return $this->render(array(
            'email' => $email,
            'password' => '',
            'errors' => $errors,
            '_token' => $this->generateCsrfToken('/login/signin'),
            ), 'signin');
    }

    public function signoutAction()
    {
        $this->session->clear();
        $this->session->setAuthenticated(false);

        return $this->redirect('/login/signin');
        
    }
}