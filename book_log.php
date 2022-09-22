<?php

function validate($review)
{
    $errors = [];

    //書籍名が正しく入力されているかチェック
    if (!strlen($review['title'])) {
      $errors['title'] = '書籍名を入力してください';
    }elseif (strlen($review['title']) > 255) {
      $errors['title'] = '書籍名は255文字以内で入力してください';
    }

    //著者名が正しく入力されているかチェック
    if (!strlen($review['author'])) {
      $errors['author'] = '著者名を入力してください';
    }elseif (strlen($review['author']) > 100) {
      $errors['author'] = '著者名は100文字以内で入力してください';
    }

    //読書状況が正しく入力されているかチェック
    if (!strlen($review['states'])) {
      $errors['states'] = '読書状況を入力してください';
    }elseif (strlen($review['states']) > 4) {
      $errors['states'] = '読書状況は4文字以内で入力してください';
    }

    //評価が正しく入力されているかチェック
    if ($review['score'] < 1 || $review['score'] > 5) {
        $errors['score'] = '評価は１〜５の整数を入力してください';
    }

    // 感想が正しく入力されているかチェック
    if (!strlen($review['summary'])) {
      $errors['summary'] = '感想を入力してください';
    }elseif (strlen($review['summary']) > 1000) {
        $errors['summary'] = '感想は1000文字以内で入力してください';
    }



    return $errors;
}

function listReviews($link)
{

  echo '登録されている読書ログを表示します' . PHP_EOL;

  $sql = 'SELECT title, author, states, score, summary FROM reviews';
  $results = mysqli_query($link, $sql);

  while ($review = mysqli_fetch_assoc($results)) {
    echo '書籍名:' . $review['title'] . PHP_EOL;
    echo '著者名:' . $review['author'] . PHP_EOL;
    echo '読書状況:' . $review['states'] . PHP_EOL;
    echo '評価:' . $review['score'] . PHP_EOL;
    echo '感想:' . $review['summary'] . PHP_EOL;
    echo '--------------' . PHP_EOL;
  }

  mysqli_free_result($results);
}

function dbConnect()
{
  $link = mysqli_connect('db', 'book_log', 'pass','book_log');

  if (!$link) {
    echo 'Error:DBに接続できません' . PHP_EOL;
    echo 'Debugging error:' . mysqli_connect_error() . PHP_EOL;
    exit;
  }

  return $link;

}





function createReview($link)
{
  $review = [];
  echo '読書ログを登録してください' . PHP_EOL;
  echo '書籍名：';
  $review['title'] = trim(fgets(STDIN));

  echo '著者名：';
  $review['author'] = trim(fgets(STDIN));

  echo '読書状況（未読,読んでる,読了）:';
  $review['states'] = trim(fgets(STDIN));

  echo '評価（５点満点の整数）：';
  $review['score'] = (int) trim(fgets(STDIN));

  echo '感想：';
  $review['summary'] = trim(fgets(STDIN));

  $validated = validate($review);
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
      "{$review['title']}",
      "{$review['author']}",
      "{$review['states']}",
      "{$review['score']}",
      "{$review['summary']}"
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
    listReviews($link);
  } elseif ($num === '9') {
    mysqli_close($link);
    break;
  }
}
