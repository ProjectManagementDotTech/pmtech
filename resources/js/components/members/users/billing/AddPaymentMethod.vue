<template>
    <div v-if="cloaked">
        <div class="bg-blue-100 border border-l-8 border-blue-300 pl-4 py-4 rounded-lg shadow-lg w-full">
            Loading... Please wait...
        </div>
    </div>
    <div v-else-if="error">
        <div class="bg-red-100 border border-l-8 border-red-300 pl-4 py-4 rounded-lg shadow-lg w-full">
            <p>
                There was an error initialising this component.
            </p>
            <p>
                We are very sorry for the inconvenience. Please try again later.
            </p>
        </div>
    </div>
    <validation-observer class="needs-validation" novalidate
                         ref="observer" tag="div"
                         v-else
                         v-slot="{ invalid }">
        <panel footer-classes="flex flex-wrap">
            <template slot="header">
                Add payment method
            </template>
            <template slot="footer">
                <div class="sm:hidden mb-3 text-right w-full">
                    <button class="btn btn-secondary" @click="onClickCancel">
                        Cancel
                    </button>
                </div>
                <div class="sm:hidden text-right w-full">
                    <button class="btn btn-primary"
                            :disabled="invalid || loadingStripe"
                            @click="onClickAdd">
                        Add payment method
                    </button>
                </div>
                <div class="hidden sm:inline-block text-right w-full">
                    <button class="btn btn-secondary mr-3" @click="onClickCancel">
                        Cancel
                    </button>
                    <button class="btn btn-primary"
                            :disabled="invalid || loadingStripe"
                            @click="onClickAdd">
                        Add payment method
                    </button>
                </div>
            </template>
            <div class="flex mx-0 lg:mx-auto w-full lg:w-1/2">
                <pmtech-input label="Cardholder name" name="cardholderName"
                              rules="required" v-model="cardholderName"
                              :disabled="loadingStripe" />
            </div>
            <div class="flex flex-wrap mb-2 mx-0 lg:mx-auto w-full lg:w-1/2">
                <div class="p-4 w-full" id="card-element"></div>
                <div class="bg-red-200 border border-l-8 border-red-500 px-4 py-2 rounded w-full"
                     v-if="cardError.length > 0">
                    {{ cardError }}
                </div>
            </div>
            <div class="flex mx-0 lg:mx-auto w-full lg:w-1/2">
                <input class="ml-4 mr-2" type="checkbox" v-model="setAsDefault"
                       :disabled="loadingStripe">
                Set as default
            </div>
        </panel>
    </validation-observer>
</template>

<script>
    import Panel from "../../../shared/Panel";
    import PmtechInput from "../../../shared/input/PmtechInput";

    export default {
        components: {
            Panel,
            PmtechInput
        },
        created() {
            this.$axios.get("/api/v1/user/setup-intent")
                .then(response => {
                    this.intent = response.data;
                    this.cloaked = false;
                    this.setupStripe();
                })
                .catch(error => {
                    /*
                     * TODO Write general error alert component (see Dynamix)
                     */
                    console.log("AddPaymentMethod - Error fetching " +
                        "setup-intent");
                    console.dir(error);
                    this.error = true;
                });
/*
                .finally(() => {
                    this.cloaked = false;
                })
*/
        },
        data() {
            return {
                cardElement: undefined,
                cardError: "",
                cardholderName: "",
                cloaked: true,
                error: false,
                loadingStripe: false,
                intent: {},
                setAsDefault: false,
                stripe: undefined
            };
        },
        methods: {
            onClickAdd() {
                this.cardError = "";
                this.stripe.confirmCardSetup(
                    this.intent.client_secret, {
                        payment_method: {
                            card: this.cardElement,
                            billing_details: {
                                name: this.cardholderName
                            }
                        }
                    })
                    .then(response => {
                        if(response.error !== undefined) {
                            this.cardError = response.error.message;
                            return;
                        }
                        this.$axios.post("/api/v1/user/add-payment-method", {
                            payment_method: response.setupIntent.payment_method,
                            set_as_default: this.setAsDefault
                        })
                            .then(response => {
                                this.$emit('added');
                            })
                            .catch(error => {
                                debugger;
                            });
                    })
                    .catch(error => {
                        debugger;
                    });
            },
            onClickCancel() {
                this.$emit("cancel");
            },
            onStripeSetup() {
                this.stripe = Stripe(process.env.MIX_STRIPE_PUBLIC_KEY);
                const elements = this.stripe.elements();
                this.cardElement = elements.create("card");
                this.cardElement.mount("#card-element");
                this.loadingStripe = false;
            },
            setupStripe() {
                this.loadingStripe = true;
                let existingScript = document.getElementsByTagName("script")[0];
                let newScript = document.createElement("script");
                newScript.src = "https://js.stripe.com/v3/";
                newScript.addEventListener("load", this.onStripeSetup, false);
                existingScript.parentNode.insertBefore(newScript,
                    existingScript);
            }
        },
        name: "AddPaymentMethod"
    }
</script>

<style scoped>

</style>
