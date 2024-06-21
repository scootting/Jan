<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>regularizacion detallada de ventas al credito del dia: {{ dataSaleDay.id_dia }}</span>
                <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
                    @click="initAddRegularize">agregar regularizacion</el-button>
            </div>
            <div class="grid-content bg-purple" style="margin-top: 15px; margin-left: 45px; margin-right: 45px;">
                <el-table v-loading="loading" :data="dataClients" height="450" style="width: 100%"
                    :row-style="tableRowStyle">
                    <!--
                    this.client = { ci_per: "", des_per: "", imp_deuda: 0, imp_amortizacion: 0, total_deuda: 0, total_pago: 0 };
                    -->
                    <el-table-column prop="ci_per" label="c.i." width="120"></el-table-column>
                    <el-table-column prop="des_per" label="apellidos y nombres" width="250"></el-table-column>
                    <el-table-column prop="total_deuda" label="deuda" width="150" align="right"></el-table-column>
                    <el-table-column prop="total_pago" label="amortizacion" width="150" align="right"></el-table-column>
                    <el-table-column prop="saldo" label="saldo" width="150" align="right"></el-table-column>
                    <el-table-column align="right-center" label="" width="280">
                        <template slot-scope="scope">
                            <el-button :disabled="dataSaleDay.estado == 'V'" type="primary" plain size="mini"
                                @click="initEditDocumentOfArchive(scope.$index, scope.row)">editar
                                regularizacion</el-button>
                            <el-button :disabled="dataSaleDay.estado == 'V'" type="danger" plain size="mini"
                                @click="DeleteDocumentOfArchive(scope.$index, scope.row)">quitar</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <div style="margin-top: 15px">
                    <el-button size="small" type="primary" icon="el-icon-switch-button"
                        @click="storeCustomerRegularizeDetail" :disabled="dataSaleDay.estado == 'V'" plain>
                        guardar regularizaciones</el-button>
                    <el-button size="small" type="primary" icon="el-icon-switch-button" @click="initCloseSaleDetailDay"
                        :disabled="dataSaleDay.estado == 'V'" plain>
                        cerrar el dia de regularizaciones</el-button>

                    <el-button size="small" type="primary" icon="el-icon-printer" @click="initRegularizeDetailDayReport"
                        plain>
                        imprimir el resumen de ventas de regularizaciones del dia</el-button>
                </div>
            </div>
        </el-card>

        <!-- Form Add Document to Archive-->
        <el-dialog title="agregar regularizacion" :visible.sync="dialogFormVisible">
            <el-form :model="client" label-width="220px" size="small">
                <el-form-item label="buscar">
                    <el-input placeholder="INSERTE UNA DESCRIPCION" v-model="writtenTextParameter"
                        class="input-with-select">
                        <el-button slot="append" icon="el-icon-search" @click="getClientsForRegularize()"></el-button>
                    </el-input>
                </el-form-item>
                <el-form-item label="Numero del documento">
                    <el-input v-model="client.ci_per" autocomplete="off" disabled></el-input>
                </el-form-item>
                <el-form-item label="apellidos y nombres">
                    <el-input v-model="client.des_per" autocomplete="off" disabled></el-input>
                </el-form-item>
                <el-form-item label="deuda">
                    <el-input v-model="client.total_deuda" autocomplete="off" disabled></el-input>
                </el-form-item>
                <el-form-item label="amortizacion">
                    <el-input-number v-model="client.total_pago" controls-position="right" :min="1"
                        :max="parseFloat(client.total_deuda)" :step="0.5"></el-input-number>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" size="small" plain @click="AddClientToList">Agregar regularizacion</el-button>
                <el-button type="danger" size="small" plain @click="dialogFormVisible = false">Cancelar</el-button>
            </span>
        </el-dialog>
        <!-- Form Add Document to Archive-->
    </div>
</template>

<script>

