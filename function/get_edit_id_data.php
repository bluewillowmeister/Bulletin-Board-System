

<?php
    // インクルード（再読み込み無し）
    require_once( __DIR__ . "/../config/Config.php");
    require_once( __DIR__ . "/../manager/TableDataManager.php");
    require_once( __DIR__ . "/utility.php");
    require_once( __DIR__ . "/session.php"); // セッション関数読み込み


    function get_edit_id_data()
    {
        // セッションスタート
        my_session_start();

        
        // クロスサイトリクエストフォージェリ対策
        if ($_POST["token"] !== $_SESSION["token"])
            return;


        // 編集ID送信用フォーム入力判定フラグ
        $input_edit_id_forms_flag = check_values($_POST["edit_id"], $_POST["edit_password"]);
        

        if ($input_edit_id_forms_flag)
        {
            
            
            $edit_id = h($_POST["edit_id"]);
            $edit_password = h($_POST["edit_password"]);

            // パスワードが間違っているとき
            if (!check_password($edit_id, $edit_password))
                return;

            // 投稿データテーブルの宣言とそのマネージャーの宣言
            $post_table = new PostDataTable();
            $post_data_manager = new TableDataManager($post_table);
            
            // 指定されたデータ削除
            $selected_data = $post_data_manager->select($edit_id, PostDataTable::POST_ID)[0];
            
            $selected_data_poster_name = $selected_data[PostDataTable::POSTER_NAME];
            $selected_data_post_comment = $selected_data[PostDataTable::POST_COMMENT];

            echo $selected_data_poster_name . "<br>";
            echo $selected_data_post_comment . "<br>";

            // SESSIONを定義
            $_SESSION["edit_id"] = $edit_id;
            $_SESSION["edit_poster_name"] = $selected_data_poster_name;
            $_SESSION["edit_post_comment"] = $selected_data_post_comment;
            $_SESSION["post_mode"] = PostMode::EDIT;
        }
            
    }
    
    // パスワードチェック用関数
    function check_password($target_id, $input_password)
    {
        // 投稿データテーブルの宣言とそのマネージャーの宣言
        $post_table = new PostDataTable();
        $post_data_manager = new TableDataManager($post_table);

        $selected_data = $post_data_manager->select($target_id, PostDataTable::POST_ID)[0];
        
        // データが存在しないとき
        if (empty($selected_data))
        {
            return false;
        }
        

        $selected_data_password = $selected_data[PostDataTable::POST_PASSWORD];

        return password_verify($input_password, $selected_data_password);
    }
    
?>

<?php
    get_edit_id_data();
    
    /* ↓一つ前のページのパスを指定し、処理が終わったらそこに戻る */
    header('location:'.$_SERVER["HTTP_REFERER"]);
?>

