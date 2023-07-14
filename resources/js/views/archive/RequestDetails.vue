<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>detalle de la reserva</span>
            </div>
            <div class="grid-content bg-purple">
                <el-row :gutter="20">
                    <el-form :model="booking" label-width="220px" size="small" :disabled="true">
                        <el-col :span="12">
                            <el-form-item label="codigo">
                                <el-input v-model="booking.idc"></el-input>
                            </el-form-item>
                            <el-form-item label="fecha">
                                <el-input v-model="booking.fecha"></el-input>
                            </el-form-item>
                            <el-form-item label="solicitante">
                                <el-input v-model="booking.des_per"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="12">
                            <el-form-item label="gestion">
                                <el-input v-model="booking.gestion"></el-input>
                            </el-form-item>
                            <el-form-item label="estado">
                                <el-input v-model="booking.estado"></el-input>
                            </el-form-item>
                            <!--
                            <el-form-item label="observaciones">
                                <el-input type="textarea" v-model="booking.observacion"></el-input>
                            </el-form-item>

                        -->
                        </el-col>
                    </el-form>
                </el-row>
            </div>
            <br>


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
                            <!--
                            <el-table-column width="250" label="tipo">
                                <template slot-scope="scope">
                                    <div slot="reference" class="name-wrapper">
                                        <el-tag size="medium" type="danger">{{ scope.row.des_con }}</el-tag>
                                    </div>
                                </template>
                            </el-table-column>
                            -->
                            <el-table-column prop="glosa" width="650" label="descripcion del documento"></el-table-column>
                        </el-table>
                        <br>
                        <el-button type="success" size="small" plain @click="initStoreChangeStateReservation('Prestado')"
                            :disabled="booking.estado !== 'Solicitado'">prestar</el-button>
                        <el-button type="info" size="small" plain @click="initStoreChangeStateReservation('Devuelto')"
                            :disabled="booking.estado !== 'Prestado'">devolver</el-button>
                        <el-button type="danger" size="small" plain
                            @click="initReturnBooking()">Cerrar</el-button>
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
            booking: {}
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
                app.reservations = response.data.bookingDetail;
                app.booking = response.data.booking[0];
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
                if (app.selectedDocuments.length > 0) {
                    let response = await axios.post("/api/storeChangeStateReservation", {
                        state: $stateRequest,
                        selected: this.selectedDocuments
                    });

                }else{
                    alert("debe seleccionar un elemento");

                }
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

        //  *  Route. Iniciar una nueva solicitud para la reserva de documentos
        initReturnBooking() {
            this.$router.push({
                name: "booking",
            });
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
  