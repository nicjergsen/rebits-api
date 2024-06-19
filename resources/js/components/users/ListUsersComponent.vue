<template>
    <div class="row">
        <div class="col-12 mb-2">
            <h1>Listado de Usuarios</h1>
            <router-link :to="{ name: 'createUser' }" class="btn btn-success"><i class="fas fa-plus-circle"></i> Nuevo</router-link>
        </div>
        <div class="col-12 mb-2">
            <input type="text" class="form-control" v-model="searchQuery" placeholder="Buscar por nombre, apellidos o correo">
        </div>
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Correo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in filteredUsers" :key="user.id">
                            <td>{{ user.id }}</td>
                            <td>{{ user.nombre }}</td>
                            <td>{{ user.apellidos }}</td>
                            <td>{{ user.email }}</td>
                            <td>
                                <router-link :to="{ name: 'editUser', params: { id: user.id } }" class="btn btn-info"><i class="fas fa-edit"></i> Editar</router-link>
                                <a type="button" @click="deleteUser(user.id)" class="btn btn-danger"><i class="fas fa-trash"></i> Eliminar</a>
                            </td>
                        </tr>
                        <tr v-if="filteredUsers.length === 0">
                            <td colspan="8" class="text-center">No se encontraron usuarios.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "listUsers",
    data() {
        return {
            users: [],
            searchQuery: ""
        };
    },
    mounted() {
        this.listUsers();
    },
    methods: {
        async listUsers() {
            try {
                const response = await this.axios.get('/api/users');
                this.users = response.data;
            } catch (error) {
                console.log(error);
                this.users = [];
            }
        },
        async deleteUser(id) {
            if (confirm("¿Confirma eliminar el registro?")) {
                try {
                    await this.axios.delete(`/api/users/${id}`);
                    this.$toastr.success("Usuario eliminado con éxito");
                    this.listUsers();
                } catch (error) {
                    console.log(error);
                    this.$toastr.error("Hubo un error al eliminar el usuario");
                }
            }
        }
    },
    computed: {
        filteredUsers() {
            const searchLower = this.searchQuery.toLowerCase();
            return this.users.filter(user => {
                return (
                    user.nombre.toLowerCase().includes(searchLower) ||
                    user.apellidos.toLowerCase().includes(searchLower) ||
                    user.email.toLowerCase().includes(searchLower)
                );
            });
        }
    }
};
</script>