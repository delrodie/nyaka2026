import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["profilSelect", "sizeZone", "montantAffiche", "montantInput"]
    static values = {
        grade: String,
        tarifs: Object // Contient { "benjamin": 5000, "cadet": 5000, ... }
    }

    connect() {
        this.updateForm()
    }

    /**
     * Déclenché lorsque le champ grade (autocomplete Symfony/TomSelect) change.
     * On remonte le texte de l'option sélectionnée comme valeur de grade.
     */
    gradeChanged(event) {
        const select = event.target
        const selectedOption = select.options[select.selectedIndex]
        // On prend le texte affiché de l'option (le nom du grade), pas l'ID
        this.gradeValue = selectedOption ? selectedOption.text.toLowerCase().trim() : ''
        this.updateForm()
    }

    updateForm() {
        const grade = this.gradeValue.toLowerCase()
        const profil = this.profilSelectTarget.value

        // 1. Masquer toutes les zones de taille
        this.sizeZoneTargets.forEach(el => el.classList.add("d-none"))

        // 2. Si aucun grade sélectionné et profil non "Comité", on affiche 0 F et on s'arrête
        if (!grade && profil !== "Comité d'organisation") {
            if (this.hasMontantAfficheTarget) {
                this.montantAfficheTarget.textContent = '0 F'
            }
            if (this.hasMontantInputTarget) {
                this.montantInputTarget.value = 0
            }
            return
        }

        // 3. Gestion des zones de taille et du tarif selon le profil / grade
        if (profil === "Comité d'organisation") {
            document.getElementById("selectAP").classList.remove("d-none")
            this.calculerTarif("comité")
        } else {
            if (grade.includes("benjamin") || grade.includes("cadet")) {
                document.getElementById("selectBenjamin").classList.remove("d-none")
            } else if (grade.includes("aîné") || grade.includes("ainé") || grade.includes("meneur")) {
                document.getElementById("selectAine").classList.remove("d-none")
            } else if (grade.includes("aa") || grade.includes("ac")) {
                document.getElementById("selectAA").classList.remove("d-none")
            } else if (grade.includes("ap") || grade.includes("aphg")) {
                document.getElementById("selectAP").classList.remove("d-none")
            }

            this.calculerTarif(grade)
        }
    }

    calculerTarif(gradeRecherche) {
        let montant = 5000

        Object.keys(this.tarifsValue).forEach(nomGrade => {
            if (gradeRecherche.includes(nomGrade)) {
                montant = this.tarifsValue[nomGrade]
            }
        })

        if (this.hasMontantAfficheTarget) {
            this.montantAfficheTarget.textContent = `${montant} F`
        }
        if (this.hasMontantInputTarget) {
            this.montantInputTarget.value = montant
        }
    }
}
