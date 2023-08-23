import { createStore } from 'vuex'
import GenericStore from './genericStore';
import ReceiptStore from './receiptStore';

export default createStore({
    modules: {
		GenericStore,
		ReceiptStore
	}
})
