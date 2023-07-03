<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>documentos registrados</span>
            </div>
            <el-row :gutter="20">
                <el-col :span="24">
                    <div class="grid-content bg-purple">
                        <span>documentos reservados</span>
                        <br>
                        <el-table v-loading="loading" :data="reservations" style="width: 100%" size="small"
                            @selection-change="handleSelectionChange">
                            <el-table-column type="selection"> </el-table-column>
                            <el-table-column prop="id_doc" label="codigo" width="100" align="center">
                                <template slot-scope="scope">
                                    <el-tag type="success" size="medium">{{ scope.row.id_doc }}</el-tag>
                                </template>
                            </el-table-column>
                            <el-table-column prop="fecha" width="100" label="fecha"></el-table-column>
                            <el-table-column width="250" label="tipo">
                                <template slot-scope="scope">
                                    <div slot="reference" class="name-wrapper">
                                        <el-tag size="medium" type="danger">{{ scope.row.descr }}</el-tag>
                                    </div>
                                </template>
                            </el-table-column>
                            <el-table-column prop="glosa" width="650" label="descripcion del documento"></el-table-column>

                        </el-table>
                        <br>
                        <el-button type="success" size="small" plain @click="initStoreChangeStateReservation('Prestado')">prestar</el-button>
                        <el-button type="info" size="small" plain @click="initStoreChangeStateReservation('Devuelto')">devolver</el-button>
                        <el-button type="danger" size="small" plain @click="initStoreChangeStateReservation('Registrado')">cancelar</el-button>
                    </div>
                </el-col>
            </el-row>
        </el-card>
    </div>
</template>
  
<script>
export default {
    data() {
        return {
            id: null,
            user: this.$store.state.user,
            reservations: [],
            selectedDocuments: [],
            loading: true,
        };
    },
    mounted() {
        let app = this;
        console.log(this.user);
        app.id = app.$route.params.id;
        this.getDataBookingDetails();
    },
    methods: {
        test() { },
        //  * A19. Busca los documentos reservados para la solicitud
        async getDataBookingDetails() {
            this.loading = true;
            let app = this;
            try {
                let response = await axios.post("/api/getDataBookingDetails", {
                    id: app.id,
                });
                app.reservations = Object.values(response.data);
                console.log(response);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
            app.loading = false;
        },
        //  * A23. Realizar la entrega, devolucion o cancelacion de la reserva
        async initStoreChangeStateReservation($stateRequest) {
            let app = this;
            console.log(this.selectedDocuments);
            try {
                let response = await axios.post("/api/storeChangeStateReservation", {
                    state: $stateRequest,
                    selected: this.selectedDocuments
                });
                alert("el estado se cambio correctamente");
                /*
                this.$router.push({
                    name: "typesarchive",
                });*/
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        handleSelectionChange(val) {
            this.selectedDocuments = val;
        },
    },
};
</script>
  
<style scoped>
.el-input .el-select {
    width: 180px;
}
</style>
  