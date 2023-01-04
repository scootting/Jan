<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>venta detallada de productos</span>
                <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
            </div>
            <div>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <div class="grid-content bg-purple">
                            <p>datos generales de la venta</p>
                            <el-form ref="form" :model="dataSaleDay" label-width="120px" size="small">
                                <el-form-item label="dia" prop="id">
                                    <el-input v-model="dataSaleDay.id_dia"></el-input>
                                </el-form-item>
                                <el-form-item label="comprobante" prop="nro_com">
                                    <el-input v-model="voucher.numero"></el-input>
                                </el-form-item>
                                <el-form-item label="fecha" prop="id">
                                    <el-input v-model="dataSaleDay.fec_tra"></el-input>
                                </el-form-item>
                                <el-form-item label="identidad" prop="id">
                                    <el-input placeholder="ingrese carnet de identidad" v-model="client.id"
                                        class="input-with-select">
                                        <el-button slot="append" icon="el-icon-search"
                                            @click="initSearchClient">BUSCAR</el-button>
                                    </el-input>
                                </el-form-item>
                                <el-form-item label="descripcion" prop="details">
                                    <el-input v-model="client.details"></el-input>
                                </el-form-item>
                            </el-form>
                        </div>
                        <p></p>
                    </el-col>
                    <el-col :span="12">
                        <div class="grid-content bg-purple">
                            <p>productos adquiridos</p>
                            <el-table :data="products" style="width: 100%" size="small">
                                <el-table-column prop="tipo" label="pago"></el-table-column>
                                <el-table-column prop="des_prd" label="detalle"></el-table-column>
                                <el-table-column prop="uni_prd" label="medida"></el-table-column>
                                <el-table-column prop="pre_uni" label="precio unitario"></el-table-column>
                                <el-table-column prop="can" label="cantidad"></el-table-column>
                                <el-table-column align="right">
                                    <template slot-scope="scope">
                                        <el-button @click="initRemoveProduct(scope.$index, scope.row)" type="primary"
                                            plain size="small">Quitar</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <p></p>
                            <el-button @click="dialogFormVisible = true" type="primary" size="small" plain>Agregar
                            </el-button>
                        </div>
                    </el-col>
                </el-row>
                <el-button @click="StoreCustomerSaleDetail()" type="success" size="small" plain>Guardar
                </el-button>
                <el-button @click="test()" type="warning" size="small" plain>Imprimir
                </el-button>
            </div>
            <information :visible="isVisible" :tag='tag' @update-visible="updateIsVisible"></information>
            <!-- componente para agregar productos -->
            <el-dialog title="agregar producto" :visible.sync="dialogFormVisible">
                <el-form :model="dataProduct" label-width="150px" size="small">
                    <el-form-item label="deuda">
                        <el-radio-group v-model="dataProduct.tipo" size="small">
                            <el-radio-button label="1">Efectivo</el-radio-button>
                            <el-radio-button label="14">Credito</el-radio-button>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item label="identidad" prop="id">
                        <el-input placeholder="ingrese el codigo del producto" v-model="id_product"
                            class="input-with-select">
                            <el-button slot="append" icon="el-icon-search" @click="getProductForSale">BUSCAR</el-button>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="descripcion" prop="id">
                        <el-input v-model="dataProduct.des_prd"></el-input>
                    </el-form-item>
                    <el-form-item label="unidad" prop="id">
                        <el-input v-model="dataProduct.uni_prd"></el-input>
                    </el-form-item>
                    <el-form-item label="cantidad">
                        <el-input-number v-model="dataProduct.can" controls-position="right" :min="1"></el-input-number>
                    </el-form-item>
                    <el-form-item label="precio unitario">
                        <el-input-number v-model="dataProduct.pre_uni" controls-position="right" :min="1"></el-input-number>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button type="primary" size="small" plain @click="initStoreNewProduct">agregar</el-button>
                    <el-button type="danger" size="small" plain @click="dialogFormVisible = false">cerrar</el-button>
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
        information,
    },
    data() {
        return {
            user: this.$store.state.user,
            id: this.$route.params.id,
            isVisible: false,           //componente campo visible
            tag: 'persona',             //componente que informacion desea traer
            dialogFormVisible: false,   //hace visible el formulario de cosas adeudadas
            dataProduct: {
                tipo: "1", cod:'', can: 1, pre_uni: 0,
            },            //producto ofertado
            id_product: '',              //codigo del producto
            products: [],               //lista de productos adquiridos
            dataSaleDay: {},            //dia de ventas
            client: {},                //responsable (director de carrera, jefe de division)
            voucher: { numero: '' },
            //product: { tipo: "1", cod:'', can: 1, uni: 0, imp: 0 }, //deuda
        };
    },
    mounted() {
        console.log(this.user);
        this.getFarmSaleDayById();
    },
    methods: {
        //  * S2. Guardar la informacion de un nuevo documento de deuda.
        async StoreCustomerSaleDetail() {
            var app = this;
            try {
                let response = await axios.post("/api/StoreCustomerSaleDetail", {
                    usuario: app.user,
                    documento: app.initStoreNewProduct,
                    marker: "registrar",
                });
                alert("se ha creado el registro de la persona");
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        //  * G5. Obtiene un dia de venta de los productos de la granja
        async getFarmSaleDayById() {
            var app = this;
            let response = await axios.post("/api/getFarmSaleDayById", {
                id: app.id,
            });
            app.dataSaleDay = response.data[0];
            console.log(app.dataSaleDay);
        },

        async getProductForSale() {
            var app = this;
            try {
                let response = await axios.post("/api/getProductForSale", {
                    codigo: app.id_product,
                });
                app.dataProduct = response.data[0];
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },


        initSearchClient() {
            this.isVisible = true;
            this.tag = 'persona';
        },

        updateIsVisible(visible, data) {
            this.isVisible = visible;
            this.client = data;
            console.log(this.isVisible + " " + this.data);
        },

        /* quita la cosa que se adeuda */
        initRemoveProduct(index, row) {
            this.dialogFormVisible = false;
            this.products.splice(index, 1);
        },

        initStoreNewProduct() {
            let variable = this.dataProduct;
            console.log(variable);
            this.dataProduct = { tipo: "1", cod:'', can: 1, pre_uni: 0, imp: 0 },
            this.products.push(variable);
        }

    },
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
  