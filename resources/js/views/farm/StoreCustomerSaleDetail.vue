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
                            <el-form ref="form" :model="document" label-width="120px" size="small">
                                <el-form-item label="dia" prop="id">
                                    <el-input v-model="document.id"></el-input>
                                </el-form-item>
                                <el-form-item label="comprobante" prop="nro_com">
                                    <el-input v-model="document.voucher"></el-input>
                                </el-form-item>
                                <el-form-item label="fecha" prop="id">
                                    <el-input v-model="document.fecha"></el-input>
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
                                <el-table-column prop="tip_tra" label="pago"></el-table-column>
                                <el-table-column prop="desc" label="detalle"></el-table-column>
                                <el-table-column prop="can" label="cantidad"></el-table-column>
                                <el-table-column prop="uni" label="precio"></el-table-column>
                                <el-table-column prop="imp" label="importe"></el-table-column>
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
                <el-form :model="product" label-width="150px" size="small">
                    <el-form-item label="deuda">
                        <el-radio-group v-model="product.tipo" size="small">
                            <el-radio-button label="1">Efectivo</el-radio-button>
                            <el-radio-button label="14">Credito</el-radio-button>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item label="cantidad">
                        <el-input-number v-model="product.can" controls-position="right" :min="1"></el-input-number>
                    </el-form-item>
                    <el-form-item label="precio unitario">
                        <el-input-number v-model="product.uni" controls-position="right" :min="1"></el-input-number>
                    </el-form-item>
                    <el-form-item label="importe">
                        <el-input-number v-model="product.imp" controls-position="right" :min="1"></el-input-number>
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
            isVisible: false,           //componente campo visible
            tag: 'persona',             //componente que informacion desea traer
            dialogFormVisible: false,   //hace visible el formulario de cosas adeudadas
            dataProducts: [],
            products: [],                //deudores
            client: {},                //responsable (director de carrera, jefe de division)
            document: {
                id: '',
                fecha: '',
                voucher: "",
            },                          //documento de deuda
            product: { tipo: "1", can: 1, uni: 0, imp: 0 }, //deuda
        };
    },
    mounted() {
        console.log(this.user);
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
            let variable = this.debt;
            product = { tipo: "1", can: 1, uni: 0, imp: uni * can},
                this.products.push(variable);
        }
        /* agrega una cosa que se adeuda */
        /*
        storeNewDebt() {
            let variable = this.debt;
            this.debt = { tipo: "fisica", cant: 1, desc: "" };
            this.debts.push(variable);
        },*/

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
  