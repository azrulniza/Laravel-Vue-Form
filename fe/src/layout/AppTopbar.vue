<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { useLayout } from '@/layout/composables/layout';
import router from '../router';
import { useAuthStore } from '@/store/auth';
import axios from 'axios';

const API_URL = import.meta.env.VITE_BASE_URL;
const authStore = useAuthStore();
const user = computed(() => authStore.getUser);
//const isAuthenticated = computed(() => authStore.isAuthenticated);

const name = ref(user.value.employee_name || '');
const token = authStore.accessToken;
const initials = computed(() => getInitials(name.value));
const { layoutConfig, onMenuToggle } = useLayout();

const outsideClickListener = ref(null);
const topbarMenuActive = ref(false);

console.log('authStore :', token);
console.log('local sotrage :', localStorage.getItem('accessToken'));
const BtnSignOut = async () => {
    authStore.clearAuthData(); // Clear auth data
    const response = await axios.post(
        `${API_URL}/logout`,
        {},
        {
            headers: { Authorization: `Bearer ${token}` }
        }
    );

    router.push({ name: 'login' });
};

const menu = ref();

const items = ref([
    {
        label: 'Manage Profile',
        icon: 'pi pi-user',
        command: () => {
            router.push({ name: 'profile' });
        }
    },
    {
        label: 'Sign Out',
        icon: 'pi pi-sign-out',
        command: () => {
            BtnSignOut();
        }
    }
]);

const toggle = (event) => {
    menu.value.toggle(event);
};

onMounted(() => {
    bindOutsideClickListener();
});

onBeforeUnmount(() => {
    unbindOutsideClickListener();
});

const logoUrl = computed(() => {
    return `/layout/images/png-transparent-ytl-hd-logo.png`;
});

const bindOutsideClickListener = () => {
    if (!outsideClickListener.value) {
        outsideClickListener.value = (event) => {
            if (isOutsideClicked(event)) {
                topbarMenuActive.value = false;
            }
        };
        document.addEventListener('click', outsideClickListener.value);
    }
};
const unbindOutsideClickListener = () => {
    if (outsideClickListener.value) {
        document.removeEventListener('click', outsideClickListener);
        outsideClickListener.value = null;
    }
};
const isOutsideClicked = (event) => {
    if (!topbarMenuActive.value) return;

    const sidebarEl = document.querySelector('.layout-topbar-menu');
    const topbarEl = document.querySelector('.layout-topbar-menu-button');

    return !(sidebarEl.isSameNode(event.target) || sidebarEl.contains(event.target) || topbarEl.isSameNode(event.target) || topbarEl.contains(event.target));
};
</script>

<template>
    <div class="layout-topbar">
        <router-link to="/" class="layout-topbar-logo">
            YTL
        </router-link>

        <button class="p-link layout-menu-button layout-topbar-button" @click="onMenuToggle()" v-tooltip.right="'menu bar'">
            <i class="pi pi-bars"></i>
        </button>
    </div>
</template>

<style lang="scss" scoped></style>
