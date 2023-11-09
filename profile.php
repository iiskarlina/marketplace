<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['update_profile'])){

    $update_user_id = $_POST['update_u_id'];
    $update_name = $_POST['update_name'];
    $update_email = $_POST['update_email'];
    $update_jenis_kelamin = $_POST['update_jenis_kelamin'];
    $update_ttl = $_POST['update_ttl'];
    $update_alamat = $_POST['update_alamat'];
 
    mysqli_query($conn, "UPDATE `users` SET name = '$update_name', email = '$update_email', jenis_kelamin = '$update_jenis_kelamin', ttl = '$update_ttl', alamat = '$update_alamat' WHERE id = '$update_user_id'") or die('query failed');
    
    header('location:profile.php');
 
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Profile</h3>
   <p> <a href="home.php">home</a> / Profile </p>
</div>

<section class="reviews">

    <?php
        $select_users_row = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
        $fetch_user = mysqli_fetch_assoc($select_users_row);
    ?>

   <h1 class="title"><?php echo $fetch_user['name'] ?></h1>

   <div class="box-container">

      <div class="box">
        <p>Name : <strong><?php echo $fetch_user['name'] ?></strong></p>
        <p>Email : <strong><?php echo $fetch_user['email'] ?></strong></p>
        <p>Jenis Kelamin : <strong><?php echo $fetch_user['jenis_kelamin'] ?></strong></p>
        <p>Tempat, Tanggal Lahir : <strong><?php echo $fetch_user['ttl'] ?></strong></p>
        <p>Alamat : <strong><?php echo $fetch_user['alamat'] ?></strong></p>
        <a href="profile.php?update=<?php echo $fetch_user['id'] ?>" class="btn">Edit Profile</a>

        <?php
            $select_kartu_identitas = mysqli_query($conn, "SELECT * FROM `kartu_identitas` WHERE user_id = '$user_id'") or die('Query failed');
            if(mysqli_num_rows($select_kartu_identitas) > 0) {
        ?>            
            <a href="member_card.php?id=<?php echo $fetch_user['id'] ?>" class="option-btn">Member Card</a>
        <?php
            } else {
        ?>
            <a href="register_card.php" class="option-btn">Register Member Card</a>
        <?php
            }
        ?>
      </div>

   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_u_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="enter your name">
      <input type="text" name="update_email" value="<?php echo $fetch_update['email']; ?>" class="box" required placeholder="enter your email">
      <select name="update_jenis_kelamin" class="box" required>
        <option value="">- Select your gender -</option>
        <option value="Male" <?php if($fetch_update['jenis_kelamin']=='Male'){ echo 'selected'; } ?>>Male</option>
        <option value="Female" <?php if($fetch_update['jenis_kelamin']=='Female'){ echo 'selected'; } ?>>Female</option>
      </select>
      <input type="text" name="update_ttl" value="<?php echo $fetch_update['ttl']; ?>" class="box" required placeholder="enter your place and date of birth">
      <textarea name="update_alamat" class="box" rows="10" placeholder="Enter your address" required><?php echo $fetch_update['alamat']; ?></textarea>
      <input type="submit" value="update" name="update_profile" class="btn">
      <input type="reset" value="cancel" id="close-update" class="option-btn">
   </form>
   <?php
         }
      }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

</section>


<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
<script>
    document.querySelector('#close-update').onclick = () =>{
        document.querySelector('.edit-product-form').style.display = 'none';
        window.location.href = 'profile.php';
    }
</script>

</body>
</html>