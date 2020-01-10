<template>
    <div class="modal-backdrop">
        <div class="row">
            <div class="inline-block" />
            <div class="inline-block" @click.stop>
                <article class="dialog-box">
                    <h2 class="dialog-header">
                        <slot name="header">
                            Default header
                        </slot>
                    </h2>
                    <section class="py-3">
                        <slot>
                            Default body
                        </slot>
                    </section>
                    <section class="dialog-footer">
                        <div class="row">
                            <div class="col-2">
                                <button v-if="showHelpButton">
                                    {{ helpButtonText }}
                                </button>
                            </div>
                            <div class="col-4" />
                            <div class="col-3">
                                <button v-if="showCancelButton" :diabled="false"
                                        @click="onClickCancel">
                                    {{ cancelButtonText }}
                                </button>
                            </div>
                            <div class="col-3">
                                <button :disabled="okButtonDisabled()"
                                        @click="onClickOk">
                                    {{ okButtonText }}
                                </button>
                            </div>
                        </div>
                    </section>
                </article>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            onClickCancel() {
                this.$emit("cancel");
            },
            onClickOk() {
                this.$emit("ok");
            }
        },
        name: "ModalDialogBox",
        props: {
            cancelButtonText: {
                default: "Cancel",
                required: false,
                type: String
            },
            helpButtonText: {
                default: "Help",
                required: false,
                type: String
            },
            okButtonDisabled: {
                required: true,
                type: Function
            },
            okButtonText: {
                default: "Ok",
                required: false,
                type: String
            },
            showCancelButton: {
                default: true,
                required: false,
                type: Boolean
            },
            showHelpButton: {
                default: false,
                required: false,
                type: Boolean
            }
        }
    }
</script>

<style scoped>

</style>
