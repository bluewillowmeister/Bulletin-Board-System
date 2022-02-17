<!-- 汎用的に利用する関数を定義 -->



<?php

    // インクルード（再読み込み無し）
    require_once( __DIR__ . "/../config/Config.php");


    // XSS攻撃対策用エスケープ関数
    // エスケープされたテキストを返す
    function h($text) 
    {
        // $text: エスケープしたいテキスト

        return htmlspecialchars($text, ENT_QUOTES, "UTF-8");
    }


    // 警告文をjavascriptで表示
    function alert($str)
    {
        $alert = "<script type='text/javascript'>alert('". $str . "');</script>";
        echo $alert;
    }   
    
    // formに入力された値が空かどうか判別する関数
    // 空のとき: false, 空でないとき: trueを返す
    function check_value(&$value, $exclude_zero=true)
    {
        // &$value, フォームに入力された値を格納している変数($_POST["name"]など)のポインタ(意味：本体)
        // $exclude_zero: デフォルト値=true, オプション変数, empty関数が空と認識してしまうゼロ(0 や "0")を除外するかどうか

        // そもそも変数($_POST["name"]など)が宣言されているかどうかチェック
        if (!isset($value))
        {
            return false;
        }
        
        // 変数の値が空かどうかチェック
        if (empty($value))
        {
            // $exclude_zero=trueのとき
            if ($exclude_zero)
            {
                // 「!==」を使って厳密に比較し、ゼロ(0 や "0")でないかチェック
                if ( ($value !== "0") && ($value !== 0) )
                {                    
                    return false;
                }
            }
            else
            {
                return false; 
            }
            
        }

        return true;
    }


    // formに入力された値が空かどうか判別する関数
    // 空のとき: false, 空でないとき: trueを返す
    // 可変長引数
    function check_values(...$values)
    {
        // &$values, フォームに入力された値を格納している配列($_POST["name"]など)

        // empty関数が空と認識してしまうゼロ(0 や "0")を除外するかどうか
        // 可変長引数とデフォルト引数が併用できないため、引数の配列末尾で判断
        // inputフォームにはboolean型の値を入力できないため問題なし
        if ( (end($values) === true) || (end($values) === false) )
        {
            $exclude_zero = end($values);
            array_pop($values);
        }
        else
            $exclude_zero = true; // デフォルト値

        // 入力された引数の数だけループ
        foreach ($values as $value)
        {
            // そもそも変数($_POST["name"]など)が宣言されているかどうかチェック
            if (!isset($value))
                return false;
            
            // 変数の値が空かどうかチェック
            if (empty($value))
            {
                // $exclude_zero=trueのとき
                if ($exclude_zero)
                {
                    // 「!==」を使って厳密に比較し、ゼロ(0 や "0")でないかチェック
                    if ( ($value !== "0") && ($value !== 0) )                  
                        return false;
                }
                else
                    return false; 
                
            }
        }
        return true;
    }


    // function check_value(
    //     &$value, 
    //     $exclude_zero=true,
    //     $alert_flag=false)
    // {
    //     // &$value, フォームに入力された値を格納している変数($_POST["name"]など)のポインタ（意味：本体）
    //     // $exclude_zero: オプション、
    //     // 

    //     if (!isset($value))
    //     {
    //         if ($alert_flag)
    //             alert("Error: isset() === false");
    //         return false;
    //     }
        
    //     if (empty($value))
    //     {
    //         if ($exclude_zero)
    //         {
    //             if ( ($value !== "0") && ($value !== 0) )
    //             {
    //                 if ($alert_flag)
    //                     alert("Error: (exor 0 && empty()) === false");
                    
    //                 return false;
    //             }
    //         }
    //         else
    //         {
    //             if ($alert_flag)
    //                 alert("Error: empty() === false");
            
    //             return false; 
    //         }
            
    //     }

    //     return true;
    // }

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

