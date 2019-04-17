<template>

    <div class="flex flex-wrap bg-white rounded-sm shadow">

        <div class="flex items-center border-b border-gray-200 py-4 px-6 w-full">
            <h2 class="flex-1 tracking-wide text-xl text-gray-600 font-light leading-none">
                {{ title }}
            </h2>
            <button @click="showSource = !showSource" class="fill-current hover:text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M24 10.935v2.131l-8 3.947v-2.23l5.64-2.783-5.64-2.79v-2.223l8 3.948zm-16 3.848l-5.64-2.783 5.64-2.79v-2.223l-8 3.948v2.131l8 3.947v-2.23zm7.047-10.783h-2.078l-4.011 16h2.073l4.016-16z"/>
                </svg>
            </button>
            <button v-if="hasAttributes" @click="showAttributes = !showAttributes" class="fill-current hover:text-blue-600 ml-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M12 9c.552 0 1 .449 1 1s-.448 1-1 1-1-.449-1-1 .448-1 1-1zm0-2c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm-9 4c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm18 0c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm-9-6c.343 0 .677.035 1 .101v-3.101c0-.552-.447-1-1-1s-1 .448-1 1v3.101c.323-.066.657-.101 1-.101zm9 4c.343 0 .677.035 1 .101v-7.101c0-.552-.447-1-1-1s-1 .448-1 1v7.101c.323-.066.657-.101 1-.101zm0 10c-.343 0-.677-.035-1-.101v3.101c0 .552.447 1 1 1s1-.448 1-1v-3.101c-.323.066-.657.101-1 .101zm-18-10c.343 0 .677.035 1 .101v-7.101c0-.552-.447-1-1-1s-1 .448-1 1v7.101c.323-.066.657-.101 1-.101zm9 6c-.343 0-.677-.035-1-.101v7.101c0 .552.447 1 1 1s1-.448 1-1v-7.101c-.323.066-.657.101-1 .101zm-9 4c-.343 0-.677-.035-1-.101v3.101c0 .552.447 1 1 1s1-.448 1-1v-3.101c-.323.066-.657.101-1 .101z"/>
                </svg>
            </button>
        </div>

        <div class="border-b border-gray-200 w-full p-6">
            <div class="flex justify-between items-center mb-4 ">
                <span class="block flex-1 text-sm uppercase tracking-wide text-gray-600">Preview</span>

                <button v-for="size, label in previewSizesWithDefault" @click="selectedPreviewSize = size" class="ml-2 bg-blue-100 rounded py-1 px-2 text-xs uppercase" :class="{ 'bg-blue-800 text-white' : selectedPreviewSize === size }">
                    {{ label }}
                </button>
            </div>

            <iframe class="preview border border-gray-200 rounded" :src="previewUrl" frameborder="0" :width="selectedPreviewSize"/>
        </div>

        <div class="bg-white p-6 w-full">
            <span class="block mb-4 text-sm uppercase tracking-wide text-gray-600">Blade</span>
            <blade-preview :path="component.view_path" :slots="component.slots" :variables="component.variables"/>
        </div>

        <div v-if="component.text" class="border-t border-gray-200 w-full p-6">
            <span class="block mb-4 text-sm uppercase tracking-wide text-gray-600">Instructions</span>
            <div v-html="renderedText" class="myriad-markdown"></div>
        </div>

        <simple-modal v-model="showAttributes" title="Attributes">
            <table slot="body" class="attribute-table w-full">
                <thead>
                <tr class="border-b border-gray-200 text-sm uppercase tracking-wide text-gray-600">
                    <th class="py-2 pr-4">Attribute</th>
                    <th class="py-2 pl-4">Value</th>
                </tr>
                </thead>
                <tbody>
                <tr class="border-b border-gray-200 text-gray-900" v-for="value, attribute in component.attributes">
                    <td class="py-2 pr-4">{{ attribute }}</td>
                    <td class="py-2 pl-4">{{ value }}</td>
                </tr>
                </tbody>
            </table>
        </simple-modal>

        <simple-modal v-model="showSource" title="Source">
            <vue-code-highlight slot="body" class="text-xs">{{ component.source }}</vue-code-highlight>
        </simple-modal>

    </div>

</template>

<script>
import BladePreview from './BladePreview';
import MarkdownIt from 'markdown-it';
import SimpleModal from 'simple-modal-vue';
import { component as VueCodeHighlight } from 'vue-code-highlight';
import 'vue-code-highlight/themes/prism-okaidia.css';

export default {

    components: {
        BladePreview,
        SimpleModal,
        VueCodeHighlight,
    },

    props: {
        title: {
            type: String,
            required: true,
        },

        component: {
            type: Object,
            required: true,
        },

        previewRoute: {
            type: String,
            required: true,
        },

        previewSizes: {
            type: [Object, Array],
            default() {
                return {};
            }
        },
    },

    data() {
        return {
            showSource: false,
            showAttributes: false,
            selectedPreviewSize: '100%',
        }
    },

    computed: {

        previewUrl() {
            return `${this.previewRoute}?key=${this.component.key}`
        },

        renderedText() {
            return new MarkdownIt().render(this.component.text);
        },

        hasPreviewSize() {
            return this.selectedPreviewSize > 0;
        },

        previewSizesWithDefault() {
            return {
                full: '100%',
                ...this.previewSizes
            }
        },

        hasAttributes() {
            if (typeof this.component.attributes === 'Array') {
                return this.component.attributes.length > 0;
            }

            return Object.keys(this.component.attributes).length > 0
        }

    },

}
</script>

<style lang="css" scoped>
    .preview {
        transition: width ease-in-out 0.2s;
    }
</style>

<style lang="css">
    .myriad-markdown {
        @apply text-base text-gray-800 leading-normal;
    }

    .myriad-markdown > * + *, .myriad-markdown li > p + p {
        @apply mt-4;
    }

    .myriad-markdown li + li {
        @apply mt-2;
    }

    .myriad-markdown strong {
        @apply text-black font-bold;
    }

    .myriad-markdown a {
        @apply text-blue-600 font-semibold;
    }

    .myriad-markdown strong a {
        @apply font-bold;
    }

    .myriad-markdown h2 {
        @apply leading-tight text-xl font-bold text-black mb-2 mt-10;
    }

    .myriad-markdown h3 {
        @apply leading-tight text-lg font-bold text-black mt-8 -mb-2;
    }

    .myriad-markdown code {
        @apply font-mono text-sm inline bg-gray-200 px-1;
    }

    .myriad-markdown pre code {
        @apply block bg-black p-4 rounded;
    }

    .myriad-markdown blockquote {
        @apply border-l-4 border-gray-200 pl-4 italic;
    }

    .myriad-markdown ul, .myriad-markdown ol {
        @apply pl-4;
    }

    .myriad-markdown ul {
        @apply list-disc;
    }

    .myriad-markdown ol {
        @apply list-decimal;
    }

    @screen sm {
        .myriad-markdown ul, .myriad-markdown ol {
            @apply pl-10;
        }
    }
</style>
