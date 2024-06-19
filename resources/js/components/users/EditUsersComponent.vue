<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h4>Editar Usuario</h4></div>
                <div class="card-body">
                    <form @submit.prevent="editUser">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" class="form-control" v-model="user.nombre" @input="validateNombre">
                                    <p v-if="errors.nombre" class="text-danger">{{ errors.nombre }}</p>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Apellidos</label>
                                    <input type="text" class="form-control" v-model="user.apellidos" @input="validateApellidos">
                                    <p v-if="errors.apellidos" class="text-danger">{{ errors.apellidos }}</p>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Correo</label>
                                    <input type="email" class="form-control" v-model="user.email" @input="validateEmail">
                                    <p v-if="errors.email" class="text-danger">{{ errors.email }}</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" :disabled="!isValid || loading">
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
    name: "editUser",
    components: {
        Spinner,
    },
    data() {
        return {
            user: {
                nombre: "",
                apellidos: "",
                email: "",
            },
            loading: false,
            errors: {
                nombre: '',
                apellidos: '',
                email: ''
            }
        };
    },
    mounted() {
        this.getUserData();
    },
    computed: {
        isValid() {
            return this.user.nombre && this.user.apellidos && this.isValidEmail(this.user.email);
        },
    },
    methods: {
        async getUserData() {
            try {
                const response = await this.axios.get(
                    `/api/users/${this.$route.params.id}`
                );
                const { nombre, apellidos, email } = response.data;
                this.user.nombre = nombre;
                this.user.apellidos = apellidos;
                this.user.email = email;
            } catch (error) {
                console.log(error);
            }
        },
        validateNombre() {
            this.errors.nombre = this.user.nombre ? '' : 'El nombre es requerido';
        },
        validateApellidos() {
            this.errors.apellidos = this.user.apellidos ? '' : 'Los apellidos son requeridos';
        },
        validateEmail() {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            this.errors.email = this.user.email ? (emailPattern.test(this.user.email) ? '' : 'El correo no es válido') : 'El correo es requerido';
        },
        isValidEmail(email) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailPattern.test(email);
        },
        async editUser() {
            if (!this.isValid) {
                this.$toastr.error(
                    "Por favor, complete todos los campos correctamente."
                );
                return;
            }
            
            this.loading = true; // Activamos loading al iniciar la edición

            try {
                await this.axios.put(
                    `/api/users/${this.$route.params.id}`,
                    this.user
                );
                this.$toastr.success("Usuario editado con éxito");
                this.$router.push({ name: "listUsers" });
            } catch (error) {
                console.error("Error al editar el usuario:", error);
                this.$toastr.error("Hubo un error al editar el usuario");
            } finally {
                this.loading = false; // Desactivamos loading al finalizar
            }
        },
    },
};
</script>
