<?php


class Util {

    /**
     * 画像を圧縮処理
     * @param string file_full_path
     * @param string use (article or icon 使用用途)
     * @return string compressd_file_path or false 正常圧縮した場合、圧縮した画像ファイルのパスを返す
     */
    public static function compress_and_resize_file($fullpath, $use)
    {
        list($width, $height,$type) = getimagesize($fullpath);

        $upload_dir = '/tmp/';
        $tmp_file_path = $upload_dir . uniqid('uploaded_image_', true);

        if (move_uploaded_file($fullpath, $tmp_file_path)) {

            switch ($type) {
                case IMAGETYPE_JPEG:
                    $original_image = imagecreatefromjpeg($tmp_file_path);
                    break;
                case IMAGETYPE_PNG:
                    $original_image = imagecreatefrompng($tmp_file_path);
                    break;
                default:
                    return false;
            }

            //使用用途別でリサイズ処理
            if ($use === "article") {

                $new_width = 320;
                $new_height = round($new_width * ($height / $width));
                $new_image = imagecreatetruecolor($new_width, $new_height);

                $is_success = imagecopyresampled($new_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                if (! $is_success) {

                    return false;

                }
            } elseif ( $use === "icon") {

                $new_width = 100;
                $new_height = round($new_width * ($height / $width));
                $new_image = imagecreatetruecolor($new_width, $new_height);

                $is_success = imagecopyresampled($new_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                if (! $is_success) {

                    return false;

                }
            }

            $compressd_file_path = UPLOAD_DIR_FOR_TMP . bin2hex(random_bytes(10)) . ".png";

            //画像タイプ別にファイル圧縮
            switch ($type) {
                case IMAGETYPE_JPEG:
                    imagejpeg($new_image, $compressd_file_path, 50);
                    return $compressd_file_path;
                    break;
                case IMAGETYPE_PNG:
                    imagepng($new_image, $compressd_file_path, 5);
                    return $compressd_file_path;
                    break;
                default:
                    return false;
            }
        } else {
            return false;
        }

    }


    /**
     * 
     * 画像をファイルサーバにアップロードした後、リネームしてあらかじめ定めたディレクトリ配下に移動させる
     * @param string $tmp_file_name 一時ファイル名($_FILES['image']['tmp_name'])
     * @param string $file_name ファイル名($_FILES['image']['name']) 
     * @param string $login_user_id $_SESSIONから取得してきたuserのid
     * @param string $file_dir アップロード先のディレクトリ
     * @return boorean 結果
     */

    public static function upload_file($tmp_file_name, $file_name, $login_user_id, $file_dir, $parm = 0644)
    {

        //ファイル名文字化け対策
        setlocale(LC_ALL, 'ja_JP.UTF-8');

        //アップロードファイルの拡張子取得
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);

        //JPG形式かPNG形式のみ処理する
        if ($ext == "jpg" OR $ext == "jpeg" OR $ext == "png") {

            //ファイル名リネーム
            $upload_file_name = date("YmdHis") . $login_user_id . mt_rand() . "." . $ext;
            
            //ファイル移動
            $is_move = rename($tmp_file_name, $file_dir . $upload_file_name);

            if ($is_move) {

                chmod($file_dir . "/" . $upload_file_name, $parm);

            } else {

                return "hoge";

            }
        } else {

                return "hoga";

        }

        return array(
            'bool' => true, 
            'upload_file_name' =>$upload_file_name,
        );
    }

    /**
     * メール送信用
     * @param string $about Admin宛かUser宛かPW変更か
     * @param string $email
     * @param string $content
     */

    public static function sent_mail_utf8($who, $param = array())
    {

        //文字化け対策
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");
        
        switch ($who){

            case 'Admin':

                $to_admin_mail_body = <<< EOF
                下記内容で問い合わせが来ています。
                対応してください。

                ===========================================================
                [ 問い合わせID ]
                {$param['qa_id']}

                [ メールアドレス ]
                {$param['email']}

                [ 件名 ]
                {$param['title']}

                [ 問い合わせ種別 ]
                {$param['qa_type']}

                [ 内容 ]
                {$param['content']}

                ===========================================================
                EOF;

                $is_sent_to_admin = mb_send_mail(MY_EMAIL_ADDRESS, TO_ADMIN_CONTACT_SUBJECT, $to_admin_mail_body);

                return $is_sent_to_admin;
                break;

            case 'User':

                $to_user_mail_body = <<< EOF
                下記内容でお問い合わせを承りました。
                返信は2～3営業日以内にいたします。

                ===========================================================
                [ メールアドレス ]
                {$param['email']}

                [ 件名 ]
                {$param['title']}

                [ 問い合わせ種別 ]
                {$param['qa_type']}

                [ 内容 ]
                {$param['content']}

                ===========================================================
                EOF;

                $is_sent_to_user = mb_send_mail($param['email'], TO_USER_CONTACT_SUBJECT, $to_user_mail_body);

                return $is_sent_to_user;
                break;

            case 'Pwreset':

                $to_reseter_mail_body = <<< EOF
                24時間以内に下記URLへアクセスして、パスワード再設定をお願いします。
                {$param['reset_url']}
                EOF;

                $is_sent_to_reseter = mb_send_mail($param['email'], TO_RESETER_CONTACT_SUBJECT, $to_reseter_mail_body);

                return $is_sent_to_reseter;
                break;

        
        }


    }
}
