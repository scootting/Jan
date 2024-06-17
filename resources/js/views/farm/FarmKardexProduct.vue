<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>movimiento de ventas diarias</span>
            </div>
            <div class="grid-content bg-purple" style="margin-top: 15px; margin-left: 45px; margin-right: 45px;">

                <el-form :model="dataProduct" label-width="220px" size="small">
                    <el-form-item label="buscar">
                        <el-input placeholder="inserte el codigo del producto" v-model="dataProduct.cod_prd"
                            class="input-with-select">
                            <el-button slot="append" icon="el-icon-search"
                                @click="getProductForSale()"></el-button>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="descripcion" prop="id">
                        <el-input v-model="dataProduct.des_prd" disabled></el-input>
                    </el-form-item>
                    <el-form-item label="unidad" prop="id">
                        <el-input v-model="dataProduct.uni_prd" disabled></el-input>
                    </el-form-item>
                </el-form>
                <el-table v-loading="loading" :data="dataDays" height="450" style="width: 100%"
                    :row-style="tableRowStyle">
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
                    <el-button size="small" type="primary" icon="el-icon-printer"
                        @click="initCustomerSaleDetailDayReport" plain>
                        imprimir el movimiento del producto seleccionado</el-button>
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
            dataProduct: {},
            dataDays: [],
            loading: false,                                       //dia de venta
        };
    },
    mounted() {
    },
    methods: {

        //  * G6. Obtiene un producto a traves de su codigo 
        async getProductForSale() {
            var app = this;
            try {
                let response = await axios.post("/api/getProductForSale", {
                    codigo: app.dataProduct.cod_prd,
                });
                app.dataProduct = { ...app.dataProduct, ...response.data[0] };
                console.log(app.dataProduct);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },


    //  * G22. Imprimir el kardex de un producto.
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
