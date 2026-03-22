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

    updateForm() {
        const grade = this.gradeValue.toLowerCase()
        const profil = this.profilSelectTarget.value

        // 1. Gestion des zones de saisie (Tailles)
        this.sizeZoneTargets.forEach(el => el.classList.add("d-none"))

        if (profil === "Comité d'organisation") {
            document.getElementById("selectAP").classList.remove("d-none")
            this.calculerTarif("comité") // Optionnel : tarif spécifique comité ?
        } else {
            // Affichage des tailles selon le grade
            if (grade.includes("benjamin") || grade.includes("cadet")) {
                document.getElementById("selectBenjamin").classList.remove("d-none")
            } else if (grade.includes("ainé") || grade.includes("meneur")) {
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
        // On cherche si le grade exact existe dans nos tarifs
        let montant = 5000

        // On boucle sur les clés des tarifs pour voir si le grade du membre y figure
        Object.keys(this.tarifsValue).forEach(nomGrade => {
            if (gradeRecherche.includes(nomGrade)) {
                montant = this.tarifsValue[nomGrade]
            }
        })

        // Mise à jour de l'UI
        if (this.hasMontantAfficheTarget) {
            this.montantAfficheTarget.textContent = `${montant} F`
        }
        if (this.hasMontantInputTarget) {
            this.montantInputTarget.value = montant
        }
    }
}
