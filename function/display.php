

<?php
    // インクルード（再読み込み無し）
    require_once( __DIR__ . "/../config/Config.php");
    require_once( __DIR__ . "/../manager/TableDataManager.php");
    require_once( __DIR__ . "/utility.php");


    function display()
    {
        

        // 投稿データテーブルの宣言とそのマネージャーの宣言
        $post_table = new PostDataTable();
        $post_data_manager = new TableDataManager($post_table);


        // 新規投稿データ挿入
        $results = $post_data_manager->select_all_data();

        foreach ($results as $row)
        {
            //$rowの中にはテーブルのカラム名が入る
            echo "投稿ID" . ": " . $row[PostDataTable::POST_ID] . "<br>";
            echo "投稿者名" . ": " . $row[PostDataTable::POSTER_NAME] . "<br>";
            echo "投稿コメント" . ": " . $row[PostDataTable::POST_COMMENT] . "<br>";
            echo "投稿日時" . ": " . $row[PostDataTable::POST_DATETIME] . "<br>";
            // echo PostDataTable::POST_PASSWORD . ": " . $row[PostDataTable::POST_PASSWORD] . "<br>";
            // print_r($row);
            echo "<hr>";
        }
    }
    
?>



