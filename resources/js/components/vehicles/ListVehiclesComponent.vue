<template>
    <div class="row">
        <div class="col-12 mb-2">
            <h1>Listado de Vehículos</h1>
            <router-link :to="{ name: 'createVehicle' }" class="btn btn-success"><i class="fas fa-plus-circle"></i> Nuevo</router-link>
        </div>
        <div class="col-12 mb-2">
            <input type="text" class="form-control" v-model="searchQuery" placeholder="Buscar...">
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
                            <th>Precio</th>
                            <th>Dueño</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="vehicle in filteredVehicles" :key="vehicle.id">
                            <td>{{ vehicle.id }}</td>
                            <td>{{ vehicle.patente }}</td>
                            <td>{{ vehicle.marca }}</td>
                            <td>{{ vehicle.modelo }}</td>
                            <td>{{ vehicle.año }}</td>
                            <td>{{ vehicle.precio }}</td>
                            <td>{{ getOwnerFullName(vehicle.user) }}</td>
                            <td>
                                <router-link :to="{ name: 'editVehicle', params: { id: vehicle.id } }" class="btn btn-info"><i class="fas fa-edit"></i> Editar</router-link>
                                <a type="button" @click="deleteVehicle(vehicle.id)" class="btn btn-danger"><i class="fas fa-trash"></i> Eliminar</a>
                            </td>
                        </tr>
                        <tr v-if="filteredVehicles.length === 0">
                            <td colspan="8" class="text-center">No se encontraron vehículos.</td>
                        </tr>
                    </tbody>
                </table>
            </div>                          
        </div>
    </div>
</template>

<script>
export default {
    name: "listVehicles",
    data() {
        return {
            vehicles: [],
            searchQuery: '',
        };
    },
    computed: {
        filteredVehicles() {
            if (!this.searchQuery) {
                return this.vehicles;
            }
            const query = this.searchQuery.toLowerCase();
            return this.vehicles.filter(vehicle =>
                vehicle.patente.toLowerCase().includes(query) ||
                vehicle.marca.toLowerCase().includes(query) ||
                vehicle.modelo.toLowerCase().includes(query) ||
                vehicle.año.toString().includes(query) ||
                vehicle.precio.toString().includes(query) ||
                this.getOwnerFullName(vehicle.user).toLowerCase().includes(query)
            );
        }
    },
    mounted() {
        this.listVehicles();
    },
    methods: {
        async listVehicles() {
            try {
                const response = await this.axios.get('/api/vehicles');
                this.vehicles = response.data;
            } catch (error) {
                console.error("Error al obtener la lista de vehículos:", error);
                this.$toastr.error("Hubo un error al obtener la lista de vehículos");
            }
        },
        deleteVehicle(id) {
            if (confirm("¿Confirma eliminar el registro?")) {
                this.axios.delete(`/api/vehicles/${id}`)
                    .then(response => {
                        this.$toastr.success("Vehículo eliminado con éxito");
                        this.listVehicles();
                    })
                    .catch(error => {
                        console.error("Error al eliminar el vehículo:", error);
                        this.$toastr.error("Hubo un error al eliminar el vehículo");
                    });
            }
        },
        getOwnerFullName(user) {
            if (user) {
                return `${user.nombre} ${user.apellidos}`;
            }
            return "Sin dueño asignado";
        },
    }
};
</script>
