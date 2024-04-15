<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>Pagos Pasarela de Pagos</span>
                <el-button-group style="text-align: right; float: right">
                    <el-button type="primary" plain icon="el-icon-arrow-left" @click="dialogFormVisible = true"
                        size="small">Busqueda</el-button>
                    <el-button type="primary" plain icon="el-icon-arrow-left" @click="initReportGatewayPayments"
                        size="small">Descargar</el-button>
                </el-button-group>

                <!--
                <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-carter"
                    @click="dialogFormVisible = true">Busqueda</el-button>
                <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-carter"
                    @click="dialogFormVisible = true">Descargar</el-button>
          -->
            </div>
            <div>
                <el-table v-loading="loading" :data="payments" style="width: 100%">
                    <el-table-column prop="codigo_transaccion" label="id" :min-width="120">
                        <template slot-scope="scope">
                            <el-tag size="medium" type="primary">{{
                        scope.row.codigo_transaccion
                    }}</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column prop="fecha_pago" label="fecha" :min-width="120">
                        <template slot-scope="scope">
                            <el-tag size="medium" type="info">{{ scope.row.fecha_pago }}</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column prop="nro_documento" label="ci" :min-width="150"></el-table-column>
                    <el-table-column prop="nombre_cliente" label="cliente" :min-width="250"></el-table-column>
                    <el-table-column prop="tipo_pago" label="tipo" :min-width="150">
                        <template slot-scope="scope">
                            <div v-if="scope.row.tipo_pago === 'PPTE'">
                                <el-tag size="medium" type="success" effect="dark">{{
                        scope.row.tipo_pago
                    }}</el-tag>
                            </div>
                            <div v-if="scope.row.tipo_pago === 'QR'">
                                <el-tag size="medium" type="" effect="dark">{{
                        scope.row.tipo_pago
                    }}</el-tag>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="monto" label="importe" :min-width="150" align="right"></el-table-column>
                    <el-table-column align="right" :min-width="150" fixed="right">
                        <template slot-scope="scope">
                            <el-button @click="initReportOnlineSales(scope.$index, scope.row)" size="mini"
                                type="primary" plain>ver
                                detalle</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
                    :current-page="pagination.current_page" :total="pagination.total"
                    @current-change="getGatewayPayments"></el-pagination>
            </div>
        </el-card>

        <el-dialog title="Shipping address" :visible.sync="dialogFormVisible">
            <el-form :model="filtered">

                <el-form-item size="small" label="fecha inicial" prop="fecha_inicial">
                    <el-date-picker size="small" type="date" placeholder="seleccione una fecha"
                        v-model="filtered.fecha_inicial" style="width: 100%"></el-date-picker>
                </el-form-item>
                <el-form-item size="small" label="fecha final" prop="fecha_final">
                    <el-date-picker size="small" type="date" placeholder="seleccione una fecha"
                        v-model="filtered.fecha_final" style="width: 100%"></el-date-picker>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false" size="small">Cancelar</el-button>
                <el-button type="primary" size="small" @click="getFilterPayments">Confirmar</el-button>
            </span>
        </el-dialog>

    </div>
</template>

<script>
export default {
    data() {
        return {
            user: this.$store.state.user,
            dialogFormVisible: false,
            payments: [],
            filtered: {},
            pagination: {
                page: 1,
            },
            writtenTextParameter: "",
            loading: true,
        };
    },
    mounted() {
        let app = this;
        this.getGatewayPayments(app.pagination.page);
    },
    methods: {
        test() {
            alert("bienvenido al modulo");
        },

        //  * T40. Obtienes los dias de venta en linea para manhattan, nottingham, vancouber
        async getGatewayPayments(page) {
            let app = this;
            try {
                let response = await axios.post("/api/getGatewayPayments", {
                    description: "",
                    fecha_inicial: app.filtered.fecha_inicial,
                    fecha_final: app.filtered.fecha_final,
                    page: page,
                });
                app.loading = false;
                app.payments = Object.values(response.data.data);
                app.pagination = response.data;
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        getFilterPayments() {
            this.dialogFormVisible = false;
            this.getGatewayPayments(1);
        },

        //  * T41. Imprime el detalle de ventas en linea para manhattan, nottingham, vancouber
        initReportGatewayPayments() {
            var app = this;
            axios({
                url: "/api/getReportGatewayPayments",
                params: {
                    description: "",
                    fecha_inicial: app.filtered.fecha_inicial,
                    fecha_final: app.filtered.fecha_final,
                },
                method: "GET",
                responseType: "arraybuffer",
            }).then((response) => {
                const link = document.createElement('a');
                link.href = window.URL.createObjectURL(
                    new Blob([response.data])
                );
                link.setAttribute('download', 'ppe.xlsx');
                document.body.appendChild(link);
                link.click();
            });
        },
        /*
        initSaleInLineDetail(index, row) {
            let id = row.id_dia;
            this.$router.push({
                name: "saleinlinedetail",
                params: {
                    id: id,
                },
            });
        },*/
    },
};
</script>

<style scoped>
.el-input .el-select {
    width: 180px;
}
</style>