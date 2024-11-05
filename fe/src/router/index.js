import { createRouter, createWebHistory } from 'vue-router';
import AppLayout from '@/layout/AppLayout.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            component: AppLayout,
            children: [
                {
                    path: '/',
                    name: 'dashboard',
                    component: () => import('@/views/UserAccessList.vue')
                },
                {
                    path: '/client/add',
                    name: 'useraccessform',
                    component: () => import('@/views/UserAccessForm.vue')
                },
                {
                    path: '/client/edit/:id',
                    name: 'useraccessedit',
                    component: () => import('@/views/UserAccessFormEdit.vue')
                }
            ]
        }
    ]
});

export default router;
