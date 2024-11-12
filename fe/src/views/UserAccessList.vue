<script setup>
import { onMounted, ref } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Menu from 'primevue/menu';
import Toast from 'primevue/toast';
import { useRouter } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import { FilterMatchMode } from 'primevue/api';
import axios from 'axios';

const router = useRouter();
const confirm = useConfirm();
const toast = useToast();

const API_URL = import.meta.env.VITE_BASE_URL;

const users = ref([]);
const getUserAccessList = async () => {
    try {
        const response = await axios.get(`${API_URL}/clients`);
        users.value = response.data.map((item, index) => ({
            ...item,
            runningNumber: index + 1,
            is_active: item.active === 1 ? true : false
        }));
    } catch (error) {
        console.error('Failed to fetch user access list:', error);
    }
};

const filters = ref({
    active: { value: null, matchMode: FilterMatchMode.EQUALS },
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});

const isMobileView = ref(window.innerWidth < 991);

const onResize = () => {
    isMobileView.value = window.innerWidth < 991;
};

onMounted(() => {
    window.addEventListener('resize', onResize);
    getUserAccessList();
});

const BtnUserDelete = (client, event) => {
    const action = client.active === 1 ? 'delete' : 'restore';
    const message = `Do you want to ${action} this client?`;
    const confirmLabel = client.active === 1 ? 'Delete' : 'Restore';

    confirm.require({
        target: event.currentTarget,
        message: message,
        icon: 'pi pi-info-circle',
        rejectClass: 'p-button-secondary p-button-outlined p-button-sm',
        acceptClass: client.active === 1 ? 'p-button-danger p-button-sm' : 'p-button-success p-button-sm',
        rejectLabel: 'Cancel',
        acceptLabel: confirmLabel,
        accept: async () => {
            try {
                const url = `${API_URL}/clients/remove/${client.id}`;
                const response = await axios.post(url, {
                    id: client.id,
                    active: client.active === 1 ? 0 : 1
                });
                if (response.status !== 200) {
                    throw new Error('Failed to delete the Client');
                }

                getUserAccessList();

                const successMessage = client.active === 1 ? 'Client deleted successfully' : 'Client restored successfully';
                toast.add({ severity: 'info', summary: 'Confirmed', detail: successMessage, life: 3000 });
            } catch (error) {
                console.error('Failed to change Client status:', error);
                toast.add({ severity: 'error', summary: 'Error', detail: `Failed to change Client status`, life: 3000 });
            }
        }
    });
};

const BtnUserAdd = () => {
    router.push({ name: 'useraccessform' });
};

const BtnUserEdit = (user) => {
    router.push({ name: 'useraccessedit', params: { id: user.id } });
};

const menuItems = [
    {
        label: 'Add User',
        icon: 'pi pi-plus',
        command: () => {
            BtnUserAdd();
        }
    }
];
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="flex justify-content-between align-items-center">
                    <h5 class="col-6 md:col-6 mb-0">Client</h5>
                    <div v-if="!isMobileView" class="col-6 md:col-6 flex justify-content-end">
                        <Button label="Add Client" icon="pi pi-plus" @click="BtnUserAdd" />
                    </div>
                    <div v-else>
                        <Menu :model="menuItems" popup ref="menu" />
                        <Button icon="p-link pi pi-ellipsis-v" class="ml-auto" severity="secondary" @click="$refs.menu.toggle($event)" link />
                    </div>
                </div>

                <DataTable
                    class="md:col-12 mt-0"
                    v-model:filters="filters"
                    :value="users"
                    filterDisplay="menu"
                    :paginator="true"
                    :row-hover="false"
                    :rows="10"
                    removableSort
                    :globalFilterFields="['first_name', 'last_name', 'email', 'address', 'state', 'city', 'postcode', 'runningNumber']"
                    scrollable
                >
                    <IconField iconPosition="left">
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
                    </IconField>
                    <template #empty> No record found. </template>

                    <Column class="md:col-1" field="runningNumber" id="DfUserAcessID" header="No." sortable />
                    <Column class="md:col-3" field="first_name" id="DfUserName" header="First Name" sortable />
                    <Column class="md:col-3" field="last_name" id="DfUserName" header="Last Name" sortable />
                    <Column class="md:col-3" field="email" id="DfUserName" header="email" sortable />
                    <Column class="md:col-4" field="address" id="DfUserRole" header="Address" sortable />
                    <Column class="md:col-4" field="state" id="DfUserRole" header="State" sortable />
                    <Column class="md:col-1" field="active" dataType="boolean" header="Status" sortable>
                        <template #body="{ data }">
                            <Badge value="Active" severity="success" size="small" v-if="data.active === 1" raised></Badge>
                            <Badge value="Inactive" severity="danger" size="small" v-else raised></Badge>
                        </template>
                    </Column>
                    <Column class="md:col-1" field="action" header="Action">
                        <template #body="{ data }">
                            <div class="flex justify-content-center">
                                <Button icon="pi pi-pencil" class="mr-2" severity="primary" v-tooltip.top="'edit'" @click="BtnUserEdit(data)" rounded raised />

                                <Toast />
                                <ConfirmPopup></ConfirmPopup>
                                <Button
                                    @click="(event) => BtnUserDelete(data, event)"
                                    :icon="data.active ? 'pi pi-times' : 'pi pi-refresh'"
                                    :severity="data.active ? 'danger' : 'secondary'"
                                    v-tooltip.top="data.active ? 'deactivate' : 'reactivate'"
                                    rounded
                                    raised
                                />
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </div>
</template>

<style scoped>
.uploader-ui {
  border-width: 2px;
  border-style: solid;
  border-radius: 66px;
  color: #6c757d;
}

@media (max-width: 991px) {
    .toplist {
        flex-direction: column;
    }
}
</style>
