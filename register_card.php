<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['submit'])){

   $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
   $jenis_kelamin = $_POST['jenis_kelamin'];
   $hobi = $_POST['hobi'];
   $tempat_lahir = $_POST['tempat_lahir'];
   $tanggal_lahir = $_POST['tanggal_lahir'];
   $alamat = $_POST['alamat'];

   $foto = $_FILES['foto']['name'];
   $foto_size = $_FILES['foto']['size'];
   $foto_tmp_name = $_FILES['foto']['tmp_name'];
   $foto_folder = 'uploaded_img/'.$foto;

   $select_users = mysqli_query($conn, "SELECT * FROM `kartu_identitas` WHERE email = '$email' OR nama_lengkap = '$nama_lengkap' OR no_hp = '$no_hp'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){
      $message[] = 'user already exist!';
   }else{
      
        $add_kartu_identitas = mysqli_query($conn, "INSERT INTO `kartu_identitas`(
                user_id,
                nama_lengkap, 
                email, 
                jenis_kelamin, 
                tempat_lahir, 
                tanggal_lahir, 
                alamat, 
                no_hp, 
                hobi, 
                foto
            ) 
            VALUES(
                '$user_id', 
                '$nama_lengkap', 
                '$email', 
                '$jenis_kelamin', 
                '$tempat_lahir', 
                '$tanggal_lahir', 
                '$alamat', 
                '$no_hp', 
                '$hobi',
                '$foto'
            )") or die('query failed');

        if ($add_kartu_identitas) {
            if($foto_size > 2000000){
                $message[] = 'image size is too large';
            }else{
                move_uploaded_file($foto_tmp_name, $foto_folder);
                $message[] = 'member card identity added successfully!';
                header('location:profile.php');
            }
        } else {
            $message[] = 'member card identity could not be added!';
        }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register Member Identity Card</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>



<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
   
<div class="form-container">

    <form action="" method="post" enctype="multipart/form-data">
        <h3>register member identity card</h3>
        <div class="form-group text-left">
            <label>Fullname <i class="text-danger">*</i></label>
            <input type="text" name="nama_lengkap" placeholder="Enter your name" required class="box">
        </div>
        <div class="form-group text-left">
            <label>Email <i class="text-danger">*</i></label>
            <input type="email" name="email" placeholder="Enter your email" required class="box">
        </div>
        <div class="form-group text-left">
            <label>No. Handphone <i class="text-danger">*</i></label>
            <input type="number" name="no_hp" placeholder="Enter your number handphone" required class="box">
        </div>
        <div class="form-group text-left">
            <label>Place of birth <i class="text-danger">*</i></label>
            <input type="text" name="tempat_lahir" placeholder="Enter your place of birth" required class="box">
        </div>
        <div class="form-group text-left">
            <label>Date of birth <i class="text-danger">*</i></label>
            <input type="date" name="tanggal_lahir" placeholder="Enter your date of birth" required class="box">
        </div>
        <div class="form-group text-left">
            <label>Gender <i class="text-danger">*</i></label>
            <select name="jenis_kelamin" class="box" required>
                <option value="">- Select Gender -</option>
                <option value="Laki-Laki">Male</option>
                <option value="Perempuan">Female</option>
            </select>
        </div>
        <div class="form-group text-left">
            <label>Address <i class="text-danger">*</i></label>
            <textarea name="alamat" class="box" rows="5" required placeholder="enter your address"></textarea>
        </div>
        <div class="form-group text-left">
            <label>Hobby <i class="text-danger">*</i></label>
            <input type="text" name="hobi" placeholder="Enter your hobby" required class="box">
        </div>
        <div class="form-group text-left">
            <label>Photo <i class="text-danger">*</i></label>
            <input type="file" name="foto" placeholder="Enter your photo" required class="box">
        </div>
        <input type="submit" name="submit" value="register now" class="btn">
        <a href="profile.php" class="option-btn">Back</a>
    </form>

</div>

</body>
</html>