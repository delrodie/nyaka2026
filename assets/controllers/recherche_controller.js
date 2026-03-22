import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["idZone", "nomZone", "radio", "doyenneSelect"]

    connect() {
        // Initialise l'affichage au chargement
        this.toggle()
        // Charge les doyennés au démarrage
        this.loadDoyennes()
        console.log('recherche')
    }

    /**
     * Bascule l'affichage entre ID et Nom
     */
    toggle() {
        const selectedValue = this.radioTargets.find(radio => radio.checked).value

        if (selectedValue === "id") {
            this.idZoneTarget.classList.remove("d-none")
            this.nomZoneTarget.classList.add("d-none")
        } else {
            this.idZoneTarget.classList.add("d-none")
            this.nomZoneTarget.classList.remove("d-none")
        }
    }

    /**
     * Charge les données depuis l'API Doyenne
     */
    async loadDoyennes() {
        try {
            const response = await fetch('/api/doyenne/');
            if (!response.ok) throw new Error("Erreur lors du chargement des doyennés");

            const data = await response.json();

            // On vide le select (sauf l'option par défaut)
            this.doyenneSelectTarget.innerHTML = '<option value="">Choisir un doyenné...</option>';

            // On remplit avec les données de l'API
            data.forEach(doyenne => {
                const option = document.createElement('option');
                option.value = doyenne.id;
                option.textContent = doyenne.nom;
                this.doyenneSelectTarget.appendChild(option);
            });
        } catch (error) {
            console.error("Erreur:", error);
        }
    }
}
