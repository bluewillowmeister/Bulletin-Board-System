<!-- セッションに操作関係の関数を定義 -->



<?php

    // 自前のセッション開始関数
    function my_session_start() 
    {
        // use_strict_mode を確実に有効にする
        // use_strict_mode は、セキュリティ上の都合で強制する
        ini_set('session.cookie_lifetime', 0);
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_strict_mode', 1); // default: 0
        ini_set('session.cookie_httponly', 1); // default: 0
        ini_set('session.cookie_secure', 0); 
        ini_set('session.use_trans_sid', 0);
        ini_set('session.cache_limiter', 'private_no_expire'); // default: nocache
        // ini_set('session.hash_function', 'sha256');
        session_start();
        session_regenerate_id();
    }

    // セッション開始判定関数
    function is_session_started()
    {
        if ( php_sapi_name() !== 'cli' ) 
        {
            if ( version_compare(phpversion(), '5.4.0', '>=') ) 
            {
                return session_status() === PHP_SESSION_ACTIVE ? true : false;
            } 
            else 
            {
                return session_id() === '' ? false : true;
            }
        }
        return false;
    }

    // // 自前のセッションID再生成関数
    // function my_session_regenerate_id() 
    //     {
    //     // セッションがアクティブな間は、
    //     // 衝突しないことを確実にするため
    //     // session_create_id() を呼び出す
    //     if (session_status() != PHP_SESSION_ACTIVE) 
    //     {
    //         session_start();
    //     }
    //     // 警告: 秘密の文字列を prefix に使ってはいけない!
    //     $newid = session_create_id('myprefix-');

    //     // 削除時のタイムスタンプを設定
    //     // セッションデータは、それなりの理由があるので、すぐに削除してはいけない
    //     $_SESSION['deleted_time'] = time();
    //     // セッションを終了する
    //     session_commit();
    //     // ユーザー定義のセッションIDを確実に受け入れるようにする
    //     // 注意: 通常の操作のためには、use_strict_mode は有効でなければならない
    //     // ini_set('session.use_strict_mode', 0);
    //     // 新しいカスタムのセッションIDを設定
    //     session_id($newid);
    //     // カスタムのセッションIDでセッションを開始
    //     session_start();
    // }

?>

