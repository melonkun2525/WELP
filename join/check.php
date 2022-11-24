<?php

require_once('../init.php'); //最初に読み込む必須ファイルを追加。

//session_start(); //init.phpに記載済みなので消去。
//require('../db/dbconnect.php'); //init.phpに記載済みなので消去。

if(!isset($_SESSION['join'])) {
    header('Location: index.php');
    exit();
}

if (!empty($_POST)) {

    //databaseクラスを使うため記述変更。

    /*
    $statement = $db->prepare('INSERT INTO users SET name=?, email=?,password=?, picture=?, created_at=NOW()');
    echo $ret = $statement -> execute(array(
        $_SESSION['join']['name'],
        $_SESSION['join']['email'],
        password_hash($_SESSION['join']['password'] , PASSWORD_BCRYPT),
        $_SESSION['join']['img_name']
    ));
    */

    $db = new database();
    $db->setSQL('INSERT INTO `users` SET `name`=:name, `email`=:email, `password`=:password, `picture`=:picture;');
    $db->setBindArray([
        'name' => $_SESSION['join']['name'],
        'email' => $_SESSION['join']['email'],
        'password' => password_hash($_SESSION['join']['password'], PASSWORD_BCRYPT),
        'picture' => $_SESSION['join']['img_name']
    ]);
    $db->execute();

    //$_FILES = $_SESSION["image"];

    //move_uploaded_file($_FILES["image"]["tmp_name"], '../member_picture/' . $_SESSION['join']['img_name']);

    unset($_SESSION['join']);

    header('Location: thanks.php');
    exit();
}


?>

<form action="" method="post">
    <input type="hidden" name="action" value="submit" />
    <dl>
        <dt>ニックネーム></dt>
        <dd>
        <?php echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES); ?>
        </dd>
        <dt>メールアドレス</dt>
        <dd>
        <?php echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES); ?>
        </dd>
        <dt>パスワード</dt>
        <dd>
            【表示されません】
        </dd>
        <dt>写真など</dt>
        <dd>
        <?php echo '<img src="data:' . $_SESSION['join']['path'] . ';base64,'.$_SESSION['join']['path_img'].'" width="100" heifht="100" alt="" >' ?>
        </dd>
    </dl>
    <div><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> |   <input type="submit" value="登録する" /></div>
</form>

<?php 
//var_dump($_SESSION['join']['image']);
?>
