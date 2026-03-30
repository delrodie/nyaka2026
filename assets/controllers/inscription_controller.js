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
        event.preventDefault();

        const form = event.currentTarget;
        const formData = new FormData(form);
        const submitBtn = this.submitButtonTarget;

        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = "Traitement en cours...";

        try {
            const data = Object.fromEntries(formData.entries());

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'  // ← Indique au serveur qu'on veut du JSON
                },
                body: JSON.stringify(data)
            });

            // ─── Vérification du Content-Type AVANT de parser ───────────────────
            const contentType = response.headers.get('Content-Type') ?? '';

            if (!contentType.includes('application/json')) {
                // Le serveur a renvoyé du HTML (page d'erreur Symfony en prod)
                const rawText = await response.text();
                // On extrait le message utile si possible, sinon on affiche le statut HTTP
                throw new Error(
                    `Le serveur a renvoyé une erreur HTTP ${response.status}. `
                    + `Vérifiez les logs Symfony (var/log/prod.log). `
                    + `Réponse brute : ${rawText.substring(0, 200)}...`
                );
            }

            const result = await response.json();

            if (!response.ok) {
                // JSON reçu mais statut HTTP 4xx/5xx
                throw new Error(result.message || `Erreur serveur (${response.status})`);
            }

            if (result.success) {
                await this.initierPaiementWave(result);
            } else {
                throw new Error(result.message || "Erreur lors de l'enregistrement");
            }

        } catch (error) {
            console.error("Erreur:", error);
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
                success_url: "https://nyaka.cvav-diocesedabidjan.org/recu/" + data.matricule,
            };

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
