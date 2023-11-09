let userBox = document.querySelector('.header .header-2 .user-box');

document.querySelector('#user-btn').onclick = () =>{
   userBox.classList.toggle('active');
   navbar.classList.remove('active');
}

let navbar = document.querySelector('.header .header-2 .navbar');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   userBox.classList.remove('active');
}

window.onscroll = () =>{
   userBox.classList.remove('active');
   navbar.classList.remove('active');

   if(window.scrollY > 60){
      document.querySelector('.header .header-2').classList.add('active');
   }else{
      document.querySelector('.header .header-2').classList.remove('active');
   }
}

var currentIndex = 1;
      
//Show current image
showSlides(currentIndex);

//Function to move Next
function plusSlides(n) {
    showSlides(currentIndex += n);
}

//Function to move back
function currentSlide(n) {
    showSlides(currentIndex = n);
}


//Initiate moving of slides
function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("carousel-item");
    var dots = document.getElementsByClassName("navigation-dot");
    if (n > slides.length) {currentIndex = 1}
    if (n < 1) {currentIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[currentIndex-1].style.display = "block";
    dots[currentIndex-1].className += " active";
}