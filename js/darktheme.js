const toggle = document.getElementById('theme-toggle');

// Vérifie si une préférence de thème existe et l'applique
window.addEventListener('load', () => {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        document.body.classList.add(savedTheme);
    } else {
        // Par défaut, applique le thème clair
        document.body.classList.add('dark-theme');
    }
});

toggle.addEventListener('click', () => {
    // Bascule entre les thèmes
    document.body.classList.toggle('dark-theme');
    document.body.classList.toggle('light-theme');

    // Sauvegarde le thème actuel dans localStorage
    const currentTheme = document.body.classList.contains('dark-theme') ? 'dark-theme' : 'light-theme';
    localStorage.setItem('theme', currentTheme);
});
