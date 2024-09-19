let slideIndex = 0;
let autoSlideInterval;

function showSlides() {
    const slides = document.querySelectorAll('.banner-image img');
    if (slideIndex >= slides.length) {
        slideIndex = 0;
    } else if (slideIndex < 0) {
        slideIndex = slides.length - 1;
    }
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = 'none';
    }
    slides[slideIndex].style.display = 'block';
}

function nextSlide() {
    slideIndex++;
    showSlides();
    startAutoSlide();
}

function prevSlide() {
    slideIndex--;
    showSlides();
    startAutoSlide();
}

function startAutoSlide() {
    clearInterval(autoSlideInterval);
    autoSlideInterval = setInterval(nextSlide, 5000);
}

function stopAutoSlide() {
    clearInterval(autoSlideInterval);
}

showSlides();
startAutoSlide();

document.querySelector('.prev').addEvent
