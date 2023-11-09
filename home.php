<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="carousel-container">
   <div class="carousel-item fade">
      <img src="images/home-bg.jpeg" class="carousel-image">
      <div class="carousel-text">
         <div class="content">
            <h3>Exclusive Skincare</h3>
            <p>Find the right beauty products for you and your home! Shop our store's latest makeup, skincare, hair care, and fragrance products.</p>
            <a href="about.php" class="white-btn">discover more</a>
         </div>
      </div>
   </div>
   <div class="carousel-item fade">
      <img src="images/home.jpeg" class="carousel-image">
      <div class="carousel-text">
         <div class="content">
            <h3>Exclusive Skincare</h3>
            <p>Find the right beauty products for you and your home! Shop our store's latest makeup, skincare, hair care, and fragrance products.</p>
            <a href="about.php" class="white-btn">discover more</a>
         </div>
      </div>
   </div>
   <div class="carousel-item fade">
      <img src="images/about-img.jpeg" class="carousel-image">
      <div class="carousel-text">
         <div class="content">
            <h3>Exclusive Skincare</h3>
            <p>Find the right beauty products for you and your home! Shop our store's latest makeup, skincare, hair care, and fragrance products.</p>
            <a href="about.php" class="white-btn">discover more</a>
         </div>
      </div>
   </div>
   
   <!-- Previous and Next Buttons -->
   <a class="previous" onclick="plusSlides(-1)"><i class="fa fa-arrow-left"></i></a>
   <a class="next" onclick="plusSlides(1)"><i class="fa fa-arrow-right"></i></a>
</div>

<div class="navigation">
   <span class="navigation-dot" onclick="currentSlide(1)"></span>
   <span class="navigation-dot" onclick="currentSlide(2)"></span>
   <span class="navigation-dot" onclick="currentSlide(3)"></span>
</div>

<!-- <section class="home">

   <div class="content">

      <h3>Exclusive Skincare</h3>
      <p>Find the right beauty products for you and your home! Shop our store's latest makeup, skincare, hair care, and fragrance products.</p>
      <a href="about.php" class="white-btn">discover more</a>
   </div>

</section> -->

<section class="products">

   <h1 class="title">latest products</h1>

   <div class="box-container">

      <?php  
         $select_products = mysqli_query($conn, "SELECT products.*, kategori_produk.nama_kategori FROM `products` JOIN kategori_produk ON products.kode_kategori=kategori_produk.kode_kategori LIMIT 6") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
     <form action="" method="post" class="box">
      <a href="detail.php?id=<?= $fetch_products['id'] ?>">
         <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <div class="name">Category : <?php echo $fetch_products['nama_kategori']; ?></div>
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

   <div class="load-more" style="margin-top: 2rem; text-align:center">
      <a href="shop.php" class="option-btn">load more</a>
   </div>

</section>

<section class="about">

   <div class="flex">

      <div class="image">
         <img src="images/about-img.jpeg" alt="">
      </div>

      <div class="content">
         <h3>about us</h3>
         <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Impedit quos enim minima ipsa dicta officia corporis ratione saepe sed adipisci?</p>
         <a href="about.php" class="btn">read more</a>
      </div>

   </div>

</section>

<section class="home-contact">

   <div class="content">
      <h3>have any questions?</h3>
      <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Atque cumque exercitationem repellendus, amet ullam voluptatibus?</p>
      <a href="contact.php" class="white-btn">contact us</a>
   </div>

</section>





<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>