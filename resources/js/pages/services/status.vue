<script>
import axios from "axios";

export default {
  data() {
    return {
      status: {
        name: "",
        color: "#000000",
      },
      statuses: [],
    };
  },
  methods: {
    async fetchStatuses() {
      try {
        const response = await axios.get("/api/statuses");
        this.statuses = response.data;
      } catch (error) {
        console.error("Erreur lors du chargement des statuts :", error);
      }
    },
    async saveStatus() {
      try {
        const response = await axios.post("/api/statuses", this.status);
        this.statuses.push(response.data);
        this.status = { name: "", color: "#000000" }; // Réinitialiser le formulaire
      } catch (error) {
        console.error("Erreur lors de l'enregistrement :", error);
      }
    },
    async deleteStatus(id) {
      try {
        await axios.delete(`/api/statuses/${id}`);
        this.statuses = this.statuses.filter(status => status.id !== id);
      } catch (error) {
        console.error("Erreur lors de la suppression :", error);
      }
    },
  },
  mounted() {
    this.fetchStatuses();
  },
};
</script>
<template>
  <v-container>
    <v-card>
      <v-card-title>Gestion des Statuts</v-card-title>
      <v-card-text>
        <v-form ref="statusForm">
          <v-text-field v-model="status.name" label="Nom du statut" outlined></v-text-field>
          <v-color-picker v-model="status.color"  :modes="['rgba']"></v-color-picker>
          <v-btn color="primary" @click="saveStatus">Enregistrer</v-btn>
        </v-form>
        <v-divider class="my-4"></v-divider>
        <v-list>
          <v-list-item v-for="status in statuses" :key="status.id"
            :style="{ backgroundColor: status.color, color: '#fff' }" class="my-2">
            <v-list-item-content>
              <v-list-item-title>{{ status.name }}</v-list-item-title>
            </v-list-item-content>
            <v-btn color="error" icon @click="deleteStatus(status.id)">
              <v-icon >tabler-trash</v-icon>
            </v-btn>
          </v-list-item>
        </v-list>
      </v-card-text>
    </v-card>
  </v-container>
</template>