export default {
    data() {
        return {
            user: this.$store.state.user,
            id: this.$route.params.id,
            writtenTextParameter: '',
            dataSaleDay: {},
            client: {},
            dataClients: [],
            stateStore: '',
            dialogFormVisible: false,
            loading: false,                                       //dia de venta
        };
    },
    mounted() {
        this.loading = true;
        this.getFarmSaleDayById();
        this.getCurrentRegularizeClientById();
        this.loading = false;
    },
    methods: {
        //  * G5. Obtiene un dia de venta de los productos de la granja
        async getFarmSaleDayById() {
            var app = this;
            let response = await axios.post("/api/getFarmSaleDayById", {
                id: app.id,
            });
            app.dataSaleDay = response.data[0];
            console.log(app.data);
        },

        //  * G15. Buscar a los deudores
        async getClientsForRegularize() {
            var app = this;
            try {
                let response = await axios.post("/api/getClientsForRegularize", {
                    description: app.writtenTextParameter,
                });
                console.log(response.data[0]);
                app.client = response.data[0];
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        //  * G19. Obtiene los clientes registrados
        async getCurrentRegularizeClientById() {
            var app = this;
            console.log(app.dataSaleDay.tip_tra);
            let response = await axios.post("/api/getCurrentRegularizeClientById", {
                id_dia: app.id,
                usr_cre: app.user.usuario,
                tip_tra: app.dataSaleDay.tip_tra
            });
            this.dataClients = response.data;
        },



        //  * Iniciar el cuadro de dialogo para insertar un nuevo deudor
        initAddRegularize() {
            this.stateStore = "añadir";
            this.dialogFormVisible = true;
        },

        //  * Inicia la edicion de un documento
        initEditDocumentOfArchive(idx, row) {
            this.client = row;
            this.stateStore = "editar";
            this.dialogFormVisible = true;
        },
        //  * Quita un documento ya existente
        DeleteDocumentOfArchive(idx, row) {
            this.dataClients.splice(idx, 1);
            console.log(this.dataClients);
            //this.OnUpdateIndex();
        },

        //  * Guarda los cambios de un nuevo documento sea nuevo o uno ya existente
        AddClientToList() {
            this.dialogFormVisible = false;
            if (this.stateStore == "añadir") {
                let variable = this.client;
                variable.saldo = variable.total_deuda - variable.total_pago;
                this.dataClients.push(variable);
            }
            else {

            }
            app.writtenTextParameter = '';
            this.client = { ci_per: "", des_per: "", imp_deuda: 0, imp_amortizacion: 0, total_deuda: 0, total_pago: 0, saldo: 0 };
            console.log(this.dataClients);
            //this.OnUpdateIndex();
        },

        //  * G16. Agregar detalle de las regularizaciones del cliente de los productos de la granja
        async storeCustomerRegularizeDetail() {
            var app = this;
            try {
                let response = await axios.post("/api/storeCustomerRegularizeDetail", {
                    general: app.dataSaleDay,
                    clientes: app.dataClients,
                });
                alert("la regularizacion se ha realizada correctamente");
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        //  * G24. Imprimir el reporte de regularizaciones del dia.
        initRegularizeDetailDayReport() {
            let app = this;
            console.log(app.dataSaleDay);
            axios({
                url: "/api/regularizeDetailDayReport/",
                params: {
                    id: app.dataSaleDay.id,
                    gestion: app.dataSaleDay.gestion,
                },
                method: "GET",
                responseType: "arraybuffer",
            }).then((response) => {
                let blob = new Blob([response.data], {
                    type: "application/pdf",
                });
                let link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                let url = window.URL.createObjectURL(blob);
                window.open(url);
            });
        },

        //  * G11. Cerrar el reporte de ventas del dia.
        async initCloseSaleDetailDay() {
            var app = this;
            try {
                let response = await axios.post("/api/setCloseSaleDetailDay", {
                    id: app.id,
                    gestion: app.dataSaleDay.gestion,
                });
                console.log(response);
                alert("acaba de cerrar el dia de regularizacion, puede imprimir el resumen");
                app.$router.push({
                    name: "farmregularizedays"
                });

            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        tableRowStyle({ row, rowIndex }) {
            if (row.tip_tra == 9)
                return { 'background': '#FFB9B9' }
        },
    }
};
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.el-row {
    margin-bottom: 20px;
}

.el-col {
    border-radius: 4px;
}

.bg-purple-dark {
    background: #99a9bf;
}

.bg-purple {
    background: #d3dce6;
}

.bg-purple-light {
    background: #e5e9f2;
}

.grid-content {
    border-radius: 4px;
    padding: 15px;
    min-height: 36px;
}

.row-bg {
    padding: 10px 0;
    background-color: #f9fafc;
}

.el-table .delete-row {
    background: red !important;
}
</style>
