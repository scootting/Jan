<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>movimiento de ventas diarias</span>
            </div>
            <div class="grid-content bg-purple" style="margin-top: 15px; margin-left: 45px; margin-right: 45px;">

                <el-form :model="range" label-width="220px" size="small">
                    <el-form-item label="rango inicial">
                        <el-date-picker type="date" v-model="range.initial" placeholder="seleccione una fecha"
                            style="width: 100%" format="yyyy/MM/dd" value-format="yyyy-MM-dd"></el-date-picker>
                    </el-form-item>
                    <el-form-item label="rango final">
                        <el-date-picker type="date" v-model="range.final" placeholder="seleccione una fecha"
                            style="width: 100%" format="yyyy/MM/dd" value-format="yyyy-MM-dd"></el-date-picker>
                    </el-form-item>
                    <el-form-item>
                        <el-button size="small" type="primary" @click.prevent="getTransactionsSaleByDays"
                            icon="el-icon-search" plain>Buscar movimientos</el-button>
                    </el-form-item>
                </el-form>

                <el-table v-loading="loading" :data="dataDays" height="450" style="width: 100%" fixed>
                    <el-table-column prop="fec_tra" label="fecha" :min-width="100">
                        <template slot-scope="scope">
                            <el-tag type="info">{{ scope.row.fec_tra }}</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column prop="id_dia" label="dia" :min-width="100">
                        <template slot-scope="scope">
                            <el-tag type="primary">{{ scope.row.id_dia }}</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column prop="glosa" label="glosa" :min-width="480"></el-table-column>
                    <el-table-column align="right" :min-width="320" fixed="right">
                        <template slot-scope="scope">
                            <el-button :disabled="scope.row.estado == 'V'" type="primary" plain size="mini"
                                @click="initSaleDetailReport(scope.$index, scope.row)">ir al dia de
                                ventas</el-button>
                            <el-button :disabled="scope.row.estado == 'V'" type="primary" plain size="mini"
                                @click="initCloseSaleDetailDay(scope.$index, scope.row)">cerrar el dia de
                                ventas</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <div style="margin-top: 15px">
                    <el-button size="small" type="primary" icon="el-icon-printer"
                        @click="initCustomerResumeSaleDetailDaysReport" plain>
                        imprimir el resumen de movimientos diarios</el-button>
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
            writtenTextParameter: '',
            dataSaleDay: {},
            range: { initial: '', final: '' },
            dataDays: [],
            loading: false,                                       //dia de venta
        };
    },
    mounted() {
    },
    methods: {
        //  * G20. Lista de dias en el rango
        async getTransactionsSaleByDays() {
            var app = this;
            console.log(app.dataSaleDay.tip_tra);
            let response = await axios.post("/api/getTransactionsSaleByDays", {
                range: app.range,
            });
            this.dataDays = response.data;
        },

        //  * G21. Imprimir el reporte de movimientos de las ventas de los dias.
        initCustomerResumeSaleDetailDaysReport() {
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
        async initCloseSaleDetailDay(idx, row) {
            var app = this;
            let id_dia = row.id;
            let gestion = row.gestion;
            try {
                let response = await axios.post("/api/setCloseSaleDetailDay", {
                    id: id_dia,
                    gestion: gestion,
                });
                console.log(response);
                alert("acaba de cerrar el dia de ventas, puede imprimir el resumen");
                app.getTransactionsSaleByDays();
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        initSaleDetailReport(index, row) {
            console.log(index, row);
            let id_dia = row.id;
            this.$router.push({
                name: "saledetailreport",
                params: {
                    id: id_dia,
                },
            });
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
</style>
