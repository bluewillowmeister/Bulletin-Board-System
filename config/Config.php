<!-- グローバル定数代替用クラスの構成定義 -->


<?php
    // 汎用テーブル構成定義用クラス
    class DataTable
    {
        public $table_name; // テーブル名
        public $bind_params_dict; // 更新対象かつbindParam関数に渡すパラメータとそのデータ型を格納
        public $table_params_config; // CREATE構文に渡す[パラメータ名, それぞれのデータ型・属性等]のペアリストを格納
        public $datetime_name; // 日時に関係するパラメータ名

        protected function __construct(
            $table_name,
            $datetime_name,
            $bind_params_dict, 
            $table_params_config)
        {
            $this->table_name = $table_name;
            $this->datetime_name = $datetime_name;
            $this->bind_params_dict = $bind_params_dict;
            $this->table_params_config = $table_params_config;
            
        }
    }


    // 投稿掲示板データベースクラス（グローバル定数の代替）
    class PostDataTable extends DataTable
    {
        // テーブル名
        public const TABLE_NAME = "post_db"; 

        // 列名一覧
        public const POST_ID = "post_id";
        public const POSTER_NAME = "poster_name";
        public const POST_COMMENT = "post_comment"; 
        public const POST_PASSWORD = "post_password";
        public const POST_DATETIME = "post_datetime";
        
        
        // 列名+それぞれのデータ型・サイズ・属性など
        public const TABLE_PARAMS_CONFIG = 
            self::POST_ID . " INT AUTO_INCREMENT PRIMARY KEY," . 
            self::POSTER_NAME . " VARCHAR(100)," . 
            self::POST_COMMENT. " TEXT," . 
            self::POST_PASSWORD. " CHAR(60)," . 
            self::POST_DATETIME. " DATETIME";
            
        // bindParams関数の引数にするパラメータ名とそのデータ型を格納した連想配列
        public const BIND_PARAMS_DICT = array(
            self::POSTER_NAME => PDO::PARAM_STR, 
            self::POST_COMMENT => PDO::PARAM_STR, 
            self::POST_PASSWORD => PDO::PARAM_STR);
        


        public function __construct()
        {
            parent::__construct(self::TABLE_NAME, self::POST_DATETIME, self::BIND_PARAMS_DICT, self::TABLE_PARAMS_CONFIG);
        }
    }

    // 投稿モード（新規投稿・投稿編集）を定義
    class PostMode
    {
        public const SIGNUP = "SIGNUP";
        public const EDIT = "EDIT";
    }

    class FormType
    {
        public const TEXT = "text";
        public const HIDDEN = "hidden";
    }
    

?>
