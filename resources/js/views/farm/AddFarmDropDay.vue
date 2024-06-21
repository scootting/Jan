<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>baja detallada de productos</span>
                <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
            </div>
            <div>
                <el-row :gutter="20">
                    <el-col :span="10">
                        <div class="grid-content bg-purple">
                            <p>datos generales de la baja</p>
                            <el-form ref="form" :model="dataSaleDay" label-width="120px" size="small">
                                <el-form-item label="dia" prop="id">
                                    <el-input v-model="dataSaleDay.id_dia" disabled></el-input>
                                </el-form-item>
                                <el-form-item label="fecha" prop="fec_tra">
                                    <el-input v-model="dataSaleDay.fec_tra" disabled></el-input>
                                </el-form-item>
                                <el-form-item label="no. de carnet" prop="ci_per">
                                    <el-input v-model="client.id" disabled></el-input>
                                </el-form-item>
                                <el-form-item label="operador" prop="details">
                                    <el-input v-model="client.details" disabled></el-input>
                                </el-form-item>
                            </el-form>
                        </div>
                        <p></p>
                    </el-col>
                    <el-col :span="14">
                        <div class="grid-content bg-purple">
                            <p>productos para la baja</p>
                            <el-table :data="products" style="width: 100%" size="small" fixed>
                                <el-table-column prop="des_prd" label="detalle"></el-table-column>
                                <el-table-column prop="uni_prd" label="medida"></el-table-column>
                                <el-table-column prop="pre_uni" label="precio unit." align="right"></el-table-column>
                                <el-table-column prop="can" label="cantidad" align="right"></el-table-column>
                                <el-table-column align="right" :min-width="100" fixed="right">
                                    <template slot-scope="scope">
                                        <el-button @click="initRemoveProduct(scope.$index, scope.row)" type="primary"
                                            plain size="small" :disabled="dataSaleDay.estado == 'V'">Quitar</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <p></p>
                            <el-button :disabled="dataSaleDay.estado == 'V'" @click="dialogFormVisible = true"
                                type="primary" size="small" plain>Agregar nuevo producto
                            </el-button>
                        </div>
                    </el-col>
                </el-row>
                <el-button size="small" type="primary" @click="StoreCustomerSaleDetail()" icon="el-icon-notebook-1"
                    plain :disabled="dataSaleDay.estado == 'V'">Guardar cambios
                </el-button>
                <el-button size="small" type="primary" icon="el-icon-switch-button" @click="initCloseSaleDetailDay"
                    :disabled="dataSaleDay.estado == 'V'" plain>
                    cerrar el dia de baja de productos</el-button>
                <el-button size="small" type="primary" icon="el-icon-printer" @click="initCustomerIncomeDetailDayReport"
                    plain>
                    imprimir el resumen de baja del dia</el-button>
            </div>
            <!-- componente para agregar productos -->
            <el-dialog title="agregar producto" :visible.sync="dialogFormVisible">
                <el-form :model="dataProduct" label-width="150px" size="small">
                    <el-form-item label="codigo" prop="id">
                        <el-input placeholder="ingrese el codigo del producto" v-model="dataProduct.cod_prd"
                            class="input-with-select">
                            <el-button slot="append" icon="el-icon-search" @click="getProductForSale">BUSCAR</el-button>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="descripcion" prop="id">
                        <el-input v-model="dataProduct.des_prd" disabled></el-input>
                    </el-form-item>
                    <el-form-item label="unidad" prop="id">
                        <el-input v-model="dataProduct.uni_prd" disabled></el-input>
                    </el-form-item>
                    <el-form-item size="small" label="precio unitario" prop="materno">
                        <el-input size="small" v-model="dataProduct.pre_uni"></el-input>
                    </el-form-item>
                    <el-form-item label="cantidad">
                        <el-input-number v-model="dataProduct.can" controls-position="right" :min="0.5"
                            :step="0.5"></el-input-number>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button type="primary" size="small" plain @click="initStoreNewProduct">agregar
                        producto</el-button>
                    <el-button type="danger" size="small" plain @click="dialogFormVisible = false">cerrar
                        ventana</el-button>
                </span>
            </el-dialog>
            <!-- componente para agregar productos -->
        </el-card>
    </div>
</template>

<script>
import information from "../components/Information.vue";

export default {
    components: {
        information
    },
    data() {
        return {
            user: this.$store.state.user,
            id: this.$route.params.id,
            isVisible: false, //componente campo visible
            tag: "persona", //componente que informacion desea traer
            dialogFormVisible: false, //hace visible el formulario de cosas adeudadas
            dataProduct: { can: 1 }, //datos del producto en venta
            products: [], //lista de productos adquiridos
            dataSaleDay: {}, //dia de venta
            voucher: "000001",
            client: { id: "", details: "" }, //cliente
        };
    },
    mounted() {
        this.client.id = this.user.nodip;
        this.client.details = this.user.descripcion;
        this.getFarmSaleDayById();
        this.getCurrentProductsById();
        console.log(this.client);
    },
    methods: {
        //  * S2. Guardar la informacion de un nuevo documento de deuda.
        async StoreCustomerSaleDetail() {
            var app = this;
            try {
                let response = await axios.post(
                    "/api/storeCustomerSaleDetail",
                    {
                        general: app.dataSaleDay,
                        cliente: app.client,
                        productos: app.products,
                        voucher: app.voucher
                    }
                );
                alert("el ingreso se ha realizada correctamente");
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true
                });
            }
        },

        //  * G5. Obtiene un dia de venta de los productos de la granja
        async getFarmSaleDayById() {
            var app = this;
            let response = await axios.post("/api/getFarmSaleDayById", {
                id: app.id
            });
            app.dataSaleDay = response.data[0];
            console.log(app.dataSaleDay);
        },

        //  * G6. Obtiene un producto a traves de su codigo
        async getProductForSale() {
            var app = this;
            try {
                let response = await axios.post("/api/getProductForSale", {
                    codigo: app.dataProduct.cod_prd
                });
                app.dataProduct = { ...app.dataProduct, ...response.data[0] };
                console.log(app.dataProduct);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true
                });
            }
        },

        //  * G14. Obtiene la cantidad de productos registrados
        async getCurrentProductsById() {
            var app = this;
            console.log(app.dataSaleDay.tip_tra);
            let response = await axios.post("/api/getCurrentProductsById", {
                id_dia: app.id,
                usr_cre: app.user.usuario,
                tip_tra: app.dataSaleDay.tip_tra
            });
            this.products = response.data;
        },

        //  * G17. Imprimir el reporte de ingresos del dia.
        initCustomerIncomeDetailDayReport() {
            let app = this;
            axios({
                url: "/api/incomeDetailDayReport/",
                params: {
                    id: app.dataSaleDay.id,
                    tipo_transaccion: app.dataSaleDay.tip_tra,
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
                alert("acaba de cerrar el dia de ingresos, puede imprimir el resumen");
                app.$router.push({
                    name: "farmdropdays"
                });

            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        initNewCustomerSale() {
            this.$router.push({
                name: "farmdropdays"
            });
        },

        updateIsVisible(visible, data) {
            this.isVisible = visible;
            this.client = data;
            console.log(this.isVisible + " " + this.data);
        },

        /* quita los productos de la lista de productos adquiridos */
        initRemoveProduct(index, row) {
            this.dialogFormVisible = false;
            this.products.splice(index, 1);
        },

        initStoreNewProduct() {
            let variable = this.dataProduct;
            console.log(variable);
            console.log(this.products);
            (this.dataProduct = { can: 1 }), this.products.push(variable);
        }
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
