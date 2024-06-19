<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h4>Editar Vehículo</h4></div>
                <div class="card-body">
                    <form @submit.prevent="editVehicle">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Marca</label>
                                    <input type="text" class="form-control" v-model="vehicle.marca">
                                    <p v-if="errors.marca" class="text-danger">{{ errors.marca }}</p>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Modelo</label>
                                    <input type="text" class="form-control" v-model="vehicle.modelo">
                                    <p v-if="errors.modelo" class="text-danger">{{ errors.modelo }}</p>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Patente</label>
                                    <input type="text" class="form-control" v-model="vehicle.patente">
                                    <p v-if="errors.patente" class="text-danger">{{ errors.patente }}</p>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Año</label>
                                    <input type="text" class="form-control" v-model="vehicle.año">
                                    <p v-if="errors.año" class="text-danger">{{ errors.año }}</p>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Precio</label>
                                    <input type="text" class="form-control" v-model="vehicle.precio">
                                    <p v-if="errors.precio" class="text-danger">{{ errors.precio }}</p>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Email del dueño</label>
                                    <input type="email" class="form-control" v-model="vehicle.email">
                                    <p v-if="errors.email" class="text-danger">{{ errors.email }}</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" :disabled="!isValid">
                                    <span v-if="!loading">Guardar</span>
                                    <Spinner v-if="loading"></Spinner>
                                </button>
                            </div>
                        </div>                                             
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Spinner from '../spinner/SpinnerComponent.vue';

export default {
    name: "editVehicle",
    components: {
        Spinner,
    },
    data() {
        return {
            vehicle: {
                marca: "",
                modelo: "",
                patente: "",
                año: "",
                precio: "",
                email: ""
            },
            errors: {
                marca: "",
                modelo: "",
                patente: "",
                año: "",
                precio: "",
                email: ""
            },
            loading: false
        };
    },
    mounted() {
        this.getVehicleData();
    },
    computed: {
        isValid() {
            return (
                this.vehicle.marca &&
                this.vehicle.modelo &&
                this.vehicle.patente.length === 6 &&
                this.vehicle.año &&
                this.vehicle.precio &&
                this.vehicle.email &&
                !this.errors.marca &&
                !this.errors.modelo &&
                !this.errors.patente &&
                !this.errors.año &&
                !this.errors.precio &&
                !this.errors.email
            );
        }
    },
    methods: {
        async getVehicleData() {
            try {
                const response = await this.axios.get(
                    `/api/vehicles/${this.$route.params.id}`
                );
                const { marca, modelo, patente, año, precio, user } = response.data;

                this.vehicle.marca = marca;
                this.vehicle.modelo = modelo;
                this.vehicle.patente = patente;
                this.vehicle.año = año;
                this.vehicle.precio = precio;

                if (user && user.email) {
                    this.vehicle.email = user.email;
                } else {
                    this.vehicle.email = ''; 
                }
            } catch (error) {
                console.error("Error al obtener los datos del vehículo:", error);
                this.$toastr.error("Hubo un error al obtener los datos del vehículo");
            }
        },
        async editVehicle() {
            if (!this.isValid) {
                this.$toastr.error(
                    "Por favor, complete todos los campos correctamente."
                );
                return;
            }

            this.loading = true; // Activamos el spinner al iniciar la edición

            try {
                await this.axios.put(
                    `/api/vehicles/${this.$route.params.id}`,
                    this.vehicle
                );
                this.$toastr.success("Vehículo editado con éxito");
                this.$router.push({ name: "listVehicles" });
            } catch (error) {
                console.error("Error al editar el vehículo:", error);
                this.$toastr.error("Hubo un error al editar el vehículo");
            } finally {
                this.loading = false; // Desactivamos el spinner al finalizar la edición
            }
        }
    }
};
</script>
