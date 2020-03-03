<template>
    <div class="p-4 w-full">
        <template v-if="cloaked">
            <div class="bg-blue-100 border border-l-8 border-blue-300 pl-4 py-4 rounded-lg shadow-lg w-full">
                Loading... Please wait...
            </div>
        </template>
        <template v-else>
            <panel :has-footer="false">
                <template slot="header">Invoices</template>
                <div class="border-l-8 border-white flex px-4 w-full">
                    <div class="w-1/3">Date</div>
                    <div class="w-1/3">Amount</div>
                </div>
                <div class="border border-l-8 hover:border-indigo-400 flex mb-2 last:mb-3 px-4 py-2 rounded hover:shadow-md w-full"
                     v-for="invoice in invoices" :key="$utils.uuid()">
                    <div class="w-1/3">
                        {{ invoice.date }}
                    </div>
                    <div class="w-1/3">
                        {{ invoice.total }}
                    </div>
                    <div class="w-1/3">
                        <a :href="invoice.download">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
            </panel>
        </template>
    </div>
</template>

<script>
    import Panel from "../../../components/shared/Panel";

    export default {
        components: {
            Panel
        },
        created() {
            this.$axios.get("/api/v1/user/invoices")
                .then(response => {
                    this.invoices = response.data;
                })
                .catch(error => {
                    this.invoices = error;
                })
                .finally(() => {
                    this.cloaked = false;
                });
        },
        data() {
            return {
                cloaked: true,
                invoices: []
            };
        },
        name: "UserSettingsBillingInvoices"
    }
</script>

<style scoped>

</style>
