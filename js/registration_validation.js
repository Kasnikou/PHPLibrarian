function validateRegistrationForm() {
  var firstName = document.forms["registrationForm"]["m_user"].value;
  var lastName = document.forms["registrationForm"]["l_name"].value;
  var email = document.forms["registrationForm"]["m_email"].value;
  var password = document.forms["registrationForm"]["m_pass"].value;
  var confirmPassword =
    document.forms["registrationForm"]["m_pass_confirm"].value;

  // Validate first and last names
  if (
    !/^[a-zA-Z]{1,20}$/.test(firstName) ||
    !/^[a-zA-Z]{1,20}$/.test(lastName)
  ) {
    alert(
      "First and last names must contain valid alpha characters only and be no more than 20 characters in length."
    );
    return false;
  }

  // Validate email format
  if (!/\S+@\S+\.\S+/.test(email)) {
    alert("Email address must be a validly formed email address.");
    return false;
  }

  // You would need to check if the email is previously associated with a user in your database.
  // This check cannot be done on the client side and must be performed on the server side.

  // Validate that the passwords match
  if (password !== confirmPassword) {
    alert("Passwords must match.");
    return false;
  }

  // Validate password strength and organizational guidelines
  if (!isPasswordStrong(password)) {
    alert(
      "Password does not meet the organizational guidelines for passwords."
    );
    return false;
  }

  // If all validations pass
  return true;
}

// Example password strength checker function
function isPasswordStrong(password) {
  // This is a simple regex for demonstration; adjust the regex to match your password policy
  var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})");
  return strongRegex.test(password);
}
