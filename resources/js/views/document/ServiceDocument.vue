<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>Documentos</span>
                <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
                    @click="initAddServicesDocument">nuevo comprobante de pago</el-button>
            </div>
            <div>
                <el-table v-loading="loading" :data="dataDocuments" style="width: 100%" size="medium">
                    <el-table-column label="fecha" prop="fecha" :min-width="100">
                        <template slot-scope="scope">
                            <el-tag type="info">{{ scope.row.fecha }}</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column label="importe" prop="tipo_documento" :min-width="100">
                        <template slot-scope="scope">
                            <el-tag type="primary">{{ scope.row.tipo_document }}</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column align="right" label="importe" prop="importe" :min-width="200"></el-table-column>
                    <el-table-column align="right" label="numero" prop="nro_cliente" :min-width="200"></el-table-column>
                    <el-table-column label="nombres y apellidos" prop="nombre_cliente"
                        :min-width="200"></el-table-column>
                    <el-table-column align="right" :min-width="320">
                        <template slot-scope="scope">
                            <el-button :disabled="dataSaleDays[scope.$index].estado == 'V'"
                                @click="initServiceDocumentDetail(scope.$index, scope.row)" type="warning" size="small"
                                plain>Mostrar</el-button>
                            <el-button @click="initServiceDocumentReport(scope.$index, scope.row)" type="primary"
                                size="small" plain>imprimir comprobante</el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </el-card>
    </div>
</template>

<script>

export default {
    data() {
        return {
            user: this.$store.state.user,
            dataDocuments: [],
            pagination: {
                page: 1,
            },
            loading: true,
        };
    },
    mounted() {
        this.getFarmSaleDays(this.pagination.page);
    },
    methods: {
        test() {
            alert("bienvenido al modulo");
        },

        //  * D12. Obtiene la lista de comprobantes realizados por usuario 
        async getServiceDocuments(page) {
            this.loading = true;
            let app = this;
            try {
                let response = await axios.post("/api/getServiceDocuments", {
                    gestion: app.user.gestion,
                    usuario: app.user.usuario,
                    page: page,
                });
                app.dataDocuments = Object.values(response.data.data);
                app.pagination = response.data;
                console.log(app.dataDocuments);
                app.loading = false;
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        initAddServicesDocument() {
            this.$router.push({
                name: "addservicedocument",
            });
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>