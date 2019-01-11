<html>
<head><title>Message Board</title>
<style>
button:hover {
  background-color: green;
}
table {
    border-collapse: collapse;
    border: 1px solid black;
}
tr:nth-child(odd) {background-color: #f2f2f2;}
</style>
</head>
<h2 align="center">Welcome to Message Board</h2>
<body>
<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
session_start();

if(isset($_SESSION['loginuser'])){
  $usr = $_SESSION['loginuser'];
  print("Welcome $usr !");
}else{
    header("Location: login.php?"); /* Redirect browser */
    exit();
}
if(isset($_POST['logout'])){
  session_destroy();
  header("Location: login.php?");
}
?>
<div id="main">

    <div id="newmsg" style="float:left; ">
      <p><b>Type messages here:</b></p>
      <textarea name="msg" rows="4" cols="50" form="msgpostform"> </textarea>
      <br>
      <form id="msgpostform" action="board.php?" method="POST">
        <input type="submit" value="New Post"></input>
        </form>
      <br><br><br><br>
    </div>

    <div id="msglst" style="float:right; width: 600px;">
      <p><b>Message List:</b></p>
      <br><br>
      <table border=1>
      <?php
      try {

        $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $dbh->beginTransaction();
        $stmt = $dbh->prepare("select posts.*,users.fullname from posts JOIN users on posts.postedby = users.username order by datetime");
        $stmt->execute();
        if($stmt->rowCount() > 0){
          while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            print("<tr>");
            print("<td height=80 colspan=6><b>Message:</b> $row->message</td>");
            print("</tr>");
            print("<tr>");
            $repid =  $row->id;
            print("<td><b>Message ID :</b> $row->id</td>");
            print("<td><b>Posted By:</b> $row->postedby</td>");
            print("<td><b>Full Name:</b> $row->fullname</td>");
            print("<td><b>Date/Time:</b> $row->datetime</td>");
            print("<td><b>ReplyTo?:</b> $row->replyto</td>");
            print("<td><button name=replyto value=$repid type=submit formaction=board.php form=msgpostform>Reply</button></td>");
            print("</tr>");
            print("<td bgcolor=#FFFFFF style=line-height:10px; colspan=6>&nbsp;</td>");
          }
        }

        if(isset($_POST['msg'])){
          print_r("yaydfw ");
          print("post msg is set");
          $newp=$_POST['msg'];
          $uid = uniqid();
          $repto = null;

          if(isset($_POST['replyto'])){
            print_r("yaydfw ");
            print("replyto msg is set");
            $repto = $_POST['replyto'];
          }else{
            print_r("NNOOOo ");
            print("replyto msg not");
          }
          print_r($repto);
          $dbh->exec('insert into posts(id,replyto,postedby,datetime,message) VALUES("'.$uid.'","'.$repto.'","'.$usr.'",NOW() ,"'.$newp.'" )')
                or die(print_r($dbh->errorInfo(), true));
          $dbh->commit();
          unset($_POST['msg']);
          header("Location: " . $_SERVER['REQUEST_URI']);
          exit();
        }

      } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
      } ?>
      </table>
    </div>
</div>

</body>
<footer>
  <p>LOGOUT FROM HERE</p>
  <form action="board.php" method="POST">
    <input type="hidden" name="logout" value="1"/>
    <input type="submit" value="logout"></input>
  </form>

</footer>
</html>
