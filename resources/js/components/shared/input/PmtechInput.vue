<template>
    <validation-provider class="relative appearance-none w-full text-input"
                        tag="div"
                        v-slot="{ errors, required, ariaInput, ariaMsg }"
                        :name="name || label" :rules="rules" :vid="vid">
        <input
            class="w-full py-2 px-3 leading-normal bg-transparent border-b"
            ref="input" v-bind="ariaInput" v-model="innerValue"
            :class="{ 'border-gray-700': !errors[0], 'border-red-600': errors[0], 'has-value': hasValue }"
            :id="name" :placeholder="placeholder" :type="type">
        <label class="absolute block inset-0 w-full px-2 py-2 leading-normal"
               :for="name"
               :class="{ 'text-gray-700': !errors[0], 'text-red-600': errors[0] }"
               @click="$refs.input.focus()">
            <span>{{ label || name }}</span>
            <span>{{ required ? ' *' : '' }}</span>
        </label>
        <span class="block text-red-600 text-xs absolute bottom-0 left-0"
              v-bind="ariaMsg"
              v-if="errors[0]">
            {{ errors[0] }}
        </span>
    </validation-provider>
</template>

<script>
    export default {
        computed: {
            hasValue() {
                return !!this.innerValue;
            }
        },
        created() {
            if(this.value) {
                this.innerValue = this.value;
            }
        },
        data() {
            return {
                innerValue: ""
            };
        },
        name: "PmtechInput",
        props: {
            label: {
                default: "",
                type: String
            },
            name: {
                default: "",
                type: String
            },
            placeholder: {
                default: "",
                type: String
            },
            rules: {
                default: "",
                type: [Object, String]
            },
            type: {
                default: "text",
                type: String,
                validator(value) {
                    return [
                        "email",
                        "number",
                        "password",
                        "search",
                        "tel",
                        "text",
                        "url"
                    ].includes(value)
                }
            },
            value: {
                type: null,
                default: ""
            },
            vid: {
                default: undefined,
                type: String
            }
        },
        watch: {
            innerValue(value) {
                this.$emit("input", value);
            },
            value(val) {
                if(val !== this.innerValue) {
                    this.innerVal = val;
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .text-input {
        padding-bottom: 18px;
        input {
            position: relative;
            z-index: 19;
            padding-top: 1.4rem;

            &.has-value, &:focus {
                outline: none;
            }
        }

        label {
            margin-top: 1rem;
            user-select: none;
        }

        input.has-value ~ label, input:focus ~ label {
            font-size: 0.6rem;
            margin-top: 0;
            transition: all 0.2s ease-in-out;
        }
    }
</style>
