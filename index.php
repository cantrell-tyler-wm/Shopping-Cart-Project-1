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
  function getProducts($conn){
    $sql = 'SELECT id, name, price, preview FROM products ORDER BY price';
    $stmt = $conn->prepare($sql);
      if ($stmt->execute()) {
        while ($row = $stmt->fetch()) {
          //echo $row["name"];
          echo '<div class="col-sm-4 col-lg-4 col-md-4" >
                        <div class="thumbnail" style="height:550px;" >
                            <img src="'.$row["preview"].'">
                            <div class="caption">
                                <h4 class="pull-right">$'.$row["price"].'</h4>
                                <h4><a href="#">'.$row["name"].'</a>
                                </h4>

                                 <p>This water is good</p>
                            </div>
                            <div class="ratings">
                                <p class="pull-right">15 reviews</p>
                                <p>
                                    <span class="glyphicon glyphicon-star"></span>
                                    <span class="glyphicon glyphicon-star"></span>
                                    <span class="glyphicon glyphicon-star"></span>
                                    <span class="glyphicon glyphicon-star"></span>
                                    <span class="glyphicon glyphicon-star"></span>
                                </p>
                            </div>
                            <form method="post" action="/Shopping-Cart-Project-1/shoppingcart.php">
                              <input type="hidden" name="id" value="'.$row['id'].'"/>
                              <input type="submit" name="add" value="ADD"/>
                            </form>
                        </div>
                    </div>';
        }
      }
  }

function register($conn) {
    $username = $_POST['username'];
    $password = sha1($_POST['password']);
    $email = $_POST['email'];
    $token = generateToken();
    $sql = 'INSERT INTO users (username, password, email,token) VALUES (?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);
    try {
        if ($stmt->execute(array($username, $password, $email, $token))) {
            setcookie('token', $token, 0, "/");
            $sql = 'INSERT INTO orders (users_id, status) (SELECT u.id, "new" FROM users u WHERE u.token = ?)';
            $stmt1 = $conn->prepare($sql);
            if ($stmt1->execute(array($token))) {
                echo 'Account Registered';
            }
        }
    }
    catch (PDOException $e) {
        echo 'Username or Email Already Registered';
    }
}

function generateToken() {
    $date = date(DATE_RFC2822);
    $rand = rand();
    return sha1($date.$rand);
}

function login($conn) {
    setcookie('token', "", 0, "/");
    $username = $_POST['username'];
    $password = sha1($_POST['password']);
    $sql = 'SELECT * FROM users WHERE username = ? AND password = ?';
    $stmt = $conn->prepare($sql);
    if ($stmt->execute(array($username, $password))) {
        $valid = false;
        while ($row = $stmt->fetch()) {
            $valid = true;
            $token = generateToken();
            $sql = 'UPDATE users SET token = ? WHERE username = ?';
            $stmt1 = $conn->prepare($sql);
            if ($stmt1->execute(array($token, $username))) {
                setcookie('token', $token, 0, "/");
                echo 'Login Successful';
            }
        }
        if(!$valid) {
            echo 'Username or Password Incorrect';
        }
    }
}
if(isset($_POST['login'])) {
    login($dbh);
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
        <li class="active"><a href="#">Home</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">signup</button>
        <li><a href="shoppingcart.php"><span class="glyphicon glyphicon-shopping-cart"></span> shopping cart</a></li>
      </ul>
    </div>
  </div>
</nav>
<!-- Page Content -->
  <center>
    <div class="container">

        <div class="row">
            <div class="col-md-15">

                <div class="row carousel-holder">

                    <div class="col-md-12">
                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                                <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="item active">
                                    <img class="slide-image" src="http://stockfresh.com/files/k/kurhan/m/37/4247_stock-photo-happy-family.jpg" alt="">
                                </div>
                                <div class="item">
                                    <img style="height: 400px" class="slide-image" src="https://dpcs.ftcdn.net/r/pics/412695cfe490b0b629d0bcd2f3e43b0c3be5f7bb/all/fader/51309988.jpg" alt="">
                                </div>
                                <div class="item">
                                    <img style="height: 400px" class="slide-image" src="http://bebusinessed.com/wp-content/uploads/2014/03/734899052_13956580111.jpg" alt="">
                                </div>
                            </div>
                            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left"></span>
                            </a>
                            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>
                        </div>
                    </div>

                </div>
<br><br>
                <div class="row">
                    <?php 
                      getProducts($dbh);
                    ?>
                    

                    

                    

                    
                  

                </div>

            </div>

        </div>

    </div>


     <?php
    if(isset($_POST['register'])) {
        register($dbh);
    }
?>
<form method="post" action="">
        <input type="text" name="username" placeholder="Username"/>
        <input type="password" name="password" placeholder="Password"/>
        <input type="submit" name="login" value="LOGIN"/>
    </form>

    <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Sign Up</h4>
      </div>
           <CENTER>
              <form method="post" action="">
            <input type="text" name="username" placeholder="Username"/>
            <br>
            <input type="password" name="password" placeholder="Password"/>
            <br>
            <input type="text" name="email" placeholder="Email"/>
            <br>
            <input type="submit" name="register" value="REGISTER"/>
            </form>
            </CENTER>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
    <!-- /.container -->

    <div class="container">

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Tyler Cantrell 2016</p>
                </div>
            </div>
        </footer>

    </div>
    

</body>
</html>