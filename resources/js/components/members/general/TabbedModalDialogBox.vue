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
                    <section class="px-2 py-1">
                        <div class="flex w-full">
                            <div v-for="tab in tabs"
                                 class="border-gray-400 border-l last:border-r border-t p-2 text-center"
                                 :class="tabulatorStyle(tab)"
                                 :style="'width: ' + (100 / tabs.length) + '%;'"
                                 @click="activateTab(tab)">
                                {{ tab.title }}
                            </div>
                        </div>
                        <div class="border-b border-gray-400 border-l border-r p-4 w-full">
                            <keep-alive>
                                <component v-bind:is="activeTab.component"
                                           :data="tabComponentData" />
                            </keep-alive>
                        </div>
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
        data() {
            return {
                activeTab: undefined
            };
        },
        methods: {
            activateDefaultTab() {
                this.activeTab = this.tabs.find(t => t.default == true);
                if(this.activeTab == null || this.activeTab == undefined) {
                    console.error("TabbedModalDialogBox - please mark on of " +
                        "the tabs as 'default: true'!");
                }
            },
            activateTab(aTab) {
                this.activeTab = aTab;
            },
            isTabActive(aTab) {
                if(this.activeTab == undefined) {
                    this.activateDefaultTab();
                }

                return this.activeTab.title == aTab.title;
            },
            onClickCancel() {
                this.$emit("cancel");
            },
            onClickOk() {
                this.$emit("ok");
            },
            tabulatorStyle(aTab) {
                if(this.isTabActive(aTab)) {
                    return "cursor-default";
                } else {
                    return "border-b bg-gray-200 hover:bg-gray-300 cursor-pointer";
                }
            }
        },
        name: "TabbedModalDialogBox",
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
            },
            tabs: {
                default: [],
                required: true,
                type: Array,
                validator: function(value) {
                    return value.length > 0;
                }
            },
            tabComponentData: {
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
