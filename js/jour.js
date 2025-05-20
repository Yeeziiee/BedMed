const jours = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
    const mois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet',
                  'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    const today = new Date();
    const calendarDiv = document.getElementById('calendar-buttons');
    const monthYearDiv = document.getElementById('month-year');

    // Afficher les 3 prochains jours
    for (let i = 0; i < 3; i++) {
        const date = new Date();
        date.setDate(today.getDate() + i);
        const jour = jours[date.getDay()];
        const jourNum = date.getDate();

        const btn = document.createElement('button');
        btn.innerHTML = `${jour}<br>${jourNum}`;

        if (i === 0) btn.classList.add('active');  // le jour courant
        calendarDiv.appendChild(btn);
    }

    // Afficher mois et année
    const moisActuel = mois[today.getMonth()];
    const annee = today.getFullYear();
    monthYearDiv.innerText = `${moisActuel} | ${annee}`;