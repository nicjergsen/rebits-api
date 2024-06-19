<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h4>Crear Vehículo</h4></div>
                <div class="card-body">
                    <form @submit.prevent="createVehicle">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Marca</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="vehicle.marca"
                                        @input="validateMarca"
                                    >
                                    <p v-if="errors.marca" class="text-danger">{{ errors.marca }}</p>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Modelo</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="vehicle.modelo"
                                        @input="validateModelo"
                                    >
                                    <p v-if="errors.modelo" class="text-danger">{{ errors.modelo }}</p>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Patente</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="vehicle.patente"
                                        @input="validatePatente"
                                    >
                                    <p v-if="errors.patente" class="text-danger">{{ errors.patente }}</p>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Año</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="vehicle.año"
                                        @input="validateAño"
                                    >
                                    <p v-if="errors.año" class="text-danger">{{ errors.año }}</p>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Precio</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="vehicle.precio"
                                        @input="validatePrecio"
                                    >
                                    <p v-if="errors.precio" class="text-danger">{{ errors.precio }}</p>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Email del dueño</label>
                                    <input 
                                        type="email" 
                                        class="form-control" 
                                        v-model="vehicle.email"
                                        @input="validateEmail"
                                    >
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
    name:"createVehicle",
    components: {
        Spinner,
    },
    data(){
        return {
            vehicle:{
                marca:"",
                modelo:"",
                patente:"",
                año:"",
                precio:"",
                email:""
            },
            errors: {
                marca: '',
                modelo: '',
                patente: '',
                año: '',
                precio: '',
                email: ''
            },
            loading: false
        }
    },
    computed: {
        isValid() {
            return this.vehicle.marca && this.vehicle.modelo && this.vehicle.patente.length === 6 && this.vehicle.año && this.vehicle.precio && this.vehicle.email && !this.errors.marca && !this.errors.modelo && !this.errors.patente && !this.errors.año && !this.errors.precio && !this.errors.email;
        }
    },
    methods:{
        validateMarca() {
            if (!this.vehicle.marca) {
                this.errors.marca = 'La marca es requerida';
            } else {
                this.errors.marca = '';
            }
        },
        validateModelo() {
            if (!this.vehicle.modelo) {
                this.errors.modelo = 'El modelo es requerido';
            } else {
                this.errors.modelo = '';
            }
        },
        validatePatente() {
            if (!this.vehicle.patente) {
                this.errors.patente = 'La patente es requerida';
            } else if (this.vehicle.patente.length !== 6) {
                this.errors.patente = 'La patente debe tener 6 caracteres';
            } else {
                this.errors.patente = '';
            }
        },
        validateAño() {
            if (!this.vehicle.año) {
                this.errors.año = 'El año es requerido';
            } else if (isNaN(this.vehicle.año)) {
                this.errors.año = 'El año debe ser un número';
            } else {
                this.errors.año = '';
            }
        },
        validatePrecio() {
            if (!this.vehicle.precio) {
                this.errors.precio = 'El precio es requerido';
            } else if (isNaN(this.vehicle.precio)) {
                this.errors.precio = 'El precio debe ser un número';
            } else {
                this.errors.precio = '';
            }
        },
        validateEmail() {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!this.vehicle.email) {
                this.errors.email = 'El email es requerido';
            } else if (!emailPattern.test(this.vehicle.email)) {
                this.errors.email = 'El email no es válido';
            } else {
                this.errors.email = '';
            }
        },
        async createVehicle(){
            if (!this.isValid) {
                this.$toastr.error("Por favor, complete todos los campos correctamente.");
                return;
            }
            this.loading = true;
            this.vehicle.patente = this.vehicle.patente.toUpperCase();
            try {
                await this.axios.post('/api/vehicles', this.vehicle);
                this.$toastr.success("Vehículo creado con éxito");
                this.$router.push({name:"listVehicles"});
            } catch (error) {
                console.error("Error al crear el vehículo:", error);
                this.$toastr.error("Hubo un error al crear el vehículo");
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>