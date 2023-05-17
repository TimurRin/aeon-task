const loggedInBlock = document.getElementById("logged-in");
const nonLoggedInBlock = document.getElementById("non-logged-in");

const userInfoBlock = document.getElementById("user-info");
const loginFormBlock = document.getElementById("login-form");
const authorizedMessage = document.getElementById("authorized-message");
const unauthorizedMessage = document.getElementById("unauthorized-message");

let messageTimeout;

function showMessage(messageDiv, message) {
  messageDiv.style.display = "block";
  messageDiv.textContent = message;
  messageDiv.style.animation = "none";
  messageDiv.offsetHeight;
  messageDiv.style.animation = "";

  clearTimeout(messageTimeout);
  messageTimeout = setTimeout(() => {
    messageDiv.style.display = "none";
  }, 10000);
}

function showUserInfo(user) {
  let userInfoDiv = document.getElementById("user-info");
  userInfoDiv.innerHTML = `
        <h2>${user.name}</h2>
        <img src="${user.photo}" alt="User photo" />
        <p>Date of Birth: ${user.dob}</p>
    `;
  loggedInBlock.style.display = "block";
  nonLoggedInBlock.style.display = "none";
}

function showLoginForm() {
  loggedInBlock.style.display = "none";
  nonLoggedInBlock.style.display = "block";
}

function login() {
  const username = document.getElementById("username").value;
  const password = document.getElementById("password").value;

  fetch("login.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ username, password }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        showUserInfo(data.user);
        showMessage(authorizedMessage, data.message);
        document.getElementById("username").value = ""
        document.getElementById("password").value = ""
      } else {
        showMessage(unauthorizedMessage, data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

function logout() {
  fetch("logout.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        showLoginForm();
        showMessage(unauthorizedMessage, data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}
