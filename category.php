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
   <title>Category</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<?php
    $category = str_replace('-', ' ', $_GET['category']);
    $select_category_row = mysqli_query($conn, "SELECT * FROM `kategori_produk` WHERE nama_kategori = '$category'") or die('query failed');
    $fetch_category = mysqli_fetch_assoc($select_category_row);
?>

<div class="heading">
   <h3>Category <?php echo $fetch_category['nama_kategori'] ?></h3>
   <p> <a href="home.php">home</a> / <?php echo $fetch_category['nama_kategori'] ?> </p>
</div>

<section class="about">

   <div class="flex">    

      <div class="image">
        <img src="images/about-img.jpeg" alt="">
      </div>

      <div class="content">
         <h3><?php echo $fetch_category['nama_kategori'] ?></h3>
         <p><?php echo $fetch_category['deskripsi'] ?></p>
      </div>

   </div>

</section>

<section class="products">

   <h1 class="title">Products <?php echo $fetch_category['nama_kategori'] ?></h1>

   <div class="box-container">

      <?php  
        $kode_kategori = $fetch_category['kode_kategori'];
        $select_products = mysqli_query($conn, "SELECT products.*, kategori_produk.nama_kategori FROM `products` JOIN kategori_produk ON products.kode_kategori=kategori_produk.kode_kategori WHERE products.kode_kategori = '$kode_kategori'") or die('query failed');
        if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
     <form action="" method="post" class="box">
      <a href="detail.php?id=<?= $fetch_products['id'] ?>">
         <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <div class="name">Stock : <?php echo $fetch_products['stok']; ?></div>
         <div class="price">Rp<?php echo $fetch_products['price']; ?>/-</div>
         <input type="number" min="1" name="product_quantity" value="1" class="qty">
         <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
         <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
         <input type="submit" value="add to cart" name="add_to_cart" class="btn">
      </a>
     </form>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

</section>


<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>