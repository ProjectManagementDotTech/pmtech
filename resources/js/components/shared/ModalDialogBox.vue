<template>
    <div class="modal-backdrop" @click.self="onClickCancel">
        <div class="flex flex-row w-full">
            <div class="w-1/2 mx-auto">
                <article class="dialog-box" @keypress.esc="onClickCancel">
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
                        <div class="flex flex-wrap items-center justify-between -mb-4 px-2">
                            <div class="w-1/2 sm:w-1/3 mb-4 text-left">
                                <button class="btn btn-secondary"
                                        v-if="showHelpButton">
                                    {{ helpButtonText }}
                                </button>
                            </div>
                            <div class="w-1/2 sm:w-1/3 mb-4 text-right lg:hidden">
                                <button class="btn btn-secondary " v-if="showCancelButton"
                                        :diabled="false" @click="onClickCancel">
                                    {{ cancelButtonText }}
                                </button>
                            </div>
                            <div class="hidden sm:w-1/2 sm:inline-block md:hidden">&nbsp;</div>
                            <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/2 mb-4 text-right">
                                <button class="hidden lg:inline-block lg:mr-2 btn btn-secondary" v-if="showCancelButton"
                                        :diabled="false" @click="onClickCancel">
                                    {{ cancelButtonText }}
                                </button>
                                <button class="btn btn-primary"
                                        :disabled="okButtonDisabled()"
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
