<template>
    <div class="row">
        <div class="col-12 mb-2">
            <h1>Histórico de dueños anteriores de los vehículos</h1>
            <small>Listado de dueños anteriores (incluyendo los eliminados)</small>
        </div>
        <div class="col-12 mb-2">
            <input type="text" class="form-control" v-model="searchQuery" placeholder="Buscar por marca, modelo, año, nombre o apellidos">
        </div>
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>ID</th>
                            <th>Patente</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Año</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="history in filteredHistory" :key="history.id">
                            <td>{{ history.id }}</td>
                            <td>{{ history.vehicle.patente }}</td>
                            <td>{{ history.vehicle.marca }}</td>
                            <td>{{ history.vehicle.modelo }}</td>
                            <td>{{ history.vehicle.año }}</td>
                            <td>{{ history.user.nombre }}</td>  
                            <td>{{ history.user.apellidos }}</td>  
                        </tr>
                        <tr v-if="filteredHistory.length === 0">
                            <td colspan="7" class="text-center">No se encontraron vehículos.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ListHistoryOwners",
    data() {
        return {
            history: [],
            searchQuery: ""
        };
    },
    mounted() {
        this.listHistory();
    },
    methods: {
        async listHistory() {
            try {
                const response = await this.axios.get('/api/history');
                this.history = response.data;
            } catch (error) {
                console.log(error);
                this.history = [];
            }
        }
    },
    computed: {
        filteredHistory() {
            const searchLower = this.searchQuery.toLowerCase();
            return this.history.filter(item => {
                const vehicle = item.vehicle;
                const user = item.user;

                return (
                    vehicle.patente.toLowerCase().includes(searchLower) ||
                    vehicle.marca.toLowerCase().includes(searchLower) ||
                    vehicle.modelo.toLowerCase().includes(searchLower) ||
                    vehicle.año.toString().includes(searchLower) ||
                    (user.nombre.toLowerCase().includes(searchLower)) ||
                    (user.apellido.toLowerCase().includes(searchLower))
                );
            });
        }
    }
};
</script>