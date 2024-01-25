<?php

class PwresetController extends Controller
{
    public function requestFormAction()
    {

        return $this->render(array(
            'email' => '', 
            '_token' => $this->generateCsrfToken('/pwreset/requestForm'),
        ));
    }

    public function requestFormFinAction()
    {
        $token = $this->request->getPost('_token');
        if (! isset($token) && ! $this->checkCsrfToken('/pwreset/requestForm', $token)) {

            return $this->redirect('/login/signin');

        }

        if (! $this->request->isPost()) {

            return $this->forwad404(); 

        }


        $email = $this->request->getPost('email');
        $errors = array();
        
        if (empty($email)) {
        
            $errors['email'] = sprintf(ERR_MSG_CONST_REQUIRED, 'メールアドレス');
        
        } elseif (! filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $errors['email'] = ERR_MSG_CONST_ADDRESS_FORMAT_CORRECTED;

        }

        if (count($errors) > 0) {

            return $this->render(array(
                'email' => $email,
                '_token' => $this->generateCsrfToken('/pwreset/requestForm'),
                'errors' => $errors,
            ), 'requestForm');
        }

        //emailを元にuserテーブルへ存在確認
        $user = $this->db_manager->get('User')->fetchByVar(array('email' => $email, 'is_delete' => 0));

        //メールアドレスが合致したユーザがいなくても送信完了画面を表示
        if(! isset($user)) {

            $this->_logger->error("user none");

            return $this->render();

        }

        $pwresetToken = bin2hex(random_bytes(32));
        $reset_url = RESET_URL . "?pwresetToken=$pwresetToken";

        //emailを元にpwresetテーブルへ存在確認、パスワードリセットフローか否かでテーブルへの処理を分ける
        $pwresetUser = $this->db_manager->get('Pwreset')->fetchByVar(array('email' => $email));

        //メール送信用変数格納配列
        $param = array(
            'email' => $email,
            'reset_url' => $reset_url,
        );

        try{

            $this->db_manager->get('Pwreset')->StartTransaction();

            if ($pwresetUser) {

                $stmt = $this->db_manager->get('Pwreset')->update($email, $pwresetToken);
                $this->_logger->info($this->getPreparedStmtAfterBind($stmt));

            } else {

                $stmt = $this->db_manager->get('Pwreset')->insert($email, $pwresetToken);
                $this->_logger->info($this->getPreparedStmtAfterBind($stmt));


            }

            if(! Util::sent_mail_utf8('Pwreset', $param)) {

                throw new Exception('メール送信に失敗しました');

            }

            $this->db_manager->get('Pwreset')->commitTransaction();

        } catch (Exception $e) {

            $this->db_manager->get('Pwreset')->backTransaction();

            $errors['error'] = $e->getMessage();
            $this->_logger->error("mail failed");


            return $this->render(array(
                'email' => '',
                '_token' => $this->generateCsrfToken('/pwreset/requestForm'),
                'errors' => $errors,
            ), 'requestForm');

        }
        
        return $this->render();
    }

    public function resetFormAction()
    {
        $errors = array();

        $pwresetToken = $this->request->getGet('pwresetToken');
        $pwresetUser = $this->db_manager->get('Pwreset')->fetchByVar(array('pwresetToken' => $pwresetToken));

        if (! $pwresetUser) {

            $errors['error'] = ERR_MSG_CONST_TOKEN_URL_CORRECTED;

            return $this->render(array(
                'email' => '',
                '_token' => $this->generateCsrfToken('/pwreset/requestForm'),
                'errors' => $errors,
            ), 'requestForm');

        }

        $yesterday = new Datetime('-1 day');
        $pwValidPeriod = $yesterday->format('Y-m-d H:i:s');

        if ($pwresetUser['create_at'] < $pwValidPeriod) {

            $errors['error'] = ERR_MSG_CONST_TOKEN_URL_EXPIRED;

            return $this->render(array(
                'email' => '',
                '_token' => $this->generateCsrfToken('/pwreset/requestForm'),
                'errors' => $errors,
            ), 'requestForm');

        }

        return $this->render(array(
            '_token' => $this->generateCsrfToken('/pwreset/resertForm'),
            'pwresetToken' => $pwresetToken,
            'new_password' => '',
            'check_password' => '',
        ));
    }

    public function resetFormFinAction()
    {
        $token = $this->request->getPost('_token');
        if (! isset($token) && !$this->checkCsrfToken('/pwreset/resetForm', $token)) {

            return $this->redirect('/pwreset/requestForm');

        }

        if (! $this->request->isPost()) {

            $this->forward404();

        }

        $errors = array();

        //URLよりGETパラメータ取得
        $pwresetToken = $this->request->getPost('pwresetToken');
        $new_password = $this->request->getPost('new_password');
        $check_password = $this->request->getPost('check_password');

        if (empty($new_password)) {

            $errors['new_password'] = sprintf(ERR_MSG_CONST_REQUIRED, 'パスワード');

        } elseif (mb_strlen($new_password) <= 7 || 31 <= mb_strlen($new_password)) {

            $errors['new_password'] = sprintf(ERR_MSG_CONST_PASSWORD_RENGTH, 'パスワード', MSG_CONST_PASSWORD_RENGTH_FROM, MSG_CONST_PASSWORD_RENGTH_TO);

        }

        if ( count($errors) > 0) {

            return $this->render(array(
                '_token' => $this->generateCsrfToken('/pwreset/resetForm'),
                'errors' => $errors,
                'new_password' => '',
                'check_password' => '',
            ), 'resetForm');
        }

        if ($new_password !== $check_password) {

            $errors['check_password'] = sprintf(ERR_MSG_CONST_CORRECTED, "確認用パスワード");

            return $this->render(array(
                '_token' => $this->generateCsrfToken('/pwreset/resetForm'),
                'errors' => $errors,
                'new_password' => '',
                'check_password' => '',
            ), 'resetForm');
        }

        $pwresetUser = $this->db_manager->get('Pwreset')->fetchByVar(array('pwresetToken' => $pwresetToken));

        try{
            //トランザクション開始
            $this->db_manager->get('User')->StartTransaction();
            
            //該当ユーザのパスワード更新
            $this->db_manager->get('User')->changePassword($new_password, $pwresetUser['email']);

            //pwresetテーブルから削除
            $this->db_manager->get('Pwreset')->delete($pwresetUser['email']);

            $this->db_manager->get('User')->commitTransaction();

        } catch(Exception $e) {

            //失敗したらロールバックする
            $this->db_manager->get('User')->backTransaction();

            $errors['errors'] = $e->getMessage();

            return $this->render(array(
                '_token' => $this->generateCsrfToken('pwreset/resetForm'),
                'errors' => $errors,
                'new_password' => '',
                'check_password' => '',
            ), 'resetForm');

        }

        return $this->render();
        
    }
}