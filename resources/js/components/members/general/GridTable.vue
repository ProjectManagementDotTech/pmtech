<template>
    <div class="w-full overflow-hidden text-sm leading-none">
        <div v-if="displayRowHeaders" class="w-full flex">
            <all-cells-header v-if="displayColumnHeaders" />
            <column-header v-for="(field, index) in fields" :key="index">
                {{ fieldTitle(field, index) }}
            </column-header>
        </div>
        <div v-for="(dataRow, rowIndex) in data" class="w-full flex">
            <row-header v-if="displayRowHeaders" :data="dataRow"
                        @select="onSelectRow">
                {{ rowIndex + 1 }}
            </row-header>
            <component class="border-b border-gray-600 border-l last:border-r inline-block w-64"
                       v-bind:is="gridField[field.type != undefined ? field.type : 'text'] ? gridField[field.type != undefined ? field.type : 'text'] : gridTextEditor"
                       v-for="(field, fieldIndex) in fields"
                       :value="dataRow[field.attribute]"
                       :key="fieldIndex" :selected="isSelected(dataRow)"
                       @click="onClickRow(dataRow)"
                       @input="onInputField(field, dataRow, $event)" />
        </div>
        <div class="w-full flex" v-if="displayEmptyRow">
            <row-header v-if="displayRowHeaders" @select="onSelectRow">
                {{ data.length + 1 }}
            </row-header>
            <component class="border-b border-gray-600 border-l last:border-r inline-block w-64"
                       v-bind:is="gridField[field.type != undefined ? field.type : 'text'] ? gridField[field.type != undefined ? field.type : 'text'] : gridTextEditor"
                       v-for="(field, fieldIndex) in fields"
                       :value="editorEmptyObject[field.attribute]"
                       :key="fieldIndex" ref="newInputRow"
                       @click="onClickRow(undefined)"
                       @input="onInputNewRowField(field, editorEmptyObject, $event)" />
        </div>
    </div>
</template>

<script>
    import GridTextEditor from "./gridTable/GridTextEditor";
    import AllCellsHeader from "./gridTable/AllCellsHeader";
    import ColumnHeader from "./gridTable/ColumnHeader";
    import RowHeader from "./gridTable/RowHeader";

    export default {
        components: {
            RowHeader,
            ColumnHeader,
            AllCellsHeader,
            GridTextEditor
        },
        created() {
            this.goldenCopy = JSON.parse(JSON.stringify(this.emptyObject));
            this.editorEmptyObject = JSON.parse(JSON.stringify(
                this.emptyObject));
        },
        data() {
            return {
                editorEmptyObject: {},
                goldenCopy: {},
                gridField: {
                    'text': 'GridTextEditor'
                },
                selectedRows: []
            };
        },
        methods: {
            fieldTitle(aField, anIndex) {
                anIndex++;
                if(aField.title == undefined) {
                    if(anIndex <= 26) {
                        return String.fromCharCode(64 + anIndex);
                    } else if(anIndex <= 676) {
                        let firstLetterIndex = Math.floor(anIndex / 26);
                        let secondLetterIndex = anIndex % 26;
                        return String.fromCharCode(65 + firstLetterIndex) +
                            String.fromCharCode(64 + secondLetterIndex);
                    } else {
                        console.error("GridTable does not support more than " +
                            "676 Columns!");
                    }
                } else {
                    return aField.title;
                }
            },
            isSelected(aDataRow) {
                if(aDataRow == undefined) {
                    return false;
                }

                let found = this.selectedRows.find(
                    row => row.id == aDataRow.id);
                return found != undefined;
            },
            onClickRow(dataRow) {
                if(!this.isSelected(dataRow)) {
                    this.selectedRows = [];
                }
            },
            onInputField(field, dataRow, newValue) {
                let newObject = JSON.parse(JSON.stringify(dataRow));
                newObject[field.attribute] = newValue;
                this.$emit("input", newObject);
            },
            onInputNewRowField(field, dataRow, newValue) {
                this.onInputField(field, dataRow, newValue);
                this.$refs.newInputRow.forEach(r => r.resetInputControl());
            },
            onSelectRow(payload) {
                let data = payload.data;
                let event = payload.event;
                if(!event.ctrlKey && !event.shiftKey) {
                    if(this.isSelected(data)) {
                        this.selectedRows = [];
                    } else {
                        this.selectedRows = [data];
                    }
                } else if(event.ctrlKey) {
                    if(this.isSelected(data)) {
                        let index = this.selectedRows
                            .findIndex(row => row.id == data.id);
                        if(index >= 0) {
                            this.selectedRows.splice(index, 1);
                        }
                    } else {
                        this.selectedRows.push(data);
                    }
                } else if(event.shiftKey) {
                    let selectedIndex = this.data
                        .findIndex(row => row.id == this.selectedRows[0].id);
                    let clickedIndex = this.data
                        .findIndex(row => row.id == data.id);
                    if(selectedIndex >= 0 && clickedIndex >= 0) {
                        let startIndex = 0;
                        let endIndex = 0;
                        if(selectedIndex < clickedIndex) {
                            startIndex = selectedIndex;
                            endIndex = clickedIndex;
                        } else {
                            startIndex = clickedIndex;
                            endIndex = selectedIndex;
                        }
                        for(let i = startIndex; i <= endIndex; i++) {
                            let toBeSelectedData = this.data[i];
                            if(!this.isSelected(toBeSelectedData)) {
                                this.selectedRows.push(toBeSelectedData);
                            }
                        }
                    }
                }
            }
        },
        name: "GridTable",
        props: {
            data: {
                required: true,
                type: Array,
            },
            displayColumnHeaders: {
                default: true,
                required: false,
                type: Boolean
            },
            displayEmptyRow: {
                default: true,
                required: false,
                type: Boolean
            },
            displayRowHeaders: {
                default: true,
                required: false,
                type: Boolean
            },
            emptyObject: {
                required: true,
                type: Object
            },
            fields: {
                required: false,
                type: Array,
                validator: function(value) {
                    return value.length > 0;
                }
            },
        },
        watch: {
            selectedRows: {
                deep: true,
                handler(newVal, oldVal) {
                    this.$emit("selected-rows", newVal);
                }
            }
        }
    }
</script>

<style scoped>

</style>
