<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h4>Carga masiva</h4></div>
                <div class="card-body">
                    <form @submit.prevent="importExcel">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <input 
                                        type="file" 
                                        class="form-control-file" 
                                        ref="fileInput" 
                                        accept=".xlsx, .xls"
                                        @change="validateFile"
                                    >
                                    <p>usar archivos .xlsx o .xls</p>
                                    <p v-if="errorMessage" class="text-danger">{{ errorMessage }}</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" :disabled="!isValid">Subir</button>
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
    name: "importExcel",
    data() {
        return {
            loading: false,
            errorMessage: '',
            isValid: false,
        };
    },
    methods: {
        validateFile() {
            const file = this.$refs.fileInput.files[0];
            this.errorMessage = '';
            this.isValid = false;

            if (file) {
                const fileType = file.type;
                const fileSize = file.size;

                const allowedTypes = [
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-excel'
                ];

                const maxSize = 5 * 1024 * 1024; // 5 MB

                if (!allowedTypes.includes(fileType)) {
                    this.errorMessage = 'Tipo de archivo no permitido. Solo se permiten archivos .xlsx o .xls';
                    return;
                }

                if (fileSize > maxSize) {
                    this.errorMessage = 'El archivo es demasiado grande. El tamaño máximo permitido es 5 MB.';
                    return;
                }

                this.isValid = true;
            }
        },
        async importExcel() {
            if (!this.isValid) {
                this.$toastr.error("El archivo no es válido.");
                return;
            }

            this.loading = true;
            const formData = new FormData();
            formData.append("file", this.$refs.fileInput.files[0]);

            try {
                await this.axios.post('/api/import/excel', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                this.$toastr.success("Carga masiva exitosa");
                this.$router.push({ name: "listUsers" });
            } catch (error) {
                console.error("Error al cargar archivo:", error);
                this.$toastr.error("Hubo un error al cargar el archivo");
            } finally {
                this.loading = false;
            }
        }
    }
};
</script>
