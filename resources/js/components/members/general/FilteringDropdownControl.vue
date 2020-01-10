<template>
    <div class="relative">
        <div class="bg-gray-100 border border-gray-200 flex items-center p-1 rounded"
             @click.stop="toggleDropdownVisibility">
            <div class="inline-block truncate"
                 :class="{ 'w-10/12': entries.length > 0, 'xl:w-11/12': entries.length > 0, 'w-full': entries.length == 0 }">
                {{ selectedEntryTitle }}
            </div>
            <div v-if="entries.length > 0"
                 class="float-right inline-block w-2/12 xl:w-1/12">
                <button class="float-right focus:outline-none">
                    <i v-if="!dropdownVisible" class="fas fa-chevron-down"></i>
                    <i v-else class="fas fa-chevron-up"></i>
                </button>
            </div>
        </div>
        <transition name="fade">
            <div v-show="dropdownVisible"
                 class="absolute block border border-gray-200 right-0 shadow-lg w-full z-10">
                <div class="bg-white border-b border-gray-200 pt-1">
                    <div class="inline-block px-1 float-left">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="border-l border-gray-200 inline-block w-10/12">
                        <input class="block pl-1 w-full"
                               placeholder="Search..." type="text" v-model="filter"
                               @input="onInput" @click.stop
                               @keydown.down="onKeyDown" @keydown.enter="onKeyEnter"
                               @keydown.up="onKeyUp">
                    </div>
                </div>
                <ul :class="{ 'h-24': filteredEntries.length > 3, 'overflow-y-scroll': filteredEntries.length > 3 }">
                    <li class="bg-white even:bg-gray-100 hover:bg-gold-100 block p-1 w-full"
                        v-for="entry in filteredEntries"
                        :id="entry[entryIdAttribute]"
                        :key="entry[entryIdAttribute]"
                        :value="entry[entryIdAttribute]"
                        @click="onUpdateSelection(entry)">
                        {{ entry[entryTitleAttribute] }}
                    </li>
                </ul>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        beforeDestroy() {
            this.$eventBus.$off('blur', this.hideDropdown);
        },
        computed: {
            filteredEntries() {
                if(this.filter == "") {
                    return this.entries;
                } else {
                    return this.entries.filter(e => {
                        let title = e[this.entryTitleAttribute];
                        return title.startsWith(this.filter);
                    })
                }
            },
            selectedEntryTitle() {
                if(this.value !== null && this.value !== undefined) {
                    return this.value[this.entryTitleAttribute];
                } else {
                    return this.noSelectionText;
                }
            }
        },
        created() {
            this.$eventBus.$on('blur', this.hideDropdown);
        },
        data() {
            return {
                dropdownButtonElement: undefined,
                dropdownVisible: false,
                filter: "",
                highlightedElementId: "",
                highlightedElementIdx: -1,
                selection: null
            }
        },
        methods: {
            hideDropdown() {
                this.dropdownVisible = false;
                this.highlightedElementId = -1;
                this.highlightedElementIdx = -1;
                if(this.dropdownButtonElement) {
                    this.dropdownButtonElement
                        .classList
                        .remove("border-indigo-400");
                    this.dropdownButtonElement.classList.add("border-gray-200");
                }
                // let selectionString = JSON.stringify(this.selection);
                // let valueString = JSON.stringify(this.value);
                // console.log("selectionString == valueString : '" +
                //     selectionString + "' == '" + valueString + "' : " +
                //     (selectionString == valueString).toString());
                // if(
                //     selectionString !== "null" &&
                //     selectionString !== valueString
                // ) {
                //     console.log("  emitting 'input', then 'blur' in the next tick...");
                // }
                // this.$emit("input", this.value);
                this.$nextTick(() => {
                    this.$emit("blur");
                });
            },
            onInput() {
                this.entries.forEach(entry => {
                    let eId = entry[this.entryIdAttribute];
                    let el = document.getElementById(eId);
                    if(el) {
                        el.classList.remove("dropdown-item-active");
                    }
                });
                this.highlightedElementIdx = 0;
                this.highlightedElementId =
                    this.filteredEntries[this.highlightedElementIdx]
                        [this.entryIdAttribute];
                this.$nextTick(() => {
                    var el = document.getElementById(this.highlightedElementId);
                    el.classList.add("dropdown-item-active");
                    el.scrollIntoView(false);
                });
            },
            onKeyDown() {
                let startIdx = this.highlightedElementIdx;
                if(this.highlightedElementIdx == -1) {
                    this.highlightedElementIdx = 0;
                }
                if(startIdx !== -1) {
                    this.highlightedElementId =
                        this.filteredEntries[this.highlightedElementIdx]
                            [this.entryIdAttribute];
                    var el = document.getElementById(this.highlightedElementId);
                    el.classList.remove("dropdown-item-active");
                    this.highlightedElementIdx++;
                }
                if(this.highlightedElementIdx >= this.filteredEntries.length) {
                    this.highlightedElementIdx = 0;
                }
                this.highlightedElementId =
                    this.filteredEntries[this.highlightedElementIdx]
                        [this.entryIdAttribute];
                var el = document.getElementById(this.highlightedElementId);
                el.classList.add("dropdown-item-active");
                el.scrollIntoView(false);
            },
            onKeyEnter() {
                var el = document.getElementById(this.highlightedElementId);
                if(el) {
                    el.click();
                }
            },
            onKeyUp() {
                this.highlightedElementId =
                    this.filteredEntries[this.highlightedElementIdx]
                        [this.entryIdAttribute];
                var el = document.getElementById(this.highlightedElementId);
                el.classList.remove("dropdown-item-active");
                this.highlightedElementIdx--;
                if(this.highlightedElementIdx < 0) {
                    this.highlightedElementIdx =
                        this.filteredEntries.length - 1;
                }
                this.highlightedElementId =
                    this.filteredEntries[this.highlightedElementIdx]
                        [this.entryIdAttribute];
                var el = document.getElementById(this.highlightedElementId);
                el.classList.add("dropdown-item-active");
                el.scrollIntoView(false);
            },
            onUpdateSelection(selectedEntry) {
                console.log("FilteringDropdownControl::onUpdateSelection");
                console.log("  Emitting 'input' with data: '" + JSON.stringify(selectedEntry) + "'");
                this.$emit("input", selectedEntry);
                this.dropdownVisible = false;
                this.filter = "";
            },
            toggleDropdownVisibility(e) {
                if(this.dropdownButtonElement === undefined) {
                    if(e.target.tagName == "I") {
                        this.dropdownButtonElement =
                            e.target.parentElement.parentElement.parentElement;
                    } else if(e.target.tagName == "BUTTON") {
                        this.dropdownButtonElement =
                            e.target.parentElement.parentElement;
                    } else if(e.target.tagName == "DIV") {
                        this.dropdownButtonElement =
                            e.target.parentElement;
                    }
                }
                let startedVisible = this.dropdownVisible;
                this.$eventBus.$emit("blur");
                if(this.entries.length > 0) {
                    this.$nextTick(() => {
                        this.dropdownVisible = !startedVisible;
                        if(this.dropdownVisible) {
                            this.dropdownButtonElement
                                .classList
                                .remove("border-gray-200");
                            this.dropdownButtonElement
                                .classList
                                .add("border-indigo-400");
                            this.onKeyDown();
                        } else {
                            this.dropdownButtonElement
                                .classList
                                .remove("border-indigo-400");
                            this.dropdownButtonElement
                                .classList
                                .add("border-gray-200");
                            this.highlightedElementId = "";
                            this.highlightedElementIdx = -1;
                            this.filteredEntries.forEach(entry => {
                                let eId = entry[this.entryIdAttribute];
                                let el = document.getElementById(eId);
                                el.classList.remove("dropdown-item-active");
                            });
                        }
                    });
                }
            }
        },
        name: "FilteringDropdownControl",
        props: {
            entries: {
                required: true,
                type: Array
            },
            entryIdAttribute: {
                default: "id",
                required: false,
                type: String
            },
            entryTitleAttribute: {
                default: "name",
                required: false,
                type: String
            },
            noSelectionText: {
                default: "Please select",
                required: false,
                type: String
            },
            value: {
                required: true
            }
        },
        // watch: {
        //     value: {
        //         deep: true,
        //         handler(newVal, oldValue) {
        //             if(newVal !== undefined) {
        //                 this.selection = newVal;
        //             }
        //         }
        //     }
        // }
    }
</script>

<style scoped>
    .dropdown-item-active {
        @apply bg-gold-100;
    }
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s ease;
    }
    .fade-enter, .fade-leave-to {
        opacity: 0;
    }
</style>
