// Sélection de l’icône
const icon = document.getElementById("toggleIcon");

// Appliquer le mode sombre si sauvegardé
if (localStorage.getItem('darkMode') === 'enabled') {
  document.body.classList.add('dark-mode');
  icon.classList.remove("fa-brightness-low");
  icon.classList.add("fa-moon");
}

// Fonction pour basculer le mode sombre
function toggleDarkMode() {
  document.body.classList.toggle('dark-mode');

  if (document.body.classList.contains('dark-mode')) {
    localStorage.setItem('darkMode', 'enabled');
    icon.classList.remove("fa-sun");
    icon.classList.add("fa-moon");
  } else {
    localStorage.setItem('darkMode', 'disabled');
    icon.classList.remove("fa-moon");
    icon.classList.add("fa-sun");
  }
}

