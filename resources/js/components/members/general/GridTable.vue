<template>
    <div class="w-full overflow-hidden text-sm leading-none">
        <div v-if="displayRowHeaders" class="w-full flex">
            <all-cells-header v-if="displayColumnHeaders" />
            <column-header v-for="(field, index) in fields" :key="index">
                {{ fieldTitle(field, index) }}
            </column-header>
        </div>
        <div v-for="(dataRow, rowIndex) in data" class="w-full flex">
            <row-header v-if="displayRowHeaders">
                {{ rowIndex + 1 }}
            </row-header>
            <component class="border-b border-gray-600 border-l last:border-r inline-block w-64"
                       v-bind:is="gridField[field.type != undefined ? field.type : 'text'] ? gridField[field.type != undefined ? field.type : 'text'] : gridTextEditor"
                       v-for="(field, fieldIndex) in fields"
                       :value="dataRow[field.attribute]"
                       :key="fieldIndex"
                       @input="onInputField(field, dataRow, $event)" />
        </div>
        <div class="w-full flex" v-if="displayEmptyRow">
            <row-header v-if="displayRowHeaders">
                {{ data.length + 1 }}
            </row-header>
            <component class="border-b border-gray-600 border-l last:border-r inline-block w-64"
                       v-bind:is="gridField[field.type != undefined ? field.type : 'text'] ? gridField[field.type != undefined ? field.type : 'text'] : gridTextEditor"
                       v-for="(field, fieldIndex) in fields"
                       :value="editorEmptyObject[field.attribute]"
                       :key="fieldIndex" ref="newInputRow"
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
                }
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
            onInputField(field, dataRow, newValue) {
                let newObject = JSON.parse(JSON.stringify(dataRow));
                newObject[field.attribute] = newValue;
                this.$emit("input", newObject);
            },
            onInputNewRowField(field, dataRow, newValue) {
                this.onInputField(field, dataRow, newValue);
                this.$refs.newInputRow.forEach(r => r.resetInputControl());
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
        }
    }
</script>

<style scoped>

</style>
