var password_eye_icon = document.getElementById("togglePassword");
var password_slashEye_icon = document.getElementById("toggleBackPassword");
var confirm_eye_icon = document.getElementById("toggleConfirm");
var confirm_slashEye_icon = document.getElementById("toggleBackConfirm");
var password = document.getElementById("password");
var confirm = document.getElementById("confirm");
password_eye_icon.addEventListener("click", function () {
  if (password.type === "password") {
    password.type = "test";
    password_eye_icon.classList.add("d-none");
    password_slashEye_icon.classList.remove("d-none");
  }
});
password_slashEye_icon.addEventListener("click", function () {
  if (password.type === "text") {
    password.type = "password";
    password_eye_icon.classList.remove("d-none");
    password_slashEye_icon.classList.add("d-none");
  }
});
confirm_eye_icon.addEventListener("click", function () {
  if (confirm.type === "password") {
    confirm.type = "test";
    confirm_eye_icon.classList.add("d-none");
    confirm_slashEye_icon.classList.remove("d-none");
  }
});
confirm_slashEye_icon.addEventListener("click", function () {
  if (confirm.type === "text") {
    confirm.type = "password";
    confirm_eye_icon.classList.remove("d-none");
    confirm_slashEye_icon.classList.add("d-none");
  }
});
