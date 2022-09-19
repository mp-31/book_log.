<?php

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


function createReview()
{
  echo '読書ログを登録してください' . PHP_EOL;
  echo '書籍名：';
  $title = trim(fgets(STDIN));

  echo '著者名：';
  $author = trim(fgets(STDIN));

  echo '読書状況（未読,読んでる,読了）:';
  $states = trim(fgets(STDIN));

  echo '評価（５点満点の整数）：';
  $score = trim(fgets(STDIN));

  echo '感想：';
  $summary = trim(fgets(STDIN));

  echo '登録が完了しました' . PHP_EOL . PHP_EOL;

  return [
    'title' => $title,
    'author' => $author,
    'states' => $states,
    'score' => $score,
    'summary' => $summary,
  ];
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
    $reviews[] = createReview();
  } elseif ($num === '2') {
    listReviews($reviews);
  } elseif ($num === '9') {
    mysqli_close($link);
    break;
  }
}
