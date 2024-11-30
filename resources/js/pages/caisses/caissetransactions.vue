<template>
  <v-container>
    <v-alert v-if="error" type="error" dismissible>
      {{ error }}
    </v-alert>
    <v-alert v-if="success" type="success" dismissible>
      {{ success }}
    </v-alert>

    <!-- Add Caisse Form -->
    <v-card class="mb-5">
      <v-card-title>Add New Caisse</v-card-title>
      <v-card-text>
        <v-form ref="caisseForm">
          <v-text-field
            v-model="caisseForm.name"
            label="Caisse Name"
            required
          ></v-text-field>
          <v-text-field
            v-model="caisseForm.initial_balance"
            label="Initial Balance"
            type="number"
            required
          ></v-text-field>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-btn @click="submitCaisse" color="primary" :loading="loading">
          Add Caisse
        </v-btn>
      </v-card-actions>
    </v-card>

    <!-- Transactions Table -->
    <v-card class="mb-5">
      <v-card-title>Manage Transactions</v-card-title>
      <v-data-table
        :headers="headers"
        :items="caisses"
        item-value="id"
        dense
        class="elevation-1"
      >
        <template #item.actions="{ item }">
          <v-btn @click="openTransactionForm(item)" small color="primary">
            Add Transaction
          </v-btn>
        </template>
      </v-data-table>
    </v-card>

    <!-- Add Transaction Dialog -->
    <v-dialog v-model="dialog" max-width="500px">
      <v-card>
        <v-card-title>Create Transaction</v-card-title>
        <v-card-text>
          <v-form ref="transactionForm">
            <v-text-field
              v-model="transactionForm.amount"
              label="Amount"
              type="number"
              required
            ></v-text-field>
            <v-radio-group v-model="transactionForm.type" row>
              <v-radio label="Entry" value="entry"></v-radio>
              <v-radio label="Exit" value="exit"></v-radio>
            </v-radio-group>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="dialog = false" text>Cancel</v-btn>
          <v-btn
            @click="submitTransaction"
            color="primary"
            :loading="loadingTransaction"
          >
            Submit
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import axios from "axios"; // Adjust this import to match your Axios instance

export default {
  data() {
    return {
      caisses: [],
      headers: [
        { text: "ID", value: "id" },
        { text: "Name", value: "name" },
        { text: "Balance", value: "balance" },
        { text: "Total Entry", value: "total_entry" },
        { text: "Total Exit", value: "total_exit" },
        { text: "Actions", value: "actions", sortable: false },
      ],
      caisseForm: {
        name: "",
        initial_balance: "",
      },
      transactionForm: {
        caisse_id: null,
        amount: null,
        type: "entry",
      },
      dialog: false,
      loading: false,
      loadingTransaction: false,
      success: null,
      error: null,
    };
  },
  async created() {
    await this.fetchCaisses();
  },
  methods: {
    // Fetch caisses
    async fetchCaisses() {
      try {
        const response = await axios.get("/api/caisses");
        this.caisses = response.data.data;
      } catch (err) {
        this.error = err.response?.data?.message || "Failed to fetch caisses.";
      }
    },
    // Submit new caisse
    async submitCaisse() {
      this.loading = true;
      try {
        const response = await axios.post("/api/caisses", this.caisseForm);
        this.success = response.data.message;
        this.error = null;
        this.caisseForm = { name: "", initial_balance: "" };
        this.fetchCaisses();
      } catch (err) {
        this.error =
          err.response?.data?.message || "Failed to create new caisse.";
        this.success = null;
      } finally {
        this.loading = false;
      }
    },
    // Open transaction form
    openTransactionForm(caisse) {
      this.transactionForm.caisse_id = caisse.id;
      this.dialog = true;
    },
    // Submit transaction
    async submitTransaction() {
      this.loadingTransaction = true;
      try {
        const response = await axios.post(
          "/api/caisses/transaction",
          this.transactionForm
        );
        this.success = response.data.message;
        this.error = null;
        this.transactionForm = { caisse_id: null, amount: null, type: "entry" };
        this.dialog = false;
        this.fetchCaisses();
      } catch (err) {
        this.error =
          err.response?.data?.message || "Failed to create transaction.";
        this.success = null;
      } finally {
        this.loadingTransaction = false;
      }
    },
  },
};
</script>

