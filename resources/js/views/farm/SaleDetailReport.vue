<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>venta detallada de productos del dia: {{ dataSaleDay.id_dia }}</span>
            </div>
            <div class="grid-content bg-purple" style="margin-top: 15px">
                <el-table v-loading="loading" :data="data" height="450" style="width: 100%" :row-style="tableRowStyle">
                    <el-table-column prop="nro_com" label="no." width="100">
                        <template slot-scope="scope">
                            <el-tag>{{ scope.row.nro_com }}</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column prop="ci_per" label="c.i." width="100"></el-table-column>
                    <el-table-column prop="des_per" label="descripcion" width="250"></el-table-column>
                    <el-table-column prop="des_prd" label="producto" width="150"></el-table-column>
                    <el-table-column prop="can_pro" label="cantidad" width="85" align="right"></el-table-column>
                    <el-table-column prop="uni_pro" label="p.u." width="100" align="right"></el-table-column>
                    <el-table-column prop="imp_pro" label="importe" width="100" align="right"></el-table-column>
                    <el-table-column align="right-center" fixed="right" width="200">
                        <template slot-scope="scope">
                            <el-button :disabled="scope.row.tip_tra == 9 || dataSaleDay.estado == 'V'"
                                @click="initCancelTransaction(scope.$index, scope.row)" size="mini" type="danger"
                                >anular
                            </el-button>
                            <!--
                            <el-button :disabled="scope.row.tip_tra == 9 || dataSaleDay.estado == 'V'"
                                @click="initCustomerSaleDetailReport(scope.$index, scope.row)" size="mini"
                                type="success" plain>reinprimir
                            </el-button>
                            -->
                            <el-button
                                @click="initCustomerSaleDetailReport(scope.$index, scope.row)" size="mini"
                                type="success">reinprimir
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <div style="margin-top: 15px">
                    <el-button size="small" type="primary" icon="el-icon-switch-button" @click="initCloseSaleDetailDay"
                        :disabled="dataSaleDay.estado == 'V'" plain>
                        cerrar el dia de ventas</el-button>
                    <el-button size="small" type="primary" icon="el-icon-printer"
                        @click="initCustomerSaleDetailDayReport" plain>
                        imprimir el resumen de ventas del dia</el-button>
                </div>
            </div>
        </el-card>
    </div>
</template>

<script>

export default {
    data() {
        return {
            user: this.$store.state.user,
            id: this.$route.params.id,
            dataSaleDay: {},
            data: [],
            loading: false,                                       //dia de venta
        };
    },
    mounted() {
        this.loading = true;
        this.getFarmSaleDayById();
        this.getFarmSaleDetailById();
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

        //  * G9. obtener todas las ventas correspondientes a un dia en especifico
        async getFarmSaleDetailById() {
            var app = this;
            try {
                let response = await axios.post("/api/getFarmSaleDetailById", {
                    id: app.id,
                });
                console.log(response);
                app.data = Object.values(response.data);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        //  * G12. Anular la transaccion de una venta erronea.
        async initCancelTransaction(index, row) {
            let app = this;
            let transaccion = row;
            console.log(row);
            try {
                let response = await axios.post("/api/updateCancelTransaction", {
                    id: transaccion.id_tran,
                    voucher: transaccion.nro_com,
                    tipo: transaccion.tip_tra,
                    gestion: transaccion.gestion,
                });
                console.log(response);
                alert("se anulo correctamente la transaccion");
                app.getFarmSaleDetailById();
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        //  * G8. Imprimir el reporte de la venta actual.
        initCustomerSaleDetailReport(index, row) {
            let transaccion = row;
            axios({
                url: "/api/customerSaleDetailReport/",
                params: {
                    voucher: transaccion.nro_com,
                    tipo: transaccion.tip_tra,
                    gestion: transaccion.gestion,
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

        //  * G10. Imprimir el reporte de ventas del dia.
        initCustomerSaleDetailDayReport() {
            let app = this;
            console.log(app.dataSaleDay);
            axios({
                url: "/api/customerSaleDetailDayReport/",
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
                alert("acaba de cerrar el dia de ventas, puede imprimir el resumen");
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
