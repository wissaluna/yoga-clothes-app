function validateRegisterForm() {
  const firstName = document.getElementById('firstname').value.trim();
  const lastName = document.getElementById('lastname').value.trim();
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value.trim();

  if (firstName === '' || lastName === '' || email === '' || password === '') {
    alert('Please fill out all required fields.');
    return false;
  }

  if (password.length < 6) {
    alert('Your password must be at least 6 characters long.');
    return false;
  }

  return true;
}

function validateLoginForm() {
  const email = document.getElementById('login-email').value.trim();
  const password = document.getElementById('login-password').value.trim();

  if (email === '' || password === '') {
    alert('Please enter both email and password.');
    return false;
  }
  return true;
}

document.addEventListener('DOMContentLoaded', () => {
  const addButtons = document.querySelectorAll('.add-to-cart-btn');

  addButtons.forEach((button) => {
    button.addEventListener('click', function () {
      const productId = this.getAttribute('data-id');

      fetch('actions/add_to_cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ product_id: productId }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            document.getElementById('cart-count').innerText = data.totalItems;

            const originalText = this.innerText;
            this.innerText = 'Added!';
            this.style.backgroundColor = '#10b981';
            this.style.color = 'white';

            setTimeout(() => {
              this.innerText = originalText;
              this.style.backgroundColor = '#0f172a';
            }, 1500);
          } else {
            alert('Error adding item to cart.');
          }
        })
        .catch((error) => {
          console.error('AJAX Error:', error);
        });
    });
  });
});
