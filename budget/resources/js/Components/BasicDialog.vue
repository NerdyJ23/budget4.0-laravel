<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'BasicDialog',
    data() {
        return {
            is: {
                open: false
            }
        };
    },
    methods: {
        show(): void {
            this.is.open = true;
        },
        hide(): void {
            this.is.open = false;
        }
    },
})
</script>

<template>
	<dialog
		:open="is.open"
		:class="[
			{'backdrop:bg-gray-500': is.open},
			'sticky center shadow-md px-5 py-3'
		]"
	>
	<slot></slot>
	</dialog>
</template>