<?php
$filters = [
  'ISBN' => '',
  'name' => '',
  'author' => '',
];

if (!empty($_GET['ISBN'])) {
  $filters['ISBN'] = $_GET['ISBN'];
}

if (!empty($_GET['name'])) {
  $filters['name'] = $_GET['name'];
}

if (!empty($_GET['author'])) {
  $filters['author'] = $_GET['author'];
}

$sql = 'SELECT * FROM books';

if (count($filters)) {
  $sql .= ' WHERE ';
  $i = 0;

  foreach ($filters as $filterName => $filterValue) {
    $sql .= "($filterName LIKE '%$filterValue%')";
    if ($i < (count($filters) - 1)) {
      $sql .= ' AND ';
    }
    $i++;
  }
}

$connect = mysqli_connect('localhost', 'root', '', '4-1-hw');

if ($connect === false) {
  http_response_code(500);
  exit('MySQL connect error');
}

$res = mysqli_query($connect, $sql);
$booksList = [];
$keysList = [];

if ($res === false) {
  http_response_code(404);
  exit('Data not found');
}

while($booksList[] = mysqli_fetch_assoc($res)) {}

if (gettype($booksList) !== 'array' || !$booksList[0]) {
  http_response_code(404);
  exit('Data not found');
}

foreach ($booksList[0] as $k => $v) {
  $keysList[] = $k;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>SQL</title>
</head>
<body>
  <form>
    <h3>Фильтры:</h3>
    <input type="text" name="ISBN" placeholder="ISBN" value="<?php echo $filters['ISBN'];?>" />
    <input type="text" name="name" placeholder="Название книги" value="<?php echo $filters['name'];?>" />
    <input type="text" name="author" placeholder="Автор книги" value="<?php echo $filters['author'];?>" />
    <button type="submit">
      Фильтровать
    </button>
  </form>

  <table>
    <tr>
      <?php foreach($keysList as $key): ?>
        <th><?php echo $key; ?></th>
      <?php endforeach; ?>
    </tr>

    <?php foreach($booksList as $book): ?>
      <?php if (gettype($book) === 'array'): ?>
        <tr>
          <?php foreach($book as $v): ?>
              <td><?php echo $v; ?></td>
          <?php endforeach; ?>
        </tr>
      <?php endif; ?>
    <?php endforeach; ?>
  </table>
</body>
</html>