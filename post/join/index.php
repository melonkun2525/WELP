<?php
session_start();
require('../db/dbconnect.php');

// index.html内のキーワードをまとめた配列
$keywords = [
    'post_name' => '',
    'post_email' => '',
    'post_password' => '',
    'err_name' => '',
    'err_email' => '',
    'err_password' => '',
    'err_password_length' => '',
    'err_image' => '',
    'err_image_type' => ''
];

// エラーが発生したかどうか判定するフラグ
$error = false;
$duplicatecheck = false;

//書き直し
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'rewrite') {
    $_POST = $_SESSION['join'];
    $error = true;
}

if (!empty($_POST)) {

    //エラー項目の確認
    if ($_POST['name'] == '') {
        $keywords['err_name'] = 'ニックネームを入力してください';
        $error = true;
    }else{
        $keywords['post_name'] = $_POST['name'];
    }

    if ($_POST['email'] == '') {
        $keywords['err_email'] = 'メールアドレスを入力してください';
        $error = true;
    }else{
        $keywords['post_email'] = $_POST['email'];
    }

    if (strlen($_POST['password']) < 4) {
        $keywords['err_password_length'] = 'パスワードは４文字以上で入力してください';
        $error = true;
    }else{
        $keywords['post_password'] = $_POST['password'];
    }

    if ($_POST['password'] == '') {
        $keywords['err_password'] = 'パスワードを入力してください';
        $error = true;
    }else{
        $keywords['post_password'] = $_POST['password'];
    }

    if(isset($_FILES['image']['name'])){
        $fileName = $_FILES['image']['name'];
    }

    if (!empty($fileName)) {
        $ext = substr($fileName , -3);
        if ($ext != 'jpg' && $ext != 'gif') {
            $keywords['err_image_type'] = '* 写真などは「.gif」または「.jpg」の画像を設定してください';
            $error = true;
        }
    }

    if (!$error) {
        $member = $db->prepare ('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
        $member -> execute(array($_POST['email']));
        $record = $member->fetch();
        if($record['cnt'] > 0) {
            $keywords['err_email'] = '指定されたメールアドレスはすでに登録されています';
            $duplicatecheck = true;
        }
    }

    // $error != true の省略
    if (!$error && !$duplicatecheck) {
        //画像をアップロードする
        $image = date('YmdHis') . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'] , '../ member_picture/'. $image);

        $_SESSION['join'] = $_POST;
        $_SESSION['join']['image'] = $image;
        header('Location: check.php');
        exit();
    }else{
        $keywords['err_image'] = '* 恐れ入りますが、画像を改めて指定してください';
    }
}

// index.htmlのファイルを文字列として取得
$html = file_get_contents('./index.html');

// index.html内のキーワード( {{}}で囲まれてるやつ )をそれぞれの変数の中身と置き換える
foreach($keywords as $key => $value) {
    $html = str_replace('{{' . $key . '}}', htmlspecialchars($value, ENT_QUOTES), $html);
}

// 中身を置き換え終わったファイルを出力する
print($html);