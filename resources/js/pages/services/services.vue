<script setup>
import { onMounted, ref } from 'vue';

// Reactive reference to hold a single service's data
const service = ref({
  name: '',
  description: '',
  status: true,
  price: 0,
  required_documents: [],
  required_fields: [], // New field for additional requirements
});

// Headers for the data table
const headers = [
  { title: 'Name', key: 'name' },
  { title: 'Description', key: 'description' },
  { title: 'Status', key: 'status' },
  { title: 'Price', key: 'price' },
  { title: 'Actions', key: 'action' },
];

// Reactive reference to hold an array of services
const services = ref([]);
const servicesData = computed(() => services.value); // Computed property to confirm reactivity

const fetchServices = async () => {
  try {
    const response = await fetch('/api/services');
    const text = await response.text(); // Log the raw text response
    console.log('Raw Response:', text);
    services.value = JSON.parse(text);
  } catch (error) {
    console.error('Error fetching services:', error);
  }
};

// Fetch services when the component is mounted
onMounted(async () => {
  await fetchServices();
});

// Array of documents to be used in the form
const documents = ['Passeport', 'Photo', 'Extrait de naissance', 'Résidence'];

// Array of additional required fields
const additionalFields = [
  'Nom', 'Prénom', 'Email', 'Téléphone', 'Date de naissance',
  'Adresse', 'Ville', 'Code postal', 'Nationalité', 'Numéro de sécurité sociale',
  'Lieu de naissance', 'Nom du père', 'Nom de la mère', 'Genre',
  'Statut marital', 'Numéro de carte d’identité', 'Numéro de passeport',
  'Nom de l’employeur', 'Profession', 'Revenu mensuel'
];
const availableFields = [
  'Nom', 'Prénom', 'Email', 'Téléphone', 'Date de Naissance',
  'Adresse', 'Ville', 'Code Postal', 'Pays', 'Nationalité',
  'Numéro de Passeport', 'Sexe', 'Profession', 'État Civil',
  'Nom de l’Employeur', 'Téléphone d’Urgence', 'Groupe Sanguin',
  'Allergies', 'Médecin de Famille', 'Contact en Cas d’Urgence'
];
// Reactive reference for tracking the service being edited
const selectedService = ref(null);

// Submit new service
const submitService = async () => {
  try {
    const response = await useApi('/services', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(service.value),
      onResponseError({ response }) {
        console.error('Error when adding service:', response._data.errors);
      },
    });

    // Reset the service form after successful submission
    service.value = { name: '', description: '', status: true, price: 0, required_documents: [], required_fields: [] };
    await fetchServices();
  } catch (error) {
    console.error('Error when adding service:', error);
  }
};

// Edit service function to populate the form with service data
const editService = (serviceData) => {
  selectedService.value = { ...serviceData };
};

// Delete service
const deleteService = async (id) => {
  try {
    await useApi(`/services/${id}`, { method: 'DELETE' });
    await fetchServices();
  } catch (error) {
    console.error('Error deleting service:', error);
  }
};
</script>

<template>
  <div>
    <!-- Add Service Button and Form Dialog -->
    <VCard class="mb-6 d-flex justify-space-between align-center" title="Ajouter un service">
      <VDialog max-width="500">
        <template v-slot:activator="{ props: activatorProps }">
          <VBtn v-bind="activatorProps" text="Ajouter Service" variant="flat" class="me-5"></VBtn>
        </template>

        <template v-slot:default="{ isActive }">
          <VForm @submit.prevent="submitService">
            <VCard title="Service">
              <VCardText>
                <VTextField v-model="service.name" class="mb-4" label="Nom du service"></VTextField>
                <VTextarea v-model="service.description" class="mb-4" label="Description du service"></VTextarea>
                <VSwitch v-model="service.status" class="mb-4" label="Status"></VSwitch>
                <VTextField v-model="service.price" type="number" class="mb-4" label="Prix"></VTextField>

                <!-- Required Documents Selection -->
                <v-select v-model="service.required_documents" :items="documents" class="mb-4"
                  label="Documents nécessaires" multiple persistent-hint></v-select>

                <!-- Additional Required Fields Selection -->
                <v-select v-model="service.required_fields" :items="additionalFields" class="mb-4"
                  label="Champs nécessaires" multiple persistent-hint></v-select>
              </VCardText>

              <VCardActions>
                <VSpacer></VSpacer>
                <VBtn type="submit" color="primary">Enregistrer</VBtn>
                <VBtn text="Fermer" @click="isActive.value = false"></VBtn>
              </VCardActions>
            </VCard>
          </VForm>
        </template>
      </VDialog>
    </VCard>

    <!-- Services Data Table -->
    <VCard class="mt-6">
      <VDataTable
        :headers="headers"
        :items="servicesData"
        item-key="id"
        class="elevation-1"
      >
        <template v-slot:item.actions="{ item }">
          <VBtn icon @click="editService(item)">
            <VIcon>mdi-pencil</VIcon>
          </VBtn>
          <VBtn icon @click="deleteService(item.id)">
            <VIcon>mdi-delete</VIcon>
          </VBtn>
        </template>

        <template v-slot:item.status="{ item }">
          <span>{{ item.status ? 'Active' : 'Inactive' }}</span>
        </template>

        <template v-slot:item.price="{ item }">
          <span>{{ item.price }}</span>
        </template>
      </VDataTable>
    </VCard>
  </div>
</template>

