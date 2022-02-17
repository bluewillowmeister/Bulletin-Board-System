<?php

    // 親クラス読み込み
    require_once( __DIR__ . "/ConnectDB.php");


    // 汎用的なデータベース操作用クラス
    // SQLインジェクション攻撃に対応
    class TableDataManager extends ConnectDB
    {
        private $table_name; // テーブル名
        private $bind_params_dict; // bindParam関数を通すパラメータとそのデータ型を格納
        private $param_placeholder_dict; // {パラメータ名: そのプレースホールダー}を格納した連想配列
        private $datetime_name; // 日時に関するパラメータ名

        // テーブル作成を行うコンストラクタ
        public function __construct($table)
        {

            $this->table_name = $table->table_name;
            $this->datetime_name = $table->datetime_name;
            $this->bind_params_dict = $table->bind_params_dict;
            $this->param_placeholder_dict = $this->get_param_placeholder_dict($table->bind_params_dict);
            
            // print_r($table->bind_params_dict);
            // print_r($this->param_placeholder_dict);
            // テーブル作成の宣言文定義
            $sql = sprintf("CREATE TABLE IF NOT EXISTS %s (%s);", 
                $table->table_name,
                $table->table_params_config // [パラメータ名, それぞれのデータ型・属性等]のペアリストを記述したSQL構文 
            );

            try 
            {
                // テーブル作成実行
                $stmt = $this->pdo()->query($sql);
            }
            catch (Exception $e) // エラー処理
            {
                echo $e->getMessage();
                exit();
            }
        }

        // １行分の新データ挿入
        public function insert($new_params_dict)
        {
            // $new_params_dict: パラメータ名と挿入する新規データを格納した連想配列
            
            try
            {
                // パラメータ名とその名前付きプレースホルダーを「,」で繋げて結合し１行に変換
                $param_names_str = implode(", " , array_flip($new_params_dict));
                $param_placeholder_str = implode(", ", $this->param_placeholder_dict);

                // INSERT構文を定義
                $insert_stmt = sprintf("INSERT INTO %s (%s, %s) VALUES (%s, NOW())", 
                    $this->table_name, $param_names_str, $this->datetime_name, $param_placeholder_str);

                
                // 前処理
                $stmt = $this->pdo()->prepare($insert_stmt);

                // 追加するデータ数分ループする
                foreach ($new_params_dict as $param_name => &$new_param)
                {
                    
                    $data_type = $this->bind_params_dict[$param_name];
                    $new_param_placeholder = $this->param_placeholder_dict[$param_name];
                    
                    
                    // 名前付きプレースホルダを実際の値に変換
                    $stmt->bindParam($new_param_placeholder, $new_param, $data_type);
                }

                // 挿入処理実行
                $stmt->execute();
            }
            catch (Exception $e) // エラー処理
            {
                echo $e->getMessage();
                exit();
            }
            
        }

        // 指定されたIDのデータ更新
        public function update($new_params_dict, $target_id, $id_name, $id_dtype=PDO::PARAM_INT)
        {
            // $new_params_dict: パラメータ名と挿入する新規データを格納した連想配列
            // $target_id; 指定されたID
            // $id_name: id（primary key）の名前

            try
            {
                // 「param_name=:param_name」という文を格納する配列
                $param_name_and_param_placeholder_list = array();

                foreach ($this->param_placeholder_dict as 
                    $param_name => $param_placeholder)
                {
                    $param_name_and_param_placeholder_list[] = $param_name . 
                        "=" . $param_placeholder;
                }

                

                // UPDATE構文を定義
                $update_stmt = sprintf("UPDATE %s SET %s,%s=NOW() WHERE %s=:%s", 
                    $this->table_name, 
                    implode(",", $param_name_and_param_placeholder_list),
                    $this->datetime_name,
                    $id_name, $id_name);

                // 前処理
                $stmt = $this->pdo()->prepare($update_stmt);

                // 更新するデータ分ループする
                foreach ($new_params_dict as $param_name => &$new_param)
                {
                    
                    $data_type = $this->bind_params_dict[$param_name];
                    $new_param_placeholder = $this->param_placeholder_dict[$param_name];
                    
                    
                    // 名前付きプレースホルダを実際の値に変換
                    $stmt->bindParam($new_param_placeholder, $new_param, $data_type);
                }

                // id（primary key）をbindする処理（挿入時に利用しないのでbindするパラメータとして予め指定されていない）
                $id_placeholder = ":". $id_name;
                $stmt->bindParam($id_placeholder, $target_id, $id_dtype);

                // 更新処理実行
                $stmt -> execute();
            }
            catch (Exception $e) // エラー処理
            {
                echo $e->getMessage();
                exit();
            }
            
        }


        // 指定されたIDのデータ更新
        public function delete($target_id, $id_name, $id_dtype=PDO::PARAM_INT)
        {
            // $target_id; 指定されたID
            // $id_name: id（primary key）の名前

            try
            {
                
                // DELETE構文を定義
                $delete_stmt = sprintf("DELETE FROM %s WHERE %s=:%s", 
                    $this->table_name, $id_name, $id_name);

                
                // 前処理
                $sql = $this->pdo()->prepare($delete_stmt);

                // id（primary key）をbindする処理（挿入時に利用しないのでbindするパラメータとして予め指定されていない）
                $id_placeholder = ":". $id_name;
                $sql->bindParam($id_placeholder, $target_id, $id_dtype);

                // 更新処理実行
                $sql -> execute();
                
                // primary key をふり直す
                $num_of_rows = $this->count_row();
                if ($num_of_rows >= 0)
                {   
                    // ふり直しの構文定義
                    $reset_primary_key_stmt = sprintf("SET @i := %s; UPDATE %s SET %s = (@i := @i +1); ALTER TABLE %s AUTO_INCREMENT = 1", 
                        0, $this->table_name, $id_name, $this->table_name);
                    // $reset_primary_key_stmt = sprintf("ALTER TABLE %s AUTO_INCREMENT = %s", 
                    //     $this->table_name, 1);
                    $sql = $this->pdo()->query($reset_primary_key_stmt);
                }   
                    
            }
            catch (Exception $e) // エラー処理
            {
                echo $e->getMessage();
                exit();
            }
            
        }
        

        // 指定されたIDのデータ更新
        public function select($target_id, $id_name, $id_dtype=PDO::PARAM_INT, $all_symbol='*')
        {
            // $target_id; 指定されたID
            // $id_name: id（primary key）の名前

            try
            {
                // SELECT構文を定義
                $select_stmt = sprintf("SELECT %s FROM %s WHERE %s=:%s", 
                    $all_symbol, $this->table_name, $id_name, $id_name);

                // 前処理
                $stmt = $this->pdo()->prepare($select_stmt);

                // id（primary key）をbindする処理（挿入時に利用しないのでbindするパラメータとして予め指定されていない）
                $id_placeholder = ":". $id_name;
                $stmt->bindParam($id_placeholder, $target_id, $id_dtype);

                // 更新処理実行
                $stmt -> execute();
                $results = $stmt->fetchAll();
                return $results;
            }
            catch (Exception $e) // エラー処理
            {
                echo $e->getMessage();
                exit();
            }
            
        }

        public function select_all_data($all_symbol="*")
        {
            try
            {
                // SELECT構文を定義
                $select_all_stmt = sprintf("SELECT %s FROM %s", $all_symbol, $this->table_name);

                // 実行
                $stmt = $this->pdo()->query($select_all_stmt);

                // 配列として取得
                $results = $stmt->fetchAll();
                
                return $results;
            }
            catch (Exception $e) // エラー処理
            {
                echo $e->getMessage();
                exit();
            }
        }

        // 行数をカウントする関数
        public function count_row($all_symbol='*')
        {

            try
            {
                // SELECT構文を定義
                $select_all_stmt = sprintf("SELECT %s FROM %s", $all_symbol, $this->table_name);

                // 実行
                $stmt = $this->pdo()->query($select_all_stmt);

                // 行数をカウント
                $count = $stmt->rowCount();
                
                return $count;
            }
            catch (Exception $e) // エラー処理
            {
                echo $e->getMessage();
                exit();
            }
        }

        // テーブルデータ全て破棄
        public function drop_table()
        {
            $sql = sprintf('DROP TABLE %s', $this->table_name);
            $this->pdo()->query($sql);
        }

        // パラメータ名とその名前付きプレースホールダーを辞書型にして取得
        private function get_param_placeholder_dict($bind_params_dict, $placeholder = ":")
        {

            // 配列初期化
            $param_placeholder_dict = array();  
            
            // 名前付きプレースホルダーを格納
            foreach ($bind_params_dict as $param_name => $data_type)
            {
                
                $param_placeholder_dict[$param_name] = $placeholder . $param_name;
            }

            return $param_placeholder_dict;
        }

        

    }



?>
