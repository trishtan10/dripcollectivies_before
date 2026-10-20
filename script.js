document.addEventListener('DOMContentLoaded', function() {
  const links = document.querySelectorAll('a[href^="#"]');
  links.forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });


  if ($('#heroCarousel').length) {
    $('#heroCarousel').carousel({
      interval: 5000
    });
  }
});


function changeMainImage(el) {
  const mainImage = document.getElementById('mainImage');
  if (mainImage) mainImage.src = el.src;
}

function openImageModal(src) {
  const modalImage = document.getElementById('modalImage');
  if (modalImage) {
    modalImage.src = src;
    $('#imageModal').modal('show');
  }
}


$(document).ready(function() {
  const cartForm = $('#addToCartForm');
  if (cartForm.length) {
    cartForm.on('submit', function(e) {
      e.preventDefault();

      $.ajax({
        type: 'POST',
        url: 'add_to_cart.php',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
          if (response.status === 'success') {
            $('#cartModal').modal('show');
          } else {
            alert('Error adding to cart.');
          }
        },
        error: function() {
          alert('Network error. Please try again.');
        }
      });
    });
  }
});
