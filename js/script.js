document.addEventListener('DOMContentLoaded', function() {
    // Vérification titre vide
    const form = document.getElementById('form_ajout');
    if (form) {
        form.onsubmit = function(event) {
            const t = document.getElementById('id_titre').value;
            if (t.trim() == "") {
                alert("Mec, met un titre stp");
                event.preventDefault();
            }
        };
    }

    // Filtre par statut
    const select = document.getElementById('mon_filtre');
    if (select) {
        select.onchange = function() {
            const val = this.value;
            const trs = document.querySelectorAll('.tache-ligne');
            trs.forEach(tr => {
                const s = tr.getAttribute('data-statut');
                tr.style.display = (val == "toutes" || s == val) ? "" : "none";
            });
        };
    }
});