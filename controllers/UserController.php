<?php

class UserController extends Controller
{
    public function signupAction()
    {

        return $this->render(array(
            'name' => '',
            'email' =>'',
            'password' => '',
            '_token' => $this->generateCsrfToken('/user/signup'),
        ));

    }

    public function signupFinAction()
    {

        $token = $this->request->getPost('_token');
        if (! $this->checkCsrfToken('/user/signup', $token)) {

            return $this->redirect('/user/signup');

        }

        if (! $this->request->isPost()) {

            $this->forward404();

        }

        if ($this->request->getPost('submit')) {

            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $is_delete = 0;

            $errors = array();

            if (empty($name)) {

                $errors['name'] = sprintf(ERR_MSG_CONST_REQUIRED, '名前');

            } elseif (15 <= mb_strlen($name)) {

                $errors['name'] = sprintf(ERR_MSG_CONST_RENGTH, '名前', MSG_CONST_NAME_RENGTH);

            }

            if (empty($email)) {

                $errors['email'] = sprintf(ERR_MSG_CONST_REQUIRED, 'メールアドレス');

            } elseif (! filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $errors['email'] = sprintf(ERR_MSG_CONST_ADDRESS_FORMAT_CORRECTED);

            }

            if (empty($password)) {

                $errors['password'] = sprintf(ERR_MSG_CONST_REQUIRED, 'パスワード');

            } elseif (mb_strlen($password) <= 7 || 31 <= mb_strlen($password)) {

                $errors['password'] = sprintf(ERR_MSG_CONST_PASSWORD_RENGTH, 'パスワード', MSG_CONST_PASSWORD_RENGTH_FROM, MSG_CONST_PASSWORD_RENGTH_TO);

            }

            // この時点でエラーがあった場合は、ここではねる
            if (count($errors) > 0) {

                return $this->render(array(
                    'name' => 'hoge',
                    'email' => '',
                    'password' => '',
                    'errors' => $errors,
                    '_token' => $this->generateCsrfToken('/user/signup'),
                ), 'signup');

            }

            if ($this->db_manager->get('User')->fetchByVar(array('name' => $name))) {

                $errors['name'] = sprintf(ERR_MSG_CONST_REGISTERD, '名前');

            }

            if($this->db_manager->get('User')->fetchByVar(array('email' => $email))){

                $errors['email'] = sprintf(ERR_MSG_CONST_REGISTERD, 'メールアドレス');

            }

            
            if(count($errors) > 0){

                return $this->render(array(
                        'name' => '',
                        'email' => '',
                        'password' => '',
                        'errors' => $errors,
                        '_token' => $this->generateCsrfToken('/user/signup'),
                ), 'signup');

            }

            $stmt = $this->db_manager->get('User')->insert($name, $email, $password, $is_delete);
            $this->_logger->info($this->getPreparedStmtAfterBind($stmt));
            return $this->render(array());
        }
    }
}