<?php
error_reporting(E_ALL); // すべてのエラーを表示
ini_set('display_errors', 1); // エラーを画面に表示

header('Content-Type: application/json'); // JSON形式でレスポンスを返す

// POSTリクエストから社員番号を取得
$data = json_decode(file_get_contents('php://input'), true); // JSONデータを取得

if (isset($data['shainno']) && !empty($data['shainno'])) {
    $shainno = $data['shainno'];
    error_log("Received shainno: " . $shainno); // ログに社員番号を記録

    // CSVファイルのパス
    $csvFile = 'information.csv';

    // CSVファイルを読み込む処理
    if (file_exists($csvFile)) {
        $result = []; // 結果を格納する配列
        if (($handle = fopen($csvFile, 'r')) !== FALSE) {
            // 1行ずつ読み込む
            while (($row = fgetcsv($handle)) !== FALSE) {
                // 例として、1列目に社員番号があると仮定
                if ($row[0] == $shainno) {
                    $result = $row; // 社員番号が一致する行を追加
                    break; // 一致したらそれ以上検索しない
                }
            }
            fclose($handle);
        }

        // 一致するデータがあれば返す
        if (!empty($result)) {
            $shainno = $result[0]; // 社員番号
            $gender = $result[1]; // 2列目：性別
            $age = $result[2];     // 3列目: 年齢
            $busho = $result[3];     // 4列目: 部署
            $yakushoku = $result[4];     // 5列目: 役職            
            $year = $result[5]; // 6列目: 入社年

            // JSONレスポンスとしてデータを返す
            echo json_encode([
                'data' => [
                    'shainno' => $shainno,
                    'age' => $age,
                    'busho' => $busho,
                    'gender' => $gender,
                    'yakushoku' => $yakushoku,
                    'year' => $year
                ],
                'success' => true
            ]);
        } else {
            // 一致するデータがない場合のエラーレスポンス
            echo json_encode(['error' => 'データが見つかりません。', 'success' => false]);
        }
    } else {
        // CSVファイルが存在しない場合のエラー
        echo json_encode(['error' => 'CSVファイルが存在しません。', 'success' => false]);
    }
} else {
    // 社員番号が送信されなかった場合
    echo json_encode(['error' => '社員番号が指定されていません。', 'success' => false]);
}

exit; // スクリプトを終了
?>




<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="importmap">
      {"imports": {
          "@google/generative-ai": "https://esm.run/@google/generative-ai"
        }
      }
    </script>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/sample.css">
    <title>社内課題抽出ツール</title>
</head>

<body>

<h1>書き込みしました。</h1>
<h2>./information.csv を確認しましょう！</h2>

<ul>
<li><a href="index.php">戻る</a></li>
</ul>
</body>
</html>