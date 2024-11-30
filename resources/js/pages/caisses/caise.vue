<template>
  <v-container>
    <v-card>
      <v-card-title>Gestion des Données</v-card-title>

      <v-card-text>
        <!-- Services Dropdown -->
        <VAutocomplete class="mb-4"
          v-model="selectedServices"
          :items="services"
          :loading="isServicesLoading"
          :error-messages="servicesErrorMessage"
          chips
          clearable
          label="Services"
          item-title="name"
          item-value="id"
          return-object
          @update:model-value="handleServiceSelection"
        >
          <template v-slot:no-data>
            <v-list-item>
              <v-list-item-title>
                {{ servicesErrorMessage || 'Aucun service trouvé' }}
              </v-list-item-title>
            </v-list-item>
          </template>
        </VAutocomplete>

        <!-- Clients Dropdown -->
        <VAutocomplete class="mb-4"
          v-model="selectedClient"
          :items="clients"
          :loading="isClientsLoading"
          :error-messages="clientsErrorMessage"
          clearable
          label="Client"
          item-title="name"
          item-value="id"
          return-object
          @update:model-value="handleClientSelection"
        >
          <template v-slot:no-data>
            <v-list-item>
              <v-list-item-title>
                {{ clientsErrorMessage || 'Aucun client trouvé' }}
              </v-list-item-title>
            </v-list-item>
          </template>
        </VAutocomplete>

        <!-- Montant -->
        <VTextField class="mb-4"
          v-model="montant"
          label="Montant"
          type="number"
          clearable
        ></VTextField>

        <!-- Versement -->
        <VTextField class="mb-4"
          v-model="versement"
          label="Versement"
          type="number"
          clearable
          @input="calculateReste"
        ></VTextField>

        <!-- Reste -->
        <VTextField class="mb-4"
          v-model="reste"
          label="Reste"
          type="number"
          readonly
        ></VTextField>
        <!-- Bouton Enregistrer -->
        <v-btn color="primary" @click="storePayment">Enregistrer</v-btn>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
import { ref, onMounted, watch } from "vue";
import { VTextField } from "vuetify/components";

export default {
  setup() {
    const services = ref([]);
    const clients = ref([]);
    const selectedServices = ref([]);
    const selectedClient = ref(null);

    // Fields for Montant, Versement, and Reste
    const montant = ref(0);
    const versement = ref(0);
    const reste = ref(0);

    // Loading and error states
    const isServicesLoading = ref(false);
    const isClientsLoading = ref(false);
    const servicesErrorMessage = ref("");
    const clientsErrorMessage = ref("");

    // Fetch Services
    const fetchServices = async () => {
      isServicesLoading.value = true;
      servicesErrorMessage.value = "";

      try {
        const response = await fetch("/api/services");
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

        const data = await response.json();
        const serviceData = data.data || data || [];
        services.value = serviceData.map((service) => ({
          id: service.id || service.ID,
          name: service.name || service.Name || "Service sans nom",
        }));

        if (services.value.length === 0) {
          servicesErrorMessage.value = "Aucun service trouvé";
        }
      } catch (error) {
        servicesErrorMessage.value = error.message || "Impossible de charger les services";
      } finally {
        isServicesLoading.value = false;
      }
    };

    // Fetch Clients
    const fetchClients = async () => {
      isClientsLoading.value = true;
      clientsErrorMessage.value = "";

      try {
        const response = await fetch("/api/clients");
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

        const data = await response.json();
        const clientData = data.data || data || [];
        console.log(clientData);
        clients.value = clientData.map((client) => ({
          id: client.id || client.ID,
          name: client.name || client.Name || "Client sans nom",
        }));

        if (clients.value.length === 0) {
          clientsErrorMessage.value = "Aucun client trouvé";
        }
      } catch (error) {
        clientsErrorMessage.value = error.message || "Impossible de charger les clients";
      } finally {
        isClientsLoading.value = false;
      }
    };

    // Calculate "Reste"
    const calculateReste = () => {
      reste.value = Math.max(0, montant.value - versement.value);
    };

    // Watch montant and versement for changes
    watch([montant, versement], calculateReste);
// Store Payment
const storePayment = async () => {
      try {
        const payload = {
          service_entry_id: selectedServices.value?.id,
          client_mail: selectedClient.value?.email,
          amount: montant.value,
          versed: versement.value,
          reste: reste.value,
        };

        const response = await axios.post("/api/payments", payload);
        alert("Paiement enregistré avec succès !");
        console.log(response.data);
      } catch (error) {
        alert("Erreur lors de l'enregistrement du paiement.");
        console.error(error.response?.data || error.message);
      }
    };
    onMounted(() => {
      fetchServices();
      fetchClients();
    });

    return {
      services,
      clients,
      selectedServices,
      selectedClient,
      montant,
      versement,
      reste,
      isServicesLoading,
      isClientsLoading,
      servicesErrorMessage,
      clientsErrorMessage,
      handleServiceSelection: (selected) => console.log("Services sélectionnés:", selected),
      handleClientSelection: (selected) => console.log("Client sélectionné:", selected),
      storePayment,
    };
  },
};
</script>
