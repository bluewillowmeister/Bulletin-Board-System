<!-- index.php初期化処理 -->

<?php
    // インクルード（再読み込み無し）
    require_once( __DIR__ . "/../config/Config.php");
    require_once( __DIR__ . "/session.php"); // セッション関数読み込み

    

    function init()
    {
        my_session_start();
        $_SESSION = array();
        session_destroy();
        session_write_close();
    }
    
    
?>

