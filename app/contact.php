<?php
$title = 'contact';
include('header.php');
?>
<main>
    <h2>this a page contact</h2>
    <p>lien he voi chung toi qua email</p>
    <form method="post">
        <label for="username">username:</label>
        <input type="text" name="username">
        </br>

        <label for="password">password</label>
        <input type="password" name="password">
        </br>

        <button type="submit" >submit</button>
    

        <?php
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $username = $_POST['username'];
            $password = $_POST['password'];
            echo "<p> username : $username </p>";
            echo "<p> password : $password </p>";
        }
        ?>
    </form>
</main>
<?php
$title = 'ban quyen thuoc ve Nguyễn Nhật Ánh';
include('footer.php');
?>