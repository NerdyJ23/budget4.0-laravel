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
	<div v-if="is.open" class="w-full bg-neutral-600/60 absolute object-center inset-0" @click.self="is.open = false">
		<dialog
			:open="is.open"
			:class="[
				'object-center shadow-md px-5 py-3 inset-0 rounded-md'
			]"
		>
		<slot></slot>
		</dialog>
	</div>
</template>