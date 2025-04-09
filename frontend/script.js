// Show login popup
function showLogin(role) {
  const loginPopup = document.getElementById("loginPopup");
  const loginTitle = document.getElementById("loginTitle");
  const loginRole = document.getElementById("loginRole");

  loginTitle.textContent = role.charAt(0).toUpperCase() + role.slice(1) + " Login";
  loginRole.value = role;
  loginPopup.style.display = "flex";
}  
  // Close the popup
  function closeLogin() {
    const loginPopup = document.getElementById("loginPopup");
    loginPopup.style.display = "none";
  }
  
  // Refresh the page for the Home button
  function refreshPage() {
    window.location.reload();
  }
  
  // Close popup if clicked outside
  window.onclick = function (event) {
    const popup = document.getElementById("loginPopup");
    if (event.target == popup) {
      popup.style.display = "none";
    }
  };