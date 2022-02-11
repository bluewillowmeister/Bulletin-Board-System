<?php

    /* データベース接続用クラス */
    class ConnectDB
    {
        /* プロパティの宣言 */
        private const DB_NAME = 'データベース名';
        private const HOST = 'ホスト名';
        private const CHARSET = 'utf8';
        private const USER = 'ユーザ名';
        private const PASSWORD = 'パスワード';
        

        /* データベースに接続する メソッド(関数) */
        public function pdo()
        {   
            $dsn = sprintf('mysql:dbname=%s;host=%s;charset=%s', 
                            self::DB_NAME, self::HOST, self::CHARSET);
            
            $user = self::USER;
            $password = self::PASSWORD;

            
            // オプション定義
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, // エラー文出力
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . self::CHARSET);
            try
            {
                
                $pdo = new PDO($dsn, $user, $password, $options);
                return $pdo;
            }
            catch (Exception $e)
            {
                echo 'Connection failed: '. $e->getMessage();
                exit();
            }
        }
        
    }


?>


