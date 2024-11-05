
import pinia from '../store';
import { useMyStore } from '../store/myStore';
import { setActivePinia } from 'pinia';

setActivePinia(pinia);

const myStore = useMyStore();

console.log(myStore.myData);
console.log(myStore.upperCaseData);
myStore.updateData('new data');
