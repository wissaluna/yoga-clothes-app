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
