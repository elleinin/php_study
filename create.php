<?php

//QA NOTES
//21/07/26 - image upload currently not working

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //if error, show exceptions

//check
// echo '<pre>';
// var_dump($_POST); //$_ is a superglobal woo
// echo '</pre>';

$errors = [];
$title = '';
$price = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $date = date('Y-m-d H:i:s');

  if (!$title) {
    $errors[] = 'Product title is required';
  };
  if (!$price) {
    $errors[] = 'Product price is required';
  };

  //creates image folder if no folder
  if (!is_dir('images')) {
    mkdir('images');
  }

  //$pdo->exec("INSERT INTO products (title, image, description, price, create_date) VALUE ('$title', '', '$description', $price, '$date')");
  //note that this is unsafe because sql code can be injected into the post, messing up the database

  if (empty($errors)) {
    $image = $_FILES['image'] ?? null;
    $imagePath = '';
    if ($image && $image['tmp_name']) {
      $imagePath = 'images/'.randomString(8).'/'.$image['name'];
      mkdir(dirname($imagePath)); //creates dir for the path
      move_uploaded_file($image['tmp_name'], 'imagePath'); //uploads img file
    };


    $statement = $pdo->prepare("INSERT INTO products (title, image, description, price, create_date) VALUE (:title, :image, :description, :price, :date)");
    $statement->bindValue(':title', $title);
    $statement->bindValue(':image', $imagePath);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':date', $date);
    $statement->execute();
    header('Location: index.php'); //redirect after upload
  };

}

//random string to save image files
function randomString($n) {
  $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
  $str = '';
  for ($i = 0; $i < $n; $i++) {
    $index = rand(0, strlen($characters) - 1);
    $str .= $characters[$index];
  }
  return $str;
}


?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- CSS -->
    <link rel="stylesheet" href="app.css">


    <title>Products CRUD</title>
  </head>

  <p>
    <a href="index.php" class="btn btn-secondary">Go Back to Products</a>
</p>
  
  <body>
    <h1>Create New Product</h1>

    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <?php foreach ($errors as $error): ?>
        <div><?php echo $error ?></div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
        <!-- multipart/form-data allows file handling -->
    <form action="" method="post" enctype="multipart/form-data"> 
        <div class="mb-3">
            <label>Product Image</label>
            <br>
            <input type="file" name="image">
        </div>
        <div class="mb-3">
            <label>Product Title</label>
            <input type="text" class="form-control" name="title" value="<?php echo $title ?>">
        </div>
        <div class="mb-3">
            <label>Product Description</label>
            <textarea class="form-control" name="description" value="<?php echo $description ?>"></textarea>
        </div>
        <div class="mb-3">
            <label>Product Price</label>
            <input type="number"  step="0.01" name="price" value="<?php echo $price ?>">
        </div>
       
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    
  </body>
</html>