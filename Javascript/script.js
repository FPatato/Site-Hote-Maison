
  const nav = document.getElementById('main-nav');
  const navOffset = nav.offsetTop;

  window.addEventListener('scroll', () => {
    if (window.scrollY >= navOffset) {
      nav.classList.add('fixed');
    } else {
      nav.classList.remove('fixed');
    }
  });

  const slides = document.querySelectorAll('.slide');
  let current = 0;

  setInterval(() => {
    slides[current].classList.remove('active');
    current = (current + 1) % slides.length;
    slides[current].classList.add('active');
  }, 5000); // change toutes les 5 secondes

  // Premier menu déroulant
  function toggleMenu() {
    let dropdown = document.getElementById("myDropdown");
    let arrow = document.getElementById("arrow");

    dropdown.classList.toggle("show");
    arrow.classList.toggle("rotate");
  }

  // Deuxième menu déroulant
  function toggleMenu2() {
    let dropdown = document.getElementById("myDropdown2");
    let arrow = document.getElementById("arrow2");

    dropdown.classList.toggle("show");
    arrow.classList.toggle("rotate");
  }

  // Troisième menu déroulant
  function toggleMenu3() {
    let dropdown = document.getElementById("myDropdown3");
    let arrow = document.getElementById("arrow3");

    dropdown.classList.toggle("show");
    arrow.classList.toggle("rotate");
  }

  // Fermer si clic ailleurs
  window.onclick = function(event) {
    if (!event.target.closest('.dropdown')) {
      let dropdowns = document.getElementsByClassName("dropdown-content");
      let arrows = document.getElementsByClassName("arrow");

      for (let i = 0; i < dropdowns.length; i++) {
        dropdowns[i].classList.remove('show');
      }
      for (let i = 0; i < arrows.length; i++) {
        arrows[i].classList.remove('rotate');
      }
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
  const toggleBtn = document.querySelector('.menu-toggle');
  const navLinks = document.querySelector('.nav-bar ul');

  toggleBtn.addEventListener('click', () => {
    navLinks.classList.toggle('open');
  });
});