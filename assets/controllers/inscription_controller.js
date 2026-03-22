import { Controller } from "@hotwired/stimulus";
import axios from "axios";

export default class extends Controller {
    static targets = ["montantInput", "submitButton"];
    static values = {
        grade: String,
        tarifs: Object
    }

    connect() {
        // Initialisation si nécessaire
    }

    async submitForm(event) {
        // 1. Empêcher le rechargement de la page
        event.preventDefault();

        const form = event.currentTarget;
        const formData = new FormData(form);
        const submitBtn = this.submitButtonTarget;

        // 2. État de chargement
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = "Traitement en cours...";

        try {
            // 3. Envoi des données au serveur Symfony (votre ParticipantController)
            // On transforme FormData en objet simple si votre API attend du JSON
            const data = Object.fromEntries(formData.entries());

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                // 4. Appel à l'API de paiement Wave (comme dans votre exemple jQuery)
                this.initierPaiementWave(result);
            } else {
                throw new Error(result.message || "Erreur lors de l'enregistrement");
            }

        } catch (error) {
            console.error("Erreur:", error);
            // Utilisation de SweetAlert2 si disponible dans votre projet
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oups...',
                    text: error.message || 'Une erreur est survenue.',
                });
            }
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    }

    async initierPaiementWave(data) {
        try {
            const checkoutParams = {
                amount: data.montant.toString(),
                currency: "XOF",
                error_url: window.location.origin + "/echec/" + data.matricule,
                success_url: "https://www.cvav-diocesedabidjan.org/recu/" + data.matricule,
            };

            // Appel à votre endpoint qui génère l'URL Wave
            const response = await axios.post('/api/wave/checkout', checkoutParams);

            const content = JSON.parse(response.data.content);
            if (content.wave_launch_url) {
                window.location.href = content.wave_launch_url;
            }
        } catch (error) {
            console.error("Erreur Wave:", error);
            this.submitButtonTarget.disabled = false;
        }
    }
}
