<script setup>
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import { useToast } from 'primevue/usetoast';

const API_URL = import.meta.env.VITE_BASE_URL;
const router = useRouter();
const toast = useToast();
const route = useRoute();

const email = ref('');
const emailConfirm = ref('');
const first_name = ref('');
const last_name = ref('');
const address = ref('');
const city = ref('');
const postcode = ref('');
const state = ref('');

const selectedUserRole = ref([]);

const firstnameError = ref(false);
const lastnameError = ref(false);
const addressError = ref(false);
const postcodeError = ref(false);
const cityError = ref(false);
const stateError = ref(false);
const emailError = ref(false);
const emailConfirmError = ref(false);
const emailFormatError = ref(false);
const emailConfirmFormatError = ref(false);

const validate = () => {
    // Reset error states
    firstnameError.value = !first_name.value;
    lastnameError.value = !last_name.value;
    addressError.value = !address.value;
    postcodeError.value = !postcode.value;
    cityError.value = !city.value;
    stateError.value = !state.value;
    emailError.value = !email.value;
    emailConfirmError.value = !emailConfirm.value;
    emailFormatError.value = false;
    emailConfirmFormatError.value = false;

    // Validate email
    if (!email.value.trim()) {
        emailError.value = true; // Email is required
    } else if (!isValidEmail(email.value)) {
        emailFormatError.value = true; // Invalid email format
    }

    // Validate confirm email
    if (!emailConfirm.value.trim()) {
        emailConfirmError.value = true; // Confirm Email is required
    } else if (!isValidEmail(emailConfirm.value)) {
        emailConfirmFormatError.value = true; // Invalid email format
    } else if (email.value !== emailConfirm.value) {
        emailConfirmFormatError.value = true; // Emails do not match
    }

    // Return overall validation result
    return (
        !firstnameError.value &&
        !lastnameError.value &&
        !addressError.value &&
        !postcodeError.value &&
        !cityError.value &&
        !stateError.value &&
        !emailError.value &&
        !emailConfirmError.value &&
        !emailFormatError.value &&
        !emailConfirmFormatError.value
    );
};

const isValidEmail = (email) => {
    // Regular expression for basic email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
};


const BtnUserEdit = async () => {
    // Validate form inputs
    if (!validate()) {
        return;
    }

    try {
        const response = await axios.put(`${API_URL}/clients/${route.params.id}`, {
            email: email.value,
            first_name: first_name.value,
            last_name: last_name.value,
            address: address.value,
            city: city.value,
            postcode: postcode.value,
            state: state.value
        });

        console.log(response.status);
        //Handle API response as needed
        if (response.data.id > 0) {
            toast.add({ severity: 'success', summary: 'Success', detail: 'Client updated', life: 3000 });

            router.push({ name: 'dashboard' });
        } else {
            toast.add({ severity: 'error', summary: 'Error', detail: response.data.error, life: 4000 });
        }

        // Handle API response as needed
        console.log('Client edited:', response);
    } catch (error) {
        console.error('Failed to edit Client:', error);
        toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update Client', life: 3000 });
        // Optionally, show a toast or error message here
    }
};

const BtnCancel = () => {
    router.push({ name: 'dashboard' });
};


const getUserDetails = async (id) => {
    try {
        const response = await axios.get(`${API_URL}/clients/${id}`);
      
        email.value = response.data.email
        emailConfirm.value = response.data.email
        first_name.value = response.data.first_name
        last_name.value = response.data.last_name
        address.value = response.data.address
        city.value = response.data.city
        postcode.value = response.data.postcode
        state.value = response.data.state
    } catch (error) {
        console.error('Error fetching seniority details:', error);
    }
};

onMounted(() => {
    getUserDetails(route.params.id);
});
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="col-12 md:col-12">
                    <h5>Add Client</h5>
                    <div class="p-fluid formgrid grid flex justify-content-center">
                        <div class="field col-12 md:col-8">
                            <div class="field col-12 md:col-12">
                                <label for="TxtEmployeeName">First Name</label>
                                <InputText id="TxtEmployeeName" type="text" v-model="first_name" placeholder="Enter first name" :class="{ 'p-invalid': firstnameError }" />
                                <small v-if="firstnameError" class="p-error">First name is required!</small>
                            </div>
                            <div class="field col-12 md:col-12">
                                <label for="TxtEmployeeName">Last Name</label>
                                <InputText id="TxtEmployeeName" type="text" v-model="last_name" placeholder="Enter last name" :class="{ 'p-invalid': lastnameError }" />
                                <small v-if="lastnameError" class="p-error">Last name is required!</small>
                            </div>
                            <div class="field col-12">
                                <label for="user_email">Email</label>
                                <InputText v-model="email" id="DfUserEmail" type="text" placeholder="Enter Email" :class="{ 'p-invalid': emailError || emailFormatError }" />
                                <small v-if="emailError" class="p-error">Email is required!</small>
                                <small v-if="emailFormatError" class="p-error">Invalid email format!</small>
                            </div>
                            <div class="field col-12">
                                <label for="user_email">Confirm Email</label>
                                <InputText v-model="emailConfirm" id="DfUserEmail" type="text" placeholder="Enter Email" :class="{ 'p-invalid': emailConfirmError || emailConfirmFormatError }" />
                                <small v-if="emailConfirmError" class="p-error">Email is required!</small>
                                <small v-else-if="emailConfirmFormatError" class="p-error">Emails do not match!</small>
                            </div>
                            <div class="field col-12 md:col-12">
                                <label for="TxtEmployeeRemarks">Address</label>
                                <Textarea id="TxtEmployeeRemarks" v-model="address" rows="4" placeholder="Address" :class="{ 'p-invalid': addressError }" />
                                <small v-if="addressError" class="p-error">Address is required!</small>
                            </div>
                            <div class="field col-12 md:col-12">
                                <label for="TxtEmployeeName">City</label>
                                <InputText id="TxtEmployeeName" type="text" v-model="city" placeholder="Enter city" :class="{ 'p-invalid': cityError }" />
                                <small v-if="cityError" class="p-error">City is required!</small>
                            </div>
                            <div class="field col-12 md:col-12">
                                <label for="TxtEmployeeName">Postcode</label>
                                <InputText id="TxtEmployeeName" type="text" v-model="postcode" placeholder="Enter postcode" :class="{ 'p-invalid': postcodeError }" />
                                <small v-if="postcodeError" class="p-error">Postcode is required!</small>
                            </div>
                            <div class="field col-12 md:col-12">
                                <label for="TxtEmployeeName">State</label>
                                <InputText id="TxtEmployeeName" type="text" v-model="state" placeholder="Enter state" :class="{ 'p-invalid': stateError }" />
                                <small v-if="stateError" class="p-error">State is required!</small>
                            </div>

                            <div class="col-12 flex justify-content-center py-3 gap-2 button-group">
                                <Button label="Save" class="col-3 md:col-3 py-2" @click="BtnUserEdit" />
                                <Button severity="secondary" label="Cancel" class="col-3 md:col-3 py-2" @click="BtnCancel" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.button-group {
    display: flex;
    justify-content: center;
    gap: 1rem;
}

@media (max-width: 500px) {
    .button-group {
        flex-direction: column;
        align-items: center;
    }

    .button-group .p-button {
        width: 100%;
    }
}
</style>
