
document.addEventListener("DOMContentLoaded", () => {
 

  // Close overlay
  document.getElementById("overlay").addEventListener("click", function () {
    document.querySelectorAll(".modal-overlay.show").forEach((modal) => {
      modal.classList.remove("show");
    });
    this.classList.remove("show");
  });

  
  // Show modal function
  function showModal(modalId) {
    document.getElementById(modalId).classList.add("show");
    document.getElementById("overlay").classList.add("show");
  }

  // Close modal function
  function closeModal(modalId) {
    document.getElementById(modalId).classList.remove("show");
    document.getElementById("overlay").classList.remove("show");
  }

  // Make functions global for onclick handlers
  window.editPartner = function (index) {
    currentEditIndex = index;
    document.getElementById("editPartnerName").value = partners[index];
    showModal("editPartnerModal");
  };

  window.deletePartner = function (index) {
    currentDeleteIndex = index;
    document.getElementById("partnerToDelete").textContent = partners[index];
    showModal("deletePartnerModal");
  };

  window.showModal = showModal;
  window.closeModal = closeModal;
});