<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h4>Crear Usuario</h4></div>
                <div class="card-body">
                    <form @submit.prevent="createUser">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="user.nombre"
                                        @input="validateNombre"
                                    >
                                    <p v-if="errors.nombre" class="text-danger">{{ errors.nombre }}</p>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Apellidos</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        v-model="user.apellidos"
                                        @input="validateApellidos"
                                    >
                                    <p v-if="errors.apellidos" class="text-danger">{{ errors.apellidos }}</p>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Correo</label>
                                    <input 
                                        type="email" 
                                        class="form-control" 
                                        v-model="user.email"
                                        @input="validateEmail"
                                    >
                                    <p v-if="errors.email" class="text-danger">{{ errors.email }}</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" :disabled="!isValid">Guardar</button>
                                <div v-if="loading" class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Cargando...</span>
                                </div>
                            </div>
                        </div>                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name:"createUser",
    data(){
        return {
            user:{
                nombre:"",
                apellidos:"",
                email:"",
            },
            errors: {
                nombre: '',
                apellidos: '',
                email: ''
            },
            loading: false
        }
    },
    computed: {
        isValid() {
            return this.user.nombre && this.user.apellidos && this.user.email && !this.errors.nombre && !this.errors.apellidos && !this.errors.email;
        }
    },
    methods:{
        validateNombre() {
            if (!this.user.nombre) {
                this.errors.nombre = 'El nombre es requerido';
            } else {
                this.errors.nombre = '';
            }
        },
        validateApellidos() {
            if (!this.user.apellidos) {
                this.errors.apellidos = 'Los apellidos son requeridos';
            } else {
                this.errors.apellidos = '';
            }
        },
        validateEmail() {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!this.user.email) {
                this.errors.email = 'El correo es requerido';
            } else if (!emailPattern.test(this.user.email)) {
                this.errors.email = 'El correo no es válido';
            } else {
                this.errors.email = '';
            }
        },
        async createUser(){
            if (!this.isValid) {
                this.$toastr.error("Por favor, complete todos los campos correctamente.");
                return;
            }
            this.loading = true;
            try {
                await this.axios.post('/api/users', this.user);
                this.$toastr.success("Usuario creado con éxito");
                this.$router.push({name:"listUsers"});
            } catch (error) {
                console.error("Error al crear el usuario:", error);
                this.$toastr.error("Hubo un error al crear el usuario");
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
