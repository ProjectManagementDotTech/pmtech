<template>
    <div class="p-4 w-full">
        <div v-if="cloaked">
            <div class="bg-blue-100 border border-l-8 border-blue-300 pl-4 py-4 rounded-lg shadow-lg w-full">
                Loading... Please wait...
            </div>
        </div>
        <div v-else-if="successMessage.length > 0">
            <div class="bg-green-100 border border-l-8 border-green-300 pl-4 py-4 rounded-lg shadow-lg w-full">
                {{ successMessage }}
            </div>
        </div>
        <div v-else-if="errorMessage.length > 0">
            <div class="bg-red-100 border border-l-8 border-red-300 pl-4 py-4 rounded-lg shadow-lg w-full">
                {{ errorMessage }}
            </div>
        </div>
        <panel v-else footer-classes="flex justify-end w-full">
            <template slot="header">Payment</template>
            <template slot="footer">
                <button class="btn btn-secondary mr-2" @click="onClickCancel">
                    Cancel
                </button>
                <button class="btn btn-primary" @click="onClickPay">Pay</button>
            </template>
            <div class="bg-red-100 border border-l-8 border-red-300 pl-4 py-4 rounded-lg shadow-lg w-full"
                 v-if="paymentErrorMessage.length > 0">
                {{ paymentErrorMessage }}
            </div>
            <div class="flex w-full">
                <p v-if="initialCharge">
                    Your subscription starts with a fee of &euro;{{ balance }}
                </p>
                <p v-else>
                    The current balance on your subscription is &euro;{{ balance }}
                </p>
            </div>
            <div v-if="!initialCharge">
                Current outstanding charge<br />
                How much do you want to pay<br />
            </div>
            <div class="flex w-full items-center">
                <div class="w-1/4">
                    Please select the card you want to use
                </div>
                <div class="w-2/3">
                    <payment-methods-table :payment-methods="paymentMethods"
                                           :selected-payment-method="selectedPaymentMethod"
                                           @selected="onUpdateSelectedPaymentMethod" />
                </div>
            </div>
        </panel>
    </div>
</template>

<script>
    import Panel from "../../../components/shared/Panel";
    import PaymentMethodsTable
        from "../../../components/members/users/billing/PaymentMethodsTable";

    export default {
        components: {
            PaymentMethodsTable,
            Panel
        },
        created() {
            let balancePromise = this.fetchBalance();
            let paymentMethodsPromise = this.fetchPaymentMethods();
            Promise.all([
                balancePromise, paymentMethodsPromise
            ])
                .then(() => {
                    this.cloaked = false;
                })
        },
        data() {
            return {
                balance: 0,
                cloaked: true,
                errorMessage: "",
                initialCharge: false,
                paymentErrorMessage: "",
                paymentMethods: [],
                selectedPaymentMethod: "",
                successMessage: ""
            };
        },
        methods: {
            fetchBalance() {
                return new Promise((resolve, reject) => {
                    this.$axios.get("/api/v1/workspaces/" +
                        this.$route.params.workspaceId + "/balance")
                        .then(response => {
                            if(response.data.initial_charge !== undefined) {
                                this.balance = response.data.initial_charge;
                                this.initialCharge = true;
                            } else {
                                this.balance = response.data.balance;
                                this.initialCharge = true;
                            }
                        })
                        .catch(error => {
                            this.errorMessage = error.response.data.message;
                        })
                        .finally(() => {
                            resolve();
                        });
                });
            },
            fetchPaymentMethods() {
                return new Promise((resolve, reject) => {
                    this.$axios.get("/api/v1/user/payment-methods")
                        .then(response => {
                            this.paymentMethods = response.data;
                        })
                        .catch(() => {
                            this.paymentMethods = [];
                        })
                        .finally(() => {
                            resolve();
                        });
                });
            },
            onClickCancel() {
                this.$router.push("/workspaces/" +
                    this.$route.params.workspaceId);
            },
            onClickPay() {
                if(
                    !this.selectedPaymentMethod ||
                    this.selectedPaymentMethod.length == 0
                ) {
                    return;
                }

                this.cloaked = true;
                this.$axios.post("/api/v1/user/create-subscription", {
                    workspace_id: this.$route.params.workspaceId,
                    stripe_id: this.selectedPaymentMethod
                })
                    .then(() => {
                        this.successMessage = "Thank you for subscribing to " +
                            "Project-Management.tech! The selected card will " +
                            "be charged on the " + this.$moment().format("Do") +
                            " of each month for this subscription.";
                    })
                    .catch(error => {
                        if(error.response.status == 403) {
                            paymentErrorMessage = "You are not the owner of " +
                                "this workspace, and therefore, you cannot " +
                                "pay for its subscription.";
                        } else if(error.response.status == 404) {
                            paymentErrorMessage = "Cannot find the workspace " +
                                "or the selected payment method. Please " +
                                "refresh your browser and try again. If the " +
                                "problem persists, please make sure the " +
                                "payment method is actually valid.";
                        } else if(error.response.status == 422) {
                            paymentErrorMessage =
                                error.response.data.errors["workspace_id"][0];
                        }
                    })
                    .finally(() => {
                        this.cloaked = false;
                    })
            },
            onUpdateSelectedPaymentMethod(paymentMethod) {
                this.selectedPaymentMethod = paymentMethod;
            }
        },
        name: "UserSettingsBillingPayment"

    }
</script>

<style scoped>

</style>
