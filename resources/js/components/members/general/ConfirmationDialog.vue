<template>
    <modal-dialog-box :cancel-button-text="cancelButtonText"
                      :ok-button-disabled="() => { return false; }"
                      :ok-button-text="okButtonText"
                      :show-cancel-button="true" @cancel="$emit('cancel')"
                      @ok="$emit('ok')">
        <template slot="header">{{ title }}</template>
        <div class="px-2">{{ message }}</div>
    </modal-dialog-box>
</template>

<script>
    import ModalDialogBox from "../../shared/ModalDialogBox";
    export default {
        components: {ModalDialogBox},
        computed: {
            cancelButtonText() {
                return this.buttons.split(/\|/)[1];
            },
            okButtonText() {
                return this.buttons.split(/\|/)[0];
            }
        },
        name: "ConfirmationDialog",
        props: {
            buttons: {
                default: "OK|Cancel",
                required: false,
                type: String
            },
            message: {
                required: true,
                type: String
            },
            title: {
                required: true,
                type: String
            }
        }
    }
</script>

<style scoped>

</style>
