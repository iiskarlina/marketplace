<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Member Card Identity</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Member Card Identity</h3>
   <p> <a href="home.php">home</a> / Member Card Identity </p>
</div>

<?php
    $select_users_row = mysqli_query($conn, "SELECT * FROM `kartu_identitas` WHERE user_id = '$user_id'") or die('query failed');
    $fetch_user = mysqli_fetch_assoc($select_users_row);
    if ($fetch_user['jenis_kelamin']=='Laki-Laki') {
        $bg = 'blue';
    } else {
        $bg = 'red';
    }
?>

<section class="member-card">    

   <div class="box-container">

      <div class="box text-left" style="background-color: <?php echo $bg ?> !important;">
        <div class="d-flex">
            <div class="w-70">
                <p>Fullname : <strong><?php echo $fetch_user['nama_lengkap'] ?></strong></p>
                <p>Gender : <strong><?php echo $fetch_user['jenis_kelamin'] ?></strong></p>
                <p>Place, Date of birth : <strong><?php echo $fetch_user['tempat_lahir'].', '.$fetch_user['tanggal_lahir'] ?></strong></p>
                <p>Address : <strong><?php echo $fetch_user['alamat'] ?></strong></p>
                <p>Email : <strong><?php echo $fetch_user['email'] ?></strong></p>
                <p>Phone : <strong><?php echo $fetch_user['no_hp'] ?></strong></p>
                <p>Hobby : <strong><?php echo $fetch_user['hobi'] ?></strong></p>
            </div>
            <div class="w-30">
                <img src="uploaded_img/<?php echo $fetch_user['foto'] ?>" style="width: 150px; height: 150px">
            </div>
        </div>        
      </div>

      <a href="profile.php" class="btn">Back</a>
   </div>


</section>


<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>