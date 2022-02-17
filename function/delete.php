

<?php
    // インクルード（再読み込み無し）
    require_once( __DIR__ . "/../config/Config.php");
    require_once( __DIR__ . "/../manager/TableDataManager.php");
    require_once( __DIR__ . "/utility.php");
    require_once( __DIR__ . "/init.php"); // 初期化処理実行


    function delete()
    {
        // セッションスタート
        my_session_start();

        // クロスサイトリクエストフォージェリ対策
        if ($_POST["token"] !== $_SESSION["token"])
            return;

        // 削除ID送信用フォーム入力判定フラグ
        $input_delete_id_forms_flag = check_values($_POST["delete_id"], $_POST["delete_password"]);

        

        if ($input_delete_id_forms_flag)
        {
            // SESSIONを定義
            $_SESSION["post_mode"] = PostMode::SIGNUP;
            
            $delete_id = h($_POST["delete_id"]);
            $delete_password = h($_POST["delete_password"]);

            // パスワードが間違っているとき
            if (!check_password($delete_id, $delete_password))
                return;

            // 投稿データテーブルの宣言とそのマネージャーの宣言
            $post_table = new PostDataTable();
            $post_data_manager = new TableDataManager($post_table);
            
            // 指定されたデータ削除
            $post_data_manager->delete($delete_id, PostDataTable::POST_ID);
            init();
            
        }
            
    }
    
    // パスワードチェック用関数
    function check_password($target_id, $input_password)
    {
        // 投稿データテーブルの宣言とそのマネージャーの宣言
        $post_table = new PostDataTable();
        $post_data_manager = new TableDataManager($post_table);

        $selected_data = $post_data_manager->select($target_id, PostDataTable::POST_ID);
        
        // データが存在しないとき
        if (empty($selected_data))
        {
            return false;
        }
        

        $selected_data_password = $selected_data[0][PostDataTable::POST_PASSWORD];

        return password_verify($input_password, $selected_data_password);
    }
    
?>

<?php
    delete();
    
    /* ↓一つ前のページのパスを指定し、処理が終わったらそこに戻る */
    header('location:'.$_SERVER["HTTP_REFERER"]);
?>

