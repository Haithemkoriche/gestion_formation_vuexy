<script setup>
import { onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const serviceId = ref(route.params.id);
const serviceData = ref(null);
const serviceEntries = ref([]);
const tableHeaders = ref([]);
const dialogVisible = ref(false);
const dialogAction = ref('');
const formData = ref({});
const statusOptions = [
  'en attente', 'traité', 'refusé', 'en cours', 'validé', 'annulé', 'complété'
];

// Fonction pour charger les données du service en fonction de l'ID
async function loadServiceData() {
  try {
    const response = await fetch(`/api/services/${serviceId.value}`);
    serviceData.value = await response.json();

    // Configuration des headers dynamiques selon `required_fields`
    if (serviceData.value?.required_fields) {
      tableHeaders.value = serviceData.value.required_fields.map(field => ({
        title: field,
        key: field.toLowerCase().replace(/ /g, '_'),
      }));
    }

    // Ajout d'une colonne "Actions"
    tableHeaders.value.push({ title: 'Actions', key: 'actions' });

    // Chargement des entrées de service
    await fetchServiceEntries();
  } catch (error) {
    console.error('Erreur lors du chargement des données du service :', error);
  }
}

// Fonction pour charger les entrées de service
async function fetchServiceEntries() {
  try {
    const entriesResponse = await fetch(`/api/services/${serviceId.value}/entries`);
    serviceEntries.value = await entriesResponse.json();
  } catch (error) {
    console.error('Erreur lors du chargement des entrées de service :', error);
  }
}

// Mettre à jour les données lors de la première monture du composant
onMounted(loadServiceData);

// Mettre à jour les données si l'ID du service change
watch(
  () => route.params.id,
  (newId) => {
    serviceId.value = newId; // Mettre à jour l'ID du service
    loadServiceData();       // Recharger les données du service
  }
);

// Fonction pour ouvrir le dialogue d'ajout ou de modification
function openDialog(action, item = {}) {
  dialogAction.value = action;
  dialogVisible.value = true;
  formData.value = { ...item, status: item.status || 'en attente' };
}

// Fonction pour enregistrer une entrée
async function saveEntry() {
  try {
    const method = dialogAction.value === 'add' ? 'POST' : 'PUT';
    const url = dialogAction.value === 'add'
      ? `/api/services/${serviceId.value}/entries`
      : `/api/entries/${formData.value.id}`;

    await fetch(url, {
      method,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ entry_data: formData.value, status: formData.value.status }),
    });

    dialogVisible.value = false;
    await fetchServiceEntries();
  } catch (error) {
    console.error('Erreur lors de lenregistrement de lentrée :', error);
  }
}

// Fonction pour supprimer une entrée
async function deleteEntry(id) {
  try {
    await fetch(`/api/entries/${id}`, { method: 'DELETE' });
    dialogVisible.value = false;
    await fetchServiceEntries();
  } catch (error) {
    console.error('Erreur lors de la suppression de lentrée :', error);
  }
}
</script>


<template>
  <div>
    <VCard class="mb-6 d-flex justify-space-between align-center" :title="serviceData?.name">
      <v-btn color="primary" class="me-5" @click="openDialog('add')">Ajouter</v-btn>
    </VCard>

    <!-- DataTable for displaying service entries -->
    <VDataTable :headers="tableHeaders" :items="serviceEntries" item-key="id" class="elevation-1">
      <template #item="{ item }">
        <!-- Start a new row for each item -->
        <tr>
          <!-- Display fields dynamically based on headers -->
          <template v-for="header in tableHeaders" :key="header.key">
            <td v-if="header.key !== 'actions'" class="text-center">
              <!-- {{ item.entry_data[header.key] }} -->
              {{ header.key === 'status' ? item.status : item.entry_data[header.key] }}
            </td>
          </template>

          <!-- Action buttons (Edit and Delete) -->
          <td class="text-center">
            <VBtn class="me-2" icon @click="openDialog('edit', item)">
              <VIcon>tabler-pencil</VIcon>
            </VBtn>
            <VBtn color="#f00" icon @click="deleteEntry(item.id)">
              <v-icon>tabler-trash</v-icon>
            </VBtn>
          </td>
        </tr>
      </template>
    </VDataTable>

    <!-- Dialog for adding or editing an entry -->
    <v-dialog v-model="dialogVisible" max-width="500px">
      <v-card>
        <v-card-title>{{ dialogAction === 'add' ? 'Ajouter' : 'Modifier' }} une Entrée</v-card-title>
        <v-card-text>
          <v-form>
            <!-- Form fields for adding or editing -->
            <AppTextField v-for="header in tableHeaders" :key="header.key" v-model="formData[header.key]"
              :label="header.title" />
            <!-- <v-select :items="statusOptions" v-model="formData.status" label="Statut" outlined dense></v-select> -->
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="green darken-1" text @click="saveEntry">Enregistrer</v-btn>
          <v-btn color="red darken-1" text @click="deleteEntry(formData.id)" v-if="dialogAction === 'edit'">Supprimer</v-btn>
          <v-btn color="grey" text @click="dialogVisible = false">Annuler</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
  