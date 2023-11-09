<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_product'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = $_POST['price'];
   $kode_kategori = $_POST['kode_kategori'];
   $deskripsi = $_POST['deskripsi'];
   $stok = $_POST['stok'];

   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

   if(mysqli_num_rows($select_product_name) > 0){
      $message[] = 'product name already added';
   }else{
      $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, price, image, kode_kategori, deskripsi, stok) VALUES('$name', '$price', '$image', '$kode_kategori', '$deskripsi', '$stok')") or die('query failed');

      if($add_product_query){
         if($image_size > 2000000){
            $message[] = 'image size is too large';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'product added successfully!';
         }
      }else{
         $message[] = 'product could not be added!';
      }
   }
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
}

if(isset($_POST['update_product'])){

   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];
   $update_kode_kategori = $_POST['update_kode_kategori'];
   $update_deskripsi = $_POST['update_deskripsi'];
   $update_stok = $_POST['update_stok'];

   mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price', kode_kategori = '$update_kode_kategori', deskripsi = '$update_deskripsi', stok = '$update_stok' WHERE id = '$update_p_id'") or die('query failed');

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'image file size is too large';
      }else{
         mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/'.$update_old_image);
      }
   }

   header('location:admin_products.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- product CRUD section starts  -->

<section class="add-products">

   <h1 class="title">shop products</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>add product</h3>
      <input type="text" name="name" class="box" placeholder="enter product name" required>
      <input type="number" min="0" name="price" class="box" placeholder="enter product price" required>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <select name="kode_kategori" class="box" required>
         <option value="">- Select Category Product -</option>
         <?php
            $select_category = mysqli_query($conn, "SELECT * FROM `kategori_produk`") or die('query failed');
            if(mysqli_num_rows($select_category) > 0){
               while($fetch_category = mysqli_fetch_assoc($select_category)){
         ?>
            <option value="<?= $fetch_category['kode_kategori'] ?>"><?= $fetch_category['nama_kategori'] ?></option>
         <?php
               }
            }
         ?>
      </select>
      <textarea name="deskripsi" class="box" rows="10" placeholder="enter product description" required></textarea>
      <input type="number" min="0" name="stok" class="box" placeholder="enter product stock" required>
      <input type="submit" value="add product" name="add_product" class="btn">
   </form>

</section>

<!-- product CRUD section ends -->

<!-- show products  -->

<section class="show-products">

   <div class="box-container">

      <?php
         $select_products = mysqli_query($conn, "SELECT products.*, kategori_produk.nama_kategori FROM `products` JOIN kategori_produk ON products.kode_kategori=kategori_produk.kode_kategori") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <div class="box">
         <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_products['name'].' - '.$fetch_products['nama_kategori']; ?></div>
         <div class="price">Rp<?php echo $fetch_products['price']; ?>/-</div>
         <div class="name"><?php echo $fetch_products['deskripsi']; ?></div>
         <div class="price">Stock : <?php echo $fetch_products['stok']; ?></div>
         <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

</section>

<section class="edit-product-form product">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <div class="row">
         <div class="col-12">
            <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
            <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
            <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
         </div>
         <div class="col-6">
            <div class="form-group text-left">
               <label>Product Name</label>
               <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="enter product name">
            </div>
         </div>
         <div class="col-6">
            <div class="form-group text-left">
               <label>Product Price</label>
               <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="enter product price">
            </div>
         </div>
         <div class="col-6">
            <div class="form-group text-left">
               <label>Product Image</label>
               <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
            </div>
         </div>
         <div class="col-6">
            <div class="form-group text-left">
               <label>Category Product</label>
               <select name="update_kode_kategori" class="box" required>
                  <option value="">- Select Category Product -</option>
                  <?php
                     $select_category = mysqli_query($conn, "SELECT * FROM `kategori_produk`") or die('query failed');
                     if(mysqli_num_rows($select_category) > 0){
                        while($fetch_category = mysqli_fetch_assoc($select_category)){
                  ?>
                     <option value="<?= $fetch_category['kode_kategori'] ?>" <?php if($fetch_update['kode_kategori']==$fetch_category['kode_kategori']){ echo 'selected'; } ?>><?= $fetch_category['nama_kategori'] ?></option>
                  <?php
                        }
                     }
                  ?>
               </select>
            </div>
         </div>
         <div class="col-12">
            <div class="form-group text-left">
               <label>Product Description</label>
               <textarea name="update_deskripsi" class="box" rows="5" placeholder="enter product description" required><?php echo $fetch_update['deskripsi']; ?></textarea>
            </div>
         </div>
         <div class="col-12">
            <div class="form-group text-left">
               <label>Product Stock</label>
               <input type="number" min="0" name="update_stok" class="box" value="<?php echo $fetch_update['stok']; ?>" placeholder="enter product stock" required>
            </div>
         </div>
      </div>

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
      window.location.href = 'admin_products.php';
   }
</script>

</body>
</html>