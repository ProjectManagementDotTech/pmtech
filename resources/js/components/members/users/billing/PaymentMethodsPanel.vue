<template>
    <div class="w-full">
        <panel footer-classes="mt-2 text-center">
            <template slot="header">Payment methods</template>
            <template slot="footer">
                <button class="btn btn-primary" @click="$emit('add')">
                    Add new card
                </button>
            </template>
            <p>
                The following credit cards are stored on your account.
            </p>
            <p class="bg-yellow-100 border border-l-8 border-yellow-500 mb-4 px-4 py-2 rounded">
                Please not that credit card information is not stored on our
                systems. All payment information is stored with Stripe.
            </p>
            <payment-methods-table :payment-methods="paymentMethods" />
        </panel>
    </div>
</template>

<script>
    import Panel from "../../../shared/Panel";
    import PaymentMethodsTable from "./PaymentMethodsTable";

    export default {
        components: {
            PaymentMethodsTable,
            Panel
        },
        methods: {
            backgroundColor(paymentMethod) {
                if(this.isPaymentMethodExpired(paymentMethod)) {
                    return "bg-red-200 cursor-not-allowed";
                }
                if(paymentMethod.default) {
                    return "bg-green-200 hover:border-indigo-400 hover:shadow";
                }

                return "hover:border-indigo-400 hover:shadow";
            },
            ccColor(ccBrand) {
                return "text-cc-" + ccBrand + "-500";
                if(ccBrand == "amex") {
                    return "text-cc-amex-500"
                } else if(ccBrand == "mastercard") {
                    return "text-mastercard-blue-500";
                } else if(ccBrand == "visa") {
                    return "text-visa-blue-500";
                } else {
                    return "";
                }
            },
            ccLogo(ccBrand) {
                return "fa-cc-" + ccBrand;
            },
            isPaymentMethodExpired(paymentMethod) {
                let expirationDate = this.$moment();
                expirationDate.month(paymentMethod.card.exp_month - 1);
                expirationDate.year(paymentMethod.card.exp_year);
                expirationDate.endOf("month");
                console.log(expirationDate);
                console.log(this.$moment());
                console.log(this.$moment().isAfter(expirationDate));
                return this.$moment().isAfter(expirationDate);
            },
            onClickDelete(paymentMethodId) {
                this.$axios.post("/api/v1/user/delete-payment-method", {
                    payment_method_id: paymentMethodId
                })
                    .then(() => {
                        this.$emit("deleted")
                    })
                    .catch(() => {
                        /*
                         * Write generic alert thingy (See Dynamix) and report
                         * error deleting card...
                         */
                    });
            },
            onClickPaymentMethod(paymentMethod) {
                this.$emit("selected", paymentMethod.stripe_id);
            }
        },
        name: "PaymentMethodsPanel",
        props: {
            paymentMethods: {
                required: true,
                type: Array
            }
        }
    }
</script>

<style scoped>

</style>
