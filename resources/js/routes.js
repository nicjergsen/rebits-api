const CreateUsers = () => import('./components/users/CreateUsersComponent.vue');
const EditUsers = () => import('./components/users/EditUsersComponent.vue');
const ListUsers = () => import('./components/users/ListUsersComponent.vue');

const CreateVehicles = () => import('./components/vehicles/CreateVehiclesComponent.vue');
const EditVehicles = () => import('./components/vehicles/EditVehiclesComponent.vue');
const ListVehicles = () => import('./components/vehicles/ListVehiclesComponent.vue');

const ImportExcel = () => import('./components/excel/ImportExcelComponent.vue');
const ListOwnersHistory = () => import('./components/history/ListOwnersHistory.vue');

export const routes = [
    {
        name: 'listUsers',
        path: '/usuarios',
        component: ListUsers
    },
    {
        name: 'createUser',
        path: '/usuario/crear',
        component: CreateUsers
    },
    {
        name: 'editUser',
        path: '/usuario/editar/:id',
        component: EditUsers
    },
    {
        name: 'listVehicles',
        path: '/vehiculos',
        component: ListVehicles
    },
    {
        name: 'createVehicle',
        path: '/vehiculo/crear',
        component: CreateVehicles
    },
    {
        name: 'editVehicle',
        path: '/vehiculo/editar/:id',
        component: EditVehicles
    },
    {
        name: 'importExcel',
        path: '/cargamasiva',
        component: ImportExcel
    },
    {
        name: 'listOwnersHistory',
        path: '/historico',
        component: ListOwnersHistory
    },
    
];