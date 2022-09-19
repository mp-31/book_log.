<?php

function validate($reviews)
{
    $errors = [];

    //書籍名が正しく入力されているかチェック
    if (!strlen($reviews['title'])) {
      $errors['title'] = '書籍名を入力してください';
    }elseif (strlen($reviews['title']) > 255) {
      $errors['title'] = '書籍名は255文字以内で入力してください';
    }

    return $errors;
}

function dbConnect()
{
  $link = mysqli_connect('db', 'book_log', 'pass', 'book_log');

  if (!$link) {
    echo 'Error:DBに接続できません' . PHP_EOL;
    echo 'Debugging error:' . mysqli_connect_error() . PHP_EOL;
    exit;
  }
  echo 'DBと接続できました' . PHP_EOL;

  return $link;
}

$reviews = [];

function createReview($link)
{
  $reviews = [];
  echo '読書ログを登録してください' . PHP_EOL;
  echo '書籍名：';
  $reviews['title'] = trim(fgets(STDIN));

  echo '著者名：';
  $reviews['author'] = trim(fgets(STDIN));

  echo '読書状況（未読,読んでる,読了）:';
  $reviews['states'] = trim(fgets(STDIN));

  echo '評価（５点満点の整数）：';
  $reviews['score'] = trim(fgets(STDIN));

  echo '感想：';
  $reviews['summary'] = trim(fgets(STDIN));

  $validated = validate($reviews);
  if (count($validated) > 0) {
    foreach ($validated as $error) {
        echo $error . PHP_EOL;
    }
    return;
  }

  $sql = <<<EOT
  INSERT INTO reviews (
      title,
      author,
      states,
      score,
      summary
  ) VALUES (
      "{$reviews['title']}",
      "{$reviews['author']}",
      "{$reviews['states']}",
      "{$reviews['score']}",
      "{$reviews['summary']}"
  )
  EOT;
      $result = mysqli_query($link, $sql);
      if ($result) {
        echo '登録が完了しました' . PHP_EOL . PHP_EOL;
      } else {
        echo 'Error:データの追加に失敗しました' . PHP_EOL;
        echo 'Debugging error;' . mysqli_error($link) . PHP_EOL . PHP_EOL;
      }
}



function listReviews($reviews)
{
  echo '登録されている読書ログを表示します' . PHP_EOL;

  foreach ($reviews as $review) {
    echo '書籍名：' . $review['title'] . PHP_EOL;
    echo '著者名：' . $review['author'] . PHP_EOL;
    echo '読書状況：' . $review['states'] . PHP_EOL;
    echo '評価：' . $review['score'] . PHP_EOL;
    echo '感想：' . $review['summary'] . PHP_EOL;
    echo '-------------------------' . PHP_EOL;
  }
}


$link = dbConnect();

while (true) {
  echo '1.読書ログを登録' . PHP_EOL;
  echo '2.読書ログを表示' . PHP_EOL;
  echo '9.アプリケーションを終了' . PHP_EOL;
  echo '番号を選択してください(1,2,9):';
  $num = trim(fgets(STDIN));

  if ($num == '1') {
    createReview($link);
  } elseif ($num === '2') {
    // listReviews($reviews);
    listReviews($reviews);
  } elseif ($num === '9') {
    mysqli_close($link);
    break;
  }
}
