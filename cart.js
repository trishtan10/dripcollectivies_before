$(document).ready(function() {
  function updateCart(id, size, quantity) {
    $.post('update_cart.php', { id: id, size: size, quantity: quantity }, function(response) {
      const res = JSON.parse(response);
      if (res.status === 'success') {
        location.reload(); 
      }
    });
  }

  $('.quantity-input').on('change', function() {
    const row = $(this).closest('tr');
    const id = row.data('id');
    const size = row.find('.size-select').val() || 'N/A';
    const quantity = $(this).val();
    updateCart(id, size, quantity);
  });

  $('.size-select').on('change', function() {
    const row = $(this).closest('tr');
    const id = row.data('id');
    const size = $(this).val();
    const quantity = row.find('.quantity-input').val();
    updateCart(id, size, quantity);
  });
});
