<script setup lang="ts">
import { defineComponent, reactive, computed } from 'vue';
import { ReceiptItem } from '@/types/Receipts/receiptItem';
import ReceiptStore from '@/store/receiptStore';

import VueTextField from '@/Components/Inputs/VueTextField.vue';
import VueDropdownMenu from '@/Components/Inputs/VueDropdownMenu.vue';

import { addIcons } from "oh-vue-icons";
import { MdDeleteforeverOutlined } from "oh-vue-icons/icons";

const props = defineProps<{
	item: ReceiptItem
}>();

const item: ReceiptItem = props.item;
const id: string = item.id ?? crypto.randomUUID();

const total = computed(() => { return ((item.cost * item.count) ?? 0 ).toFixed(2)})

const validName = (value: string) => { return value.length == 0 ? 'Name is required' : true };
const validCategory = (value: string) => { return value.length == 0 ? 'Category cannot be blank' : true };
const defaultNumber = (item:any, defaultValue: number = 0): number => {
	return item == '' ? 0 : item as number;
}

addIcons(MdDeleteforeverOutlined);
defineExpose({item});
</script>

<script lang="ts">
defineComponent({
	name: "ReceiptDialogItem"
})
</script>
<template>
	<div class="relative grid grid-cols-5 gap-2 px-2 py-1 even:bg-slate-100 odd:bg-slate-200 hover:bg-neutral-400/50">
		<VueTextField v-model="item.name" :rules="validName" class="self-center" placeholder="Item Name" :name="`item_name-${id}`" />
		<VueTextField v-model="item.count" class="self-center mx-auto w-[75px]" :name="`item_count-${id}`" @blur="item.count = defaultNumber(item.count)"/>
		<div class="inline-flex flex-row w-[100px] self-center justify-self-center">
			<span class="self-center text-xl">$</span>
			<VueTextField v-model="item.cost" :name="`item_cost-${id}`" @blur="item.cost = defaultNumber(item.cost)"/>
		</div>
		<span class="text-lg self-center align-self-center">$ {{ total }}</span>
		<div class="self-center inline-flex flex-row">
			<VueDropdownMenu :rules="validCategory" :items="ReceiptStore.state.categories" v-model="item.category"/>
			<div class="inline-flex flex-row self-center icon-button">
				<VIcon class="ml-2" name="md-deleteforever-outlined" scale="1.5" @click="$emit('delete')"></VIcon>
				Edit
			</div>
		</div>
	</div>
</template>
<style lang="scss">
.table {
	&-head {
		@apply bg-slate-200;
		&-text {
			@apply text-lg font-semibold;
		}
	}
}
</style>