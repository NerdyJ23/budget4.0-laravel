<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'BasicDialog',
	props: {
		persistent: {
			type: Boolean,
			required: false,
			default: false
		}
	},
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
        },
		hideSelf() {
			if (!this.persistent) {
				this.hide();
			}
		}
    },
})
</script>

<template>
	<div v-if="is.open" class="w-full bg-neutral-600/60 absolute object-center inset-0" @click.self="hideSelf">
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