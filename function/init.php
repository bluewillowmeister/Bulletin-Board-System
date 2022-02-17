<!-- index.php初期化処理 -->

<?php
    // インクルード（再読み込み無し）
    require_once( __DIR__ . "/../config/Config.php");
    require_once( __DIR__ . "/session.php"); // セッション関数読み込み

    
    // セッション初期化関数
    function init()
    {
        my_session_start();
        $_SESSION = array();

        // クッキー削除
        if (isset($_COOKIE[session_name()])) 
        {
            setcookie(session_name(), '', time()-42000, '/');
        }

        session_destroy();
        session_write_close();
    }
    
    
?>

