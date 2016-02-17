<?php
$user = 'root';
$pass = 'root';
$name = 'shop';
$dbh = null;
try {
  $dbh = new PDO('mysql:host=localhost;dbname=' .$name, $user, $pass);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
      print"error: ". $e->getMessage() . "<br/>";
      die(); 
}

function addProduct($conn, $id) {
    $token = getToken();
    $sql = 'INSERT INTO orders_products (orders_id, products_id) (SELECT o.id, ? FROM users u LEFT JOIN orders o ON u.id = o.users_id AND o.status = "new" WHERE u.token = ?)';
    $stmt = $conn->prepare($sql);
    if ($stmt->execute(array($id, $token))) {
    }
}
function deleteProduct($conn, $id) {
    $token = getToken();
    $sql = 'DELETE op FROM users u LEFT JOIN orders o ON u.id = o.users_id AND o.status = "new" LEFT JOIN orders_products op ON o.id = op.orders_id WHERE u.token = ? AND op.id = ?';
    $stmt = $conn->prepare($sql);
    if ($stmt->execute(array($token, $id))) {
    }
}
function getProducts($conn) {
    $token = getToken();
    $sql = 'SELECT p.name, p.price, p.preview, op.id FROM users u LEFT JOIN orders o ON u.id = o.users_id AND o.status = "new" LEFT JOIN orders_products op ON o.id = op.orders_id LEFT JOIN products p ON op.products_id = p.id WHERE u.token = ?';
    $stmt = $conn->prepare($sql);
    if ($stmt->execute(array($token))) {
        while ($row = $stmt->fetch()) {
            if ($row['id'] != null) {
                echo '<div>
                    <div class="col-sm-4 col-lg-4 col-md-4" >
                        <div class="thumbnail" style="height:550px;" >
                            <img src="'.$row["preview"].'">

                    Name: '.$row['name'].'<br>
                    Price: $'.$row['price'].'<br>
                    <form method="post" action="shoppingcart.php">
                        <input type="hidden" name="id" value="'.$row['id'].'"/>
                        <input type="submit" name="delete" value="DELETE"/>
                    </form>
                    </div></div>
                    </div>'
                    ;


            }
        }
    }
}
function getToken() {
    if (isset($_COOKIE['token'])) {
        return $_COOKIE['token'];
    }
    else {
    }
}
if(isset($_POST['add'])) {
    $id = $_POST['id'];
    addProduct($dbh, $id);
}
if(isset($_POST['delete'])) {
    $id = $_POST['id'];
    deleteProduct($dbh, $id);
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>www.TheWaterShop.com</title>
  <script src="//cdnjs.cloudflare.com/ajax/libs/minicart/3.0.6/minicart.min.js"></script>
        <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

      <!-- Optional theme -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

      <!-- Latest compiled and minified JavaScript -->
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
      <link rel=stylesheet href="style.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
      <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

</head>
<body>
  <script src="scripts.js"></script>
<!---navbar-->
<nav class="navbar navbar-light" style="background-color: #FAF0FF;">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
      </button>
      
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li ><a href="index.php">Home</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">signup</button>
        <li><a class="active" href="shoppingcart.php"><span class="glyphicon glyphicon-shopping-cart"></span> shopping cart</a></li>
      </ul>
    </div>
  </div>
</nav>
<?php
getProducts($dbh);
?>
   <form method="post" action="shoppingcart.php">
        <input type="submit" name="checkout" value="CHECKOUT"/>
    </form>
</body>
</html>