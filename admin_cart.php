<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_POST['update_order'])){

   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
   $message[] = 'payment status has been updated!';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="orders">

   <h1 class="title">placed cart</h1>

   <div class="box-container">
      <?php
      $select_orders = mysqli_query($conn, "SELECT cart.*, users.name as user_name, users.email FROM `cart` JOIN users ON cart.user_id=users.id ORDER BY cart.id DESC") or die('query failed');
      if(mysqli_num_rows($select_orders) > 0){
         while($fetch_orders = mysqli_fetch_assoc($select_orders)){
      ?>
      <div class="box">
          <p> User Name : <span><?php echo $fetch_orders['user_name']; ?></span> </p>
          <p> User Email : <span><?php echo $fetch_orders['email']; ?></span> </p>
          <p> Product Name : <span><?php echo $fetch_orders['name']; ?></span> </p>
          <p> Product Price : <span>Rp<?php echo $fetch_orders['price']; ?></span> </p>
          <p> Quantity : <span><?php echo $fetch_orders['quantity']; ?></span> </p>
          <p>
            Product Image : 
            <img src="uploaded_img/<?php echo $fetch_orders['image'] ?>" style="width:100%;">
          </p>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      ?>
   </div>

</section>










<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>