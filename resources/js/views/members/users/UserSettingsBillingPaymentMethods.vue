<template>
    <div class="p-4 w-full">
        <div v-if="cloaked">
            <div class="bg-blue-100 border border-l-8 border-blue-300 pl-4 py-4 rounded-lg shadow-lg w-full">
                Loading... Please wait...
            </div>
        </div>
        <div v-else>
            <div v-if="paymentMethods.length > 0 && !showAddCard">
                <payment-methods-panel :payment-methods="paymentMethods"
                                       @add="onAdd" @deleted="onDeleted" />
            </div>
            <div v-else-if="showAddCard">
                <add-payment-method @added="onAdded" @cancel="onCancel"/>
            </div>
            <div v-else>
                What the ...
            </div>
        </div>
    </div>
</template>

<script>
    import AddPaymentMethod
        from "../../../components/members/users/billing/AddPaymentMethod";
    import PaymentMethodsPanel from
            "../../../components/members/users/billing/PaymentMethodsPanel";

    export default {
        components: {
            AddPaymentMethod,
            PaymentMethodsPanel
        },
        created() {
            this.cloaked = true;
            this.fetchPaymentMethods();
        },
        data() {
            return {
                cloaked: false,
                paymentMethods: [],
                showAddCard: false
            };
        },
        methods: {
            fetchPaymentMethods() {
                this.$axios.get("/api/v1/user/payment-methods")
                    .then(response => {
                        this.paymentMethods = response.data;
                        if(response.data.length > 0) {
                            this.showAddCard = false;
                        } else {
                            this.showAddCard = true;
                        }
                    })
                    .catch(() => {
                        this.paymentMethods = [];
                        this.showAddCard = true;
                    })
                    .finally(() => {
                        this.cloaked = false;
                    });
            },
            onAdd() {
                this.showAddCard = true;
            },
            onAdded() {
                this.showAddCard = false;
                this.cloaked = true;
                this.fetchPaymentMethods();
            },
            onCancel() {
                if(this.paymentMethods.length > 0) {
                    this.showAddCard = false;
                }
            },
            onDeleted() {
                this.cloaked = true;
                this.fetchPaymentMethods();
            }
        },
        name: "UserSettingsBillingPaymentMethods"
    }
</script>

<style scoped>

</style>
