

<?php
    // インクルード（再読み込み無し）
    require_once( __DIR__ . "/../config/Config.php");
    require_once( __DIR__ . "/../manager/TableDataManager.php");
    require_once( __DIR__ . "/utility.php");
    require_once( __DIR__ . "/init.php"); 

    function update()
    {
        // セッションスタート
        my_session_start();

        // クロスサイトリクエストフォージェリ対策
        if ($_POST["token"] !== $_SESSION["token"])
            return;

        // 編集済み投稿フォーム入力判定フラグ
        $input_edit_forms_flag = check_values($_POST["edit_poster_name"], $_POST["edit_post_comment"], $_POST["signup_password"], $_POST["edit_id"]); 


        

        if ($input_edit_forms_flag)
        {
            // セッション定義
            $_SESSION["post_mode"] = PostMode::SIGNUP;
            
            $edit_id = h($_POST["edit_id"]);
            $new_poster_name = h($_POST["edit_poster_name"]);
            $new_post_comment = h($_POST["edit_post_comment"]);
            $new_post_password = password_hash(h($_POST["signup_password"]), PASSWORD_DEFAULT, array("cost" => 11)); // 60文字のhash値に変換



            // パラメータ名と挿入する新規データを格納した連想配列
            $new_params_dict = array(
                PostDataTable::POSTER_NAME => $new_poster_name,
                PostDataTable::POST_COMMENT => $new_post_comment,
                PostDataTable::POST_PASSWORD => $new_post_password);
            
            
            // 投稿データテーブルの宣言とそのマネージャーの宣言
            $post_table = new PostDataTable();
            $post_data_manager = new TableDataManager($post_table);
            
            
            

            // 新規投稿データ挿入
            $post_data_manager->update($new_params_dict, $edit_id, PostDataTable::POST_ID);
            
            // セッション初期化
            init();
            
        }
        
        
    }
    
?>

<?php
    update();
    
    /* ↓一つ前のページのパスを指定し、処理が終わったらそこに戻る */
    header('location:'.$_SERVER["HTTP_REFERER"]);
?>

