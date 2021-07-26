<?php

//two ways to connect to database; mysqli; pdo
//PDO = PHP Data Objects; enables access from PHP to MySQL databases
//dsn = connecion string that tells driver where to find connection
//PDO(dsn, username, pass)
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //if error, show exception

$statement = $pdo->prepare('SELECT * FROM products ORDER BY create_date DESC'); //select from database
//^^exec can also be used to initialize in a sense, but is actually used to make changes, so prepare is better
$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC); //each record to be fetched as assoc array --passed into--> products variable

//

//check
// echo '<pre>';
// var_dump($products);
// echo '</pre>';

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
  <body>
    <h1>Products CRUD</h1>

    <p>
        <a href="create.php" class="btn btn-success">Create Product</a>
    </p>

    <table class="table">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Image</th>
            <th scope="col">Title</th>
            <th scope="col">Price</th>
            <th scope="col">Create Date</th>
            <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
            </tr> -->
            <!--method a-->
            <!-- <?php foreach ($products as $product) { ?> //$i is index
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                </tr>
            <?php } ?> -->
            <!--method b -->
            <?php foreach ($products as $i => $product): ?>
                <tr>
                    <th scope="row"><?php echo $i + 1 ?></th>
                    <td> <img src="?php echo $product['image']" class="thumb"> </td>
                    <td><?php echo $product['title'] ?></td>
                    <td><?php echo $product['price'] ?></td>
                    <td><?php echo $product['create_date'] ?></td>
                    <td>
                        <a href="update.php?id=<?php echo $product['id'] ?>" class="btn btn-sm btn-outline-dark">Edit</a>
                        <!-- <a href="delete.php?id=<?php echo $product['id']?>" type="button" class="btn btn-sm btn-outline-danger">Delete</a> -->
                        <form style="display: inline-block" method="post" action="delete.php">
                            <input type="hidden" name="id" value="<?php echo $product['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            <!-- when clicked, submit hidden id input -->
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            
        </tbody>
    </table>
    
  </body>
</html>