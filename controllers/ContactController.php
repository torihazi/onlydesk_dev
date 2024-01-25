<?php 

class ContactController extends Controller
{
    public function qaFormAction()
    {   
        return $this->render(array(
            'email' => '',
            'title' => '',
            'qa_type' => 0,
            '_token' => $this->generateCsrfToken('/contact/qaForm'),
        ));
    }
    
    public function qaFormCheckAction()
    {

        // $token = $this->request->getPost('_token');
        // if (! $this->checkCsrfToken('/contact/qaForm', $token)) {
            
        //     $this->redirect('/login/signin');
        
        // }

        if (! $this->request->isPost()) {

            $this->forward404();

        }

        $submit_value= $this->request->getPost('submit');

        if ( $submit_value === "check") {

            $errors = array();
            $email = $this->request->getPost('email');
            $title = $this->request->getPost('title');
            $qa_type = $this->request->getPost('qa_type');
            $content = $this->request->getPost('content');

            if (empty($email)) {

                $errors['email'] = sprintf(ERR_MSG_CONST_REQUIRED, 'メールアドレス');
            
            } elseif (! filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $errors['email'] = sprintf(ERR_MSG_CONST_ADDRESS_FORMAT_CORRECTED);

            }

            if (empty($title)) {

                $errors['title'] = sprintf(ERR_MSG_CONST_REQUIRED, '件名');
            
            }

            if (empty($qa_type)) {

                $errors['qa_type'] = sprintf(ERR_MSG_CONST_REQUIRED, '種別の選択');
            
            }

            if (empty($content)) {

                $errors['content'] = sprintf(ERR_MSG_CONST_REQUIRED, '本文');

            }

            if (count($errors) > 0) {
                
                return $this->render(array(
                    'email' => $email,
                    'title' => $title,
                    'qa_type' => $qa_type,
                    'content' => $content,
                    'errors' => $errors,
                    '_token' => $this->generateCsrfToken('/contact/qaForm'),
                ), 'qaForm');
            }
        }

        return $this->render(array(
            'email' => $email,
            'title' => $title,
            'qa_type' => $qa_type,
            'content' => $content,
            '_token' => $this->generateCsrfToken('/contact/qaFormCheck'),
        ));

    }

    public function qaFormFinAction()
    {
        // $token = $this->request->getPost('_token');
        // if (! $this->checkCsrfToken('/contact/qaFormCheck', $token)) {
            
        //     $this->redirect('/login/signin');
        
        // }

        if (! $this->request->isPost()) {

            $this->forward404();

        }

        $submit_value= $this->request->getPost('submit');

        if ($submit_value === 'check') {
            
            $email = $this->request->getPost('email');
            $title = $this->request->getPost('title');
            $qa_type = $this->request->getPost('qa_type');
            $content = $this->request->getPost('content');
            
            return $this->render(array(
                'email' => $email,
                'title' => $title,
                'qa_type' => $qa_type,
                'content' => $content,
                '_token' => $this->generateCsrfToken('/contact/qaForm'),
            ), 'qaForm');

        }

        if ($submit_value = 'fin') {

            $email = $this->request->getPost('email');
            $title = $this->request->getPost('title');
            $qa_type = $this->request->getPost('qa_type');
            $content = $this->request->getPost('content');

            try {

                $this->db_manager->get('Contact')->StartTransaction();

                //contactテーブルへ挿入
                $qa_id = $this->db_manager->get('Contact')->insert($email, $title, $qa_type, $content);

                //メール送信用変数格納配列
                $param = array(
                    'email' => $email,
                    'title' => $title,
                    'qa_type' => QA_TYPE[$qa_type],
                    'content' => $content,
                    'qa_id' => $qa_id,
                );

                if(isset($qa_id)){

                    //管理者へのメール送信
                    if (! Util::sent_mail_utf8('Admin', $param)) {

                        throw new Exception("メールの送信に失敗しました");

                    }
                    
                    //ユーザ側へ対応受付の旨を自動返信
                    if (! Util::sent_mail_utf8('User', $param)) {

                        throw new Exception("メールの送信に失敗しました");

                    }

                    $this->db_manager->get('Contact')->CommitTransaction();

                    $this->_logger->info("mail send");

                    return $this->render();

                } else {

                    throw new Exception("重大なエラーが発生しました。");

                }
            } catch (Excetion $e) {

                $errors = array();
                $errors['error'] = $e->getMessage();

                $this->db_manager->get('Contact')->backTransaction();

                $this->_logger->error("mail not send");


                return $this->render(array(
                    'email' => $email,
                    'title' => $title,
                    'qa_type' => $qa_type,
                    'content'=> $content,
                    'errors' => $errors,
                ), 'qaForm');
            }
        }
    }
}