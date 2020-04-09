<template>
    <div>
        <div class="mb-2" v-for="paymentMethod in paymentMethods"
             :key="paymentMethod.stripe_id">
            <div class="border border-l-8 flex flex-wrap last:mb-0 mx-0 lg:mx-auto p-4 rounded w-full lg:w-2/3 xl:w-1/2"
                 :class="backgroundColor(paymentMethod)"
                 @click="onClickPaymentMethod(paymentMethod)">
                <div class="flex pr-2 w-1/2 md:w-1/3 xl:w-5/12">
                    <i class="fab mr-3"
                       :class="[ ccLogo(paymentMethod.card.brand), ccColor(paymentMethod.card.brand) ]">
                    </i>
                    <span class="font-mono">**** {{ paymentMethod.card.last4 }}</span>
                </div>
                <div class="font-mono pl-2 text-right w-1/2 md:w-1/4">
                    {{ paymentMethod.card.exp_month.toString().padStart(2, "0") }}
                    / {{ paymentMethod.card.exp_year.toString().substr(2) }}
                </div>
                <div class="text-right w-full md:w-5/12 xl:w-1/3">
                    <button class="focus:outline-none"
                            v-if="!paymentMethod.default"
                            @click="onClickDelete(paymentMethod.stripe_id)">
                        <i class="far fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            backgroundColor(paymentMethod) {
                if(
                    this.selectedPaymentMethod &&
                    this.selectedPaymentMethod == paymentMethod.stripe_id
                ) {
                    return "bg-indigo-400 border-indigo-500 " +
                        "hover:border-indigo-900 hover:shadow text-white";
                }
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
                if(!this.isPaymentMethodExpired(paymentMethod)) {
                    this.$emit("selected", paymentMethod.stripe_id);
                }
            }
        },
        name: "PaymentMethodsTable",
        props: {
            paymentMethods: {
                required: true,
                type: Array
            },
            selectedPaymentMethod: {
                default: "",
                required: false,
                type: String
            }
        }
    }
</script>

<style scoped>

</style>
