document.addEventListener('DOMContentLoaded', () => {
  const toggleBtn = document.querySelector('.menu-toggle');
  const navLinks = document.querySelector('.nav-bar ul');

  toggleBtn.addEventListener('click', () => {
    navLinks.classList.toggle('open');
  });
});