
<?php require_once("./config/Config.php"); // グローバル定数読み込み?> 
<?php require_once("./function/utility.php"); // 汎用関数読み込み?>
<?php require_once("./function/init.php"); // 初期化関数読み込み?>
<?php require_once("./function/session.php"); // セッション関数読み込み?>
<?php require_once("./function/display.php"); // 投稿データ表示用関数読み込み?>

<?php
    my_session_start();
    if (!isset($_SESSION["post_mode"]))
    {
        $_SESSION["post_mode"] = PostMode::SIGNUP;
    }
?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta name="author" content="aoyagi">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <meta name="description" content="SQLを活用したWeb掲示板">
    <title>Mission5-1</title>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/script.js"></script>
    
    
</head>
<script>

    function clearForms(className) 
    {   
        var textForms = document.getElementsByClassName(className);
        
        for (var i = 0; i < textForms.length; i++)
        {
            textForms[i].value = '';
        }
        
    }

</script>

<body>
    <!-- header -->
    <header>
    </header>
    <!-- /header -->



    <!-- main -->
    <div id="wrapper">
        

        <h1>《この掲示板のテーマ：つぶやき》</h1>
        
        <hr>

        <?php if ( $_SESSION["post_mode"] === PostMode::SIGNUP ): ?>
            
            <!-- 投稿データ更新（追加・編集）用フォーム -->
            <form method="POST" action="./function/insert.php">
            
                <table>

                    <!-- 新規投稿送信用フォーム -->
                    <div id="signup">
                        <tr>
                            <th>名前：</th>
                            <td>
                                <input name="poster_name" class="post_data" type="text" value="">
                            </td>
                        </tr>
                        <tr>
                            <th>コメント：</th>
                            <td>
                                <input name="post_comment" class="post_data" type="" value="">            
                            </td>
                        </tr>

                        <tr>
                            <th>パスワード：</th>
                            <td><input name="signup_password" class="post_data" type="password" value="">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="submit" value="送信">
                                <input type="button" value="クリア" onclick="clearForms('post_data')" />  
                            </td>
                        </tr>
                    </div>
                    <!-- /新規投稿用フォーム -->
                    
                </table>
                
            </form>
            <!-- /投稿データ更新（追加・編集）用フォーム -->

            <!-- 削除ID送信用フォーム -->
            <form method="POST" action="./function/delete.php">
                <table>
                    <tr>
                        <th>削除対象番号：</th>
                        <td><input name="delete_id" type="number" value=""></td>
                    </tr>
                    <tr>
                        <th>パスワード：</th>
                        <td><input name="delete_password" class="post_data" type="password" value="">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="submit" value="削除">
                        </td>
                    </tr>
                </table>
            </form>
            <!-- /削除ID送信用フォーム -->


            <!-- 編集ID送信用フォーム -->
            <form method="POST" action="./function/get_edit_id_data.php">
                <table>            
                    <div id="edit">
                        <tr>
                            <th>編集対象番号：</th>
                            <td><input name="edit_id" type="number" value=""></td>
                        </tr>
                        <tr>
                            <th>パスワード：</th>
                            <td><input name="edit_password" class="post_data" type="password" value="">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="submit" value="編集">
                            </td>
                        </tr>
                    </div>
                </table>
            </form>
            <!-- /編集ID送信用フォーム -->

        <?php elseif ( $_SESSION["post_mode"] === PostMode::EDIT ): ?>

            <!-- 投稿データ更新（編集）用フォーム -->
            <form method="POST" action="./function/update.php">
            
                <table>
                    <!-- 編集済み投稿送信用フォーム -->
                    <div id="signup">
                        <tr>
                            <th>新しい名前：</th>
                            <td>
                                <input name="edit_poster_name" class="post_data" type="text" value="<?php
                                    if (check_value($_SESSION["edit_poster_name"]))
                                        echo $_SESSION["edit_poster_name"];
                                ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>新しいコメント：</th>
                            <td>
                                <input name="edit_post_comment" class="post_data" type="" value="<?php
                                    if (check_value($_SESSION["edit_post_comment"]))
                                        echo $_SESSION["edit_post_comment"];
                                ?>">            
                            </td>
                        </tr>

                        <tr>
                            <th>新しいパスワード：</th>
                            <td><input name="signup_password" class="post_data" type="password" value="">
                            </td>
                        </tr>

                        <input name="edit_id" type="hidden" value="<?php
                            if (check_value($_SESSION["edit_id"]))
                                echo $_SESSION["edit_id"];
                        ?>">
                        <tr>
                            <td>
                                <input type="submit" value="送信" formaction="./function/update.php">
                                <input type="button" value="クリア" onclick="clearForms('post_data')" />  
                            </td>
                        </tr>
                    </div>
                    <!-- /編集済み投稿用フォーム -->

                </table>
                
            </form>
            <!-- /投稿データ更新（編集）用フォーム -->


        <?php endif; ?>
        

        
        <hr>
        
        
        <h2>【投稿内容】</h2>
        <?php  
        
            display();
        ?>

    </div>
    <!-- /main -->


    <!-- footer -->
    <footer>
    </footer>
    <!-- /footer -->

</body>
</html>