<html>
<head><title>Login Page</title></head>
<h2>LOGIN HERE:</h2><br>
<body>
  <?php
  session_start();
  error_reporting(E_ALL);
  ini_set('display_errors','On');

  if(isset($_POST['uname'])&&isset($_POST['pass'])){

    $usr=$_POST['uname'];
    $psw=$_POST['pass'];

    try {
      $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
      $dbh->beginTransaction();
      $stmt = $dbh->prepare("select * from users where username='".$usr."' AND password='".md5($psw)."'");

      $stmt->execute();
      if($stmt->rowCount() > 0){
        $_SESSION['loginuser']= $usr;
        header("Location: board.php?"); /* Redirect browser */
        exit();
      }
      else{
        print ("Incorrect username/password combination: Please Enter valid credentials!");
        print("<br>");print("<br>");
      }
    } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  }
  ?>

<div id="panel">
  <form action="login.php" method="POST">
    Username: <input name ="uname" type="text"></input><br><br>
    Password: <input name ="pass" type="password"></input><br><br>
    <input type="submit" value="LOGIN"> </input>
  </form>
</div>

</body>
</html>
