<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1(3)</title>
</head>
<body>
    <form action="" method="post">
<?php
function countelement($pdo)
{
    //DBのレコード数を返す
    $results=0;
    $sql = "SELECT * FROM tbtest4";
    $stmt = $pdo->query($sql);
    $results = $stmt->rowCount();
    return $results;
}
function selectitem($pdo,$id,$index)
{
    //指定されたIDのindex=1:名前 =2:コメント =4:パスワードを返す
    $sql = 'select * from tbtest4 where id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch();
	return $data[$index];
}
function deleteelement($pdo,$id)
{
    //指定されたIDのレコードを削除
    $sql = 'delete from tbtest4 where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}
function updateelement($pdo,$id,$name,$comment,$passwd)
{
    //指定されたIDのレコードを更新
    $timestp = date("Y/m/d H:i:s");
    $sql = 'UPDATE tbtest4 SET name=:name,comment=:comment,timestp=:timestp,passwd=:passwd WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':timestp', $timestp, PDO::PARAM_STR);
    $stmt->bindParam(':passwd', $passwd, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}
function insertelement($pdo,$id,$name,$comment,$passwd)
{
    //レコードを追加
    //echo "INSERT".$id;
    $timestp = date("Y/m/d H:i:s");
    $sql = 'INSERT INTO tbtest4 (id, name, comment, timestp, passwd) '
    .'VALUES (:id, :name, :comment, :timestp, :passwd)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':timestp', $timestp, PDO::PARAM_STR);
    $stmt->bindParam(':passwd', $passwd, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}
function selectall($pdo)
{
    //表示
    $sql = 'SELECT * FROM tbtest4';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].'  '.$row['name'].'  '.$row['comment'].'  '.$row['timestp'].'  '.$row['passwd'].'<br><hr>';
    }
}
function selectlastid($pdo)
{
    //最後のレコードのIDを返す
    $sql = 'SELECT * FROM tbtest4';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row)
    {
        //最後の行までから回し
    }
    return $row['id'];
}
    // 【サンプル】
    // ・データベース名：tb230666db
    // ・ユーザー名：tb-230666
    // ・パスワード：U9r25xMKnZ
    // の学生の場合：

    // DB接続設定
    $dsn = 'mysql:dbname=tb230666db;host=localhost';
    $user = 'tb-230666';
    $password = 'U9r25xMKnZ';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //テーブル作成
    //投稿ID:id　名前:name　コメント:comment　日付:datetime　パスワード:passwd
    $sql = "CREATE TABLE IF NOT EXISTS tbtest4"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "timestp DATETIME,"
    . "passwd char(16)"
    .");";
    $stmt = $pdo->query($sql);

  //デバッグ用
    /*if(!empty($_POST["edit"]))
        echo "edit ".$_POST["edit"]."<br>";
    if(!empty($_POST["delete"]))
        echo "delete ".$_POST["delete"]."<br>";
    if(!empty($_POST["title"]))
        echo "name ".$_POST["title"]."<br>";
    if(!empty($_POST["comment"]))
        echo "comment ".$_POST["comment"]."<br>";
    if(!empty($_POST["pass"]))
        echo "pass ".$_POST["pass"]."<br>";
    if(!empty($_POST["update"]))
        echo "update ".$_POST["update"]."<br>";
*/
    //編集
    $edit=0;
    if(!empty($_POST["edit"]))
    {
       $edit=$_POST["edit"];
    }
    if(($edit!=0)&&($_POST["pass"]==selectitem($pdo,$edit,4)))
    {
        $name = selectitem($pdo,$edit,1);
        $comment = selectitem($pdo,$edit,2);

        echo '<input type="text" name="title" value="';
        echo $name;
        echo '" placeholder="名前"><br>';
        echo '<input type="text" name="comment" value="';
        echo $comment;
        echo '" placeholder="コメント">';
        if(($name!="")||($comment!=""))
        {
            echo '<br> <input type="text" disabled name="submit" value="';
            echo $edit;
            echo '" placeholder="">';
            echo '<input type="hidden" name="update" value=';
            echo $edit;
            echo '>';
            echo '<br><input type="password" name="pass" value="" placeholder="パスワード">';
            echo '<input type="submit" name="submit" value="更新">';
        }
        else
        {
            echo '<br><input type="password" name="pass" value="" placeholder="パスワード">';
            echo '<input type="submit" name="submit" value="送信">';
        }
    }
    else
    {
        echo '<input type="text" name="title" value="" placeholder="名前"><br>';
        echo '<input type="text" name="comment" value="" placeholder="コメント">';
        echo '<br><input type="password" name="pass" value="" placeholder="パスワード">';
        echo '<input type="submit" name="submit" value="送信">';
    }
?>
    </form>
    <br>
    <form action="" method="post">
        <input type="text" name="delete" value="" placeholder="削除対象番号"><br>
        <input type="password" name="pass" value="" placeholder="パスワード">
        <input type="submit" name="submit" value="削除">
    </form>
    <br>
    <form action="" method="post">
        <input type="text" name="edit" value="" placeholder="編集対象番号"><br>
        <input type="password" name="pass" value="" placeholder="パスワード">
        <input type="submit" name="submit" value="編集">
    </form>
    <br>
    
    
<?php
    //$count = 1;
    //$lines = file($filename,FILE_IGNORE_NEW_LINES);
    //編集
    if((!empty($_POST["edit"]))&&($_POST["pass"]==selectitem($pdo,$edit,4)))
    {
        //echo "編集<br>";
        selectall($pdo);
    }
    //編集後の送信
    //elseif((!empty($_POST["title"])) &&(!empty($_POST["update"]))&&($_POST["pass"]==$passwd[$_POST["update"]]))
    elseif((!empty($_POST["title"])) &&(!empty($_POST["update"])))
    {
        //echo "編集後の送信<br>";
        //echo "UUU".$_POST["update"]."<br>";
        updateelement($pdo,$_POST["update"],$_POST["title"],$_POST["comment"],$_POST["pass"]);
        selectall($pdo);
    }
    //送信
    //elseif((!empty($_POST["title"]))&&(($_POST["pass"]==$passwd)||(empty($passwd)))){
    elseif(!empty($_POST["title"]))
    {
        //echo "送信<br>";
        //$id = countelement($pdo) + 1; // 行数でtセット
        $id = selectlastid($pdo) + 1;   //最後のレコードの次のIDをセット
        insertelement($pdo,$id,$_POST["title"],$_POST["comment"],$_POST["pass"]);
        selectall($pdo);
    }
    //削除
    //if(empty($_POST["title"])){
    elseif((!empty($_POST["delete"]))&&($_POST["pass"]==selectitem($pdo,$_POST["delete"],4)))
    {
        //echo "削除<br>";
        deleteelement($pdo,$_POST["delete"]);
        selectall($pdo);
    }
    //初め
    else
    {
        if((!empty($_POST["edit"]))&&($_POST["pass"]!=selectitem($pdo,$edit,4)))
            echo "パスワードが違います<br>";
        elseif((!empty($_POST["title"])) &&(!empty($_POST["update"]))&&($_POST["pass"]!=selectitem($pdo,$_POST["update"],4)))
            echo "パスワードが違います<br>";
        elseif((!empty($_POST["delete"]))&&($_POST["pass"]!=selectitem($pdo,$_POST["delete"],4)))
            echo "パスワードが違います<br>";
        /*f(($_POST["pass"]!="pass")&&(!empty($_POST["pass"]))){
            echo "パスワードが違います<br>";
        }*/
        selectall($pdo);
    }
?>
</body>
</html>
