

var swiper = new Swiper(".mySwiper", {
    effect: "coverflow",
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: "auto",
    coverflowEffect: {
      rotate: 0,
      stretch: 0,
      depth: 300,
      modifier: 1,
      slideShadows: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true, 
    },
    autoplay: {
      delay: 3000, // 3000 ms = 3 seconds
      disableOnInteraction: false, // Continue autoplay after user interactions
    },
});



  var hidden = true;

  // Save the original value when the page loads
  var originalValue = document.getElementById('nominal-value').textContent.trim();
  console.log(originalValue);
  console.log(typeof originalValue)
  function clickPass() {
      const nominalValueElement = document.getElementById('nominal-value');
      const showHideIcon = document.getElementById('show_hide');
  
      if (!hidden) {
          // Show the original nominal value
          nominalValueElement.textContent = originalValue;
          showHideIcon.src = 'image/visible.png';
          hidden=true;
      } else {
          // Hide the nominal value with asterisks of the same length
          nominalValueElement.textContent = '*'.repeat(originalValue.length);
          showHideIcon.src = 'image/hide.png';
          hidden=false;
      }
  }
  