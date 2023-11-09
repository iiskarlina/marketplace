<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_product'])){

   $nama_kategori = mysqli_real_escape_string($conn, $_POST['name']);
   $deskripsi = $_POST['deskripsi'];   

   $select_category_product_name = mysqli_query($conn, "SELECT nama_kategori FROM `kategori_produk` WHERE nama_kategori = '$nama_kategori'") or die('query failed');

   if(mysqli_num_rows($select_category_product_name) > 0){
      $message[] = 'category product name already added';
   }else{
      $add_category_product_query = mysqli_query($conn, "INSERT INTO `kategori_produk`(nama_kategori, deskripsi) VALUES('$nama_kategori', '$deskripsi')") or die('query failed');

      if($add_category_product_query){
        $message[] = 'category product added successfully!';
      }else{
         $message[] = 'category product could not be added!';
      }
   }
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `kategori_produk` WHERE kode_kategori = '$delete_id'") or die('query failed');
   header('location:admin_kategori.php');
}

if(isset($_POST['update_product'])){

   $update_kode_kategori = $_POST['update_p_id'];
   $update_nama_kategori = $_POST['update_name'];
   $update_deskripsi = $_POST['update_deskripsi'];

   mysqli_query($conn, "UPDATE `kategori_produk` SET nama_kategori = '$update_nama_kategori', deskripsi = '$update_deskripsi' WHERE kode_kategori = '$update_kode_kategori'") or die('query failed');
   
   header('location:admin_kategori.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Category Products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- product CRUD section starts  -->

<section class="add-products">

   <h1 class="title">shop Category Products</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>add Category Product</h3>
      <input type="text" name="name" class="box" placeholder="Enter category product name" required>
      <textarea name="deskripsi" class="box" rows="10" placeholder="Enter category product description" required></textarea>
      <input type="submit" value="add category product" name="add_product" class="btn">
   </form>

</section>

<!-- product CRUD section ends -->

<!-- show products  -->

<section class="show-products">

   <div class="box-container">

      <?php
         $select_category_products = mysqli_query($conn, "SELECT * FROM `kategori_produk`") or die('query failed');
         if(mysqli_num_rows($select_category_products) > 0){
            while($fetch_category = mysqli_fetch_assoc($select_category_products)){
      ?>
      <div class="box">
         <!-- <img src="uploaded_img/<?php echo $fetch_category['image']; ?>" alt=""> -->
         <div class="name"><?php echo $fetch_category['nama_kategori']; ?></div>
         <div class="price"><?php echo $fetch_category['deskripsi']; ?></div>
         <a href="admin_kategori.php?update=<?php echo $fetch_category['kode_kategori']; ?>" class="option-btn">update</a>
         <a href="admin_kategori.php?delete=<?php echo $fetch_category['kode_kategori']; ?>" class="delete-btn" onclick="return confirm('delete this category product?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `kategori_produk` WHERE kode_kategori = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['kode_kategori']; ?>">
      <input type="text" name="update_name" value="<?php echo $fetch_update['nama_kategori']; ?>" class="box" required placeholder="enter product name">
      <textarea name="update_deskripsi" class="box" rows="10" placeholder="Enter category product description" required><?php echo $fetch_update['deskripsi']; ?></textarea>
      <input type="submit" value="update" name="update_product" class="btn">
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







<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>
<script>
    document.querySelector('#close-update').onclick = () =>{
        document.querySelector('.edit-product-form').style.display = 'none';
        window.location.href = 'admin_kategori.php';
    }
</script>

</body>
</html>