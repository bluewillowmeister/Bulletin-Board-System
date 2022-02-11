<!-- 汎用的に利用する関数を定義 -->



<?php

    // インクルード（再読み込み無し）
    require_once( __DIR__ . "/../config/Config.php");


    // XSS攻撃対策用エスケープ関数
    function h($s) 
    {
        return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
    }


    // 警告文をjavascriptで表示
    function alert($str)
    {
        $alert = "<script type='text/javascript'>alert('". $str . "');</script>";
        echo $alert;
    }   

    function check_value(
        &$value, 
        $exclude_zero=true,
        $alert_flag=false)
    {
        if (!isset($value))
        {
            if ($alert_flag)
                alert("Error: isset() === false");
            return false;
        }
        
        if (empty($value))
        {
            if ($exclude_zero)
            {
                if ( ($value !== "0") && ($value !== 0) )
                {
                    if ($alert_flag)
                        alert("Error: (exor 0 && empty()) === false");
                    
                    return false;
                }
            }
            else
            {
                if ($alert_flag)
                    alert("Error: empty() === false");
            
                return false; 
            }
            
        }

        return true;
    }

    function change_mode(
        &$poster_name,
        &$post_comment,
        &$signup_password,
        &$edit_id,
        &$edit_password
    )
    {

        
        // 新規投稿フォーム入力判定フラグ
        $input_signup_forms_flag = (check_value($poster_name) === true)
            && (check_value($post_comment) === true)
            && (check_value($signup_password) === true)
            && (check_value($signup_password) === false)
            && (check_value($signup_password) === false);


        // 編集番号フォーム入力判定フラグ
        $input_edit_id_forms_flag = (check_value($poster_name) === false)
            && (check_value($post_comment) === false)
            && (check_value($signup_password) === false)
            && (check_value($edit_id) === true)
            && (check_value($edit_password) === true);

    
        if ($input_signup_forms_flag)
        {
            // echo "edit_id: ". !empty($edit_id) . "<br>";
            // echo "edit_password: ". !empty($edit_password) . "<br>";
            return PostMode::SIGNUP;
        }
        elseif ($input_edit_id_forms_flag)
        {
            // echo "edit_id: ". !empty($edit_id) . "<br>";
            // echo "edit_password: ". !empty($edit_password) . "<br>";
            return PostMode::EDIT;
        }

        return PostMode::SIGNUP;
    }



    // // パスワードチェック用
    // function check_password($input_post_password, $registered_post_password)
    // {


        
        

    //     return password_verify($input_post_password, $registered_post_password);
    // }
?>

