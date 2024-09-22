<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>documento de regularizacion de activos</span>
                <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
            </div>
            <div>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <div class="grid-content bg-purple">
                            <p>datos generales de la solicitud</p>
                            <el-form ref="form" :model="dataDocument" label-width="180px" size="small">
                                <el-form-item label="codigo programatico" prop="idc">
                                    {{ dataDocument.cod_prg }}
                                </el-form-item>
                                <el-form-item label="descripcion de la unidad" prop="idc">
                                    {{ dataDocument.des_prg }}
                                </el-form-item>
                                <el-form-item label="codigo del documento" prop="idc">
                                    {{ dataDocument.idc }}
                                </el-form-item>
                            </el-form>
                            <p>activos registrados de anteriores gestiones</p>
                            <el-input placeholder="ingrese una descripcion" v-model="description"
                                class="input-with-select" size="small">
                                <el-button slot="append" icon="el-icon-search"
                                    @click="getSearchFixedAssets">BUSCAR</el-button>
                            </el-input>
                            <p></p>
                            <el-table :data="dataSearched" style="width: 100%" height="400">
                                <el-table-column prop="des_prg" label="des_prg"></el-table-column>
                                <el-table-column label="codigo">
                                    <template slot-scope="scope">
                                        <div slot="reference" class="name-wrapper">
                                            <el-tag size="medium">{{ scope.row.codigo }}</el-tag>
                                        </div>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="descripcion" label="descripcion"></el-table-column>
                                <el-table-column align="right" width="120">
                                    <template slot-scope="scope">
                                        <el-button @click="initSelectedFixedAssets(scope.$index, scope.row)"
                                            type="primary" size="small"> agregar</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                    </el-col>
                    <el-col :span="12">
                        <div class="grid-content bg-purple">
                            <el-form ref="form" :model="fixedAsset" label-width="180px" size="small">
                                <p>datos generales del activo</p>
                                <el-form-item label="numero de carnet" prop="ci_resp">
                                    <el-input placeholder="" v-model="fixedAsset.ci_resp" class="input-with-select">
                                        <el-button slot="append" icon="el-icon-search"
                                            @click="initSearchManager">BUSCAR</el-button>
                                    </el-input>
                                </el-form-item>
                                <el-form-item label="apellidos y nombres" prop="idc">
                                    {{ fixedAsset.des_resp }}
                                </el-form-item>
                                <el-form-item label="partida contable">
                                    <el-select v-model="fixedAsset.id_contable" value-key="id_contable" size="small"
                                        placeholder="seleccione la partida contable" @change="OnchangeContable">
                                        <el-option v-for="item in dataAccountingItem" :key="item.con_cod"
                                            :label="item.con_des" :value="item.con_cod">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                                <el-form-item label="partida presupuestaria">
                                    <el-select v-model="fixedAsset.id_presupuesto" value-key="id_presupuesto"
                                        size="small" placeholder="seleccione la partida presupuestaria"
                                        @change="OnchangePresupuesto">
                                        <el-option v-for="item in dataBudgetItem" :key="item.act_par_cod"
                                            :label="item.par_des" :value="item.act_par_cod">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                                <el-form-item label="fecha de registro" prop="fecha">
                                    <el-date-picker type="date" placeholder="seleccione una fecha"
                                        v-model="fixedAsset.fecha_adquisicion" style="width: 100%"></el-date-picker>
                                </el-form-item>
                                <el-form-item label="unidad de medida">
                                    <el-select v-model="fixedAsset.medida" value-key="medida" size="small"
                                        placeholder="seleccione el tipo de medida" @change="OnchangeMedida">
                                        <el-option v-for="item in dataMeasurement" :key="item.uni_des_cor"
                                            :label="item.uni_des_det" :value="item.uni_des_cor">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                                <el-form-item label="cantidad">
                                    <el-input v-model="fixedAsset.cantidad" autocomplete="off"></el-input>
                                </el-form-item>
                                <el-form-item label="precio unitario">
                                    <el-input v-model="fixedAsset.importe" autocomplete="off"></el-input>
                                </el-form-item>
                                <el-form-item label="descripcion general" prop="des_general">
                                    <el-input type="textarea" autosize
                                        placeholder="Ingrese una descripcion general del activo"
                                        v-model="fixedAsset.des_general">
                                    </el-input>
                                </el-form-item>
                            </el-form>
                            <p>informacion adicional del activo fijo</p>
                            <el-table :data="fixedAsset.aditional" style="width: 100%" size="small">
                                <el-table-column prop="cantidad" label="cantidad" width="90"></el-table-column>
                                <el-table-column prop="descripcion" label="descripcion" width="220"></el-table-column>
                                <el-table-column align="right">
                                    <template slot-scope="scope">
                                        <el-button @click="initEditAditionalDescription(scope.$index, scope.row)"
                                            type="primary" size="small">Editar</el-button>
                                        <el-button @click="initRemoveDetails(scope.$index, scope.row)" type="primary"
                                            size="small">Quitar</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <p></p>
                            <el-button @click="initAddAditionalDescription" type="primary" size="small">agregar
                            </el-button>
                        </div>
                    </el-col>
                </el-row>
                <el-row :gutter="20">
                    <el-col :span="24">
                        <div class="grid-content bg-purple">
                            <p>Activos fijos</p>
                            <el-table :data="dataFixedAssets" style="width: 100%" size="small">
                                <el-table-column prop="codigo" label="codigo" width="90"></el-table-column>
                                <el-table-column prop="cantidad" label="cantidad" width="90"></el-table-column>
                                <el-table-column prop="descripcion" label="descripcion" width="220"></el-table-column>
                                <el-table-column align="right">
                                    <template slot-scope="scope">
                                        <el-button @click="initEditAditionalDescription(scope.$index, scope.row)"
                                            type="primary" size="small">Editar</el-button>
                                        <el-button @click="initRemoveDetails(scope.$index, scope.row)" type="primary"
                                            size="small">Quitar</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <el-button @click="storeDebtorDocument" type="primary" size="small">guardar informacion
                            </el-button>
                        </div>
                    </el-col>
                </el-row>
            </div>
            <information :visible="isVisible" :tag='tag' @update-visible="updateIsVisible"></information>
        </el-card>
        <!-- Form Add Document to Archive-->
        <el-dialog title="detalle adiciona" :visible.sync="dialogFormVisible">
            <el-form :model="aditionalDetails" label-width="220px" size="small">
                <el-form-item label="cantidad">
                    <el-input v-model="aditionalDetails.cantidad" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="descripcion">
                    <el-input type="textarea" v-model="aditionalDetails.descripcion" autocomplete="off"></el-input>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" size="small" plain @click="initStoreAditionalDescription">agregar</el-button>
                <el-button type="danger" size="small" plain @click="dialogFormVisible = false">Cancelar</el-button>
            </span>
        </el-dialog>
        <!-- Form Add Document to Archive-->
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
            dataFixedAssets: [],
            fixedAsset: {
                idx: 0,
                codigo: '',
                codigo_anterior: '',
                des_general: '',
                des_detallada: '',
                medida: '',
                cantidad: 0,
                importe: 0,
                fecha_adquisicion: '',
                id_contable: 0,
                id_presupuesto: '',
                estado: '',
                cod_prg: '',
                des_prg: '',
                ci_resp: '',
                id_asignaciones: '',
                aditional: [],
            },         //documento de regularizacion
            description: '',
            dataSearched: [],
            dataDocument: {},
            dataBudgetItem: [],         //partida presupuestaria
            dataAccountingItem: [],     //partida contable
            dataMeasurement: [],        //unidades de medida
            manager: {},                //responsable
            isVisible: false,           //componente campo visible
            tag: '',                    //componente que informacion desea traer
            flag: '',                   //deudor, responsable, categoria programatica

            dialogFormVisible: false,   //hace visible el formulario de cosas adeudadas
            aditionalDetails: {
                cantidad: 0,
                descripcion: '',
            },
        };
    },
    mounted() {
        console.log(this.user);
        this.getFixedAssetsDetails();
    },
    methods: {

        test() { },
        async getFixedAssetsDetails() {
            var app = this;
            try {
                let response = await axios.post("/api/getFixedAssetsDetails", {
                    id: app.id,
                    typea: 0,
                    year: app.user.gestion,
                });
                app.dataDocument = response.data.dataDocument[0];
                app.dataBudgetItem = response.data.dataBudgetItem;
                app.dataAccountingItem = response.data.dataAccountingItem;
                app.dataMeasurement = response.data.dataMeasurement;
                console.log(app.dataDocument);
                console.log(app.dataBudgetItem);
                console.log(app.dataAccountingItem);
                console.log(app.dataMeasurement);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        async getSearchFixedAssets() {
            var app = this;
            try {
                let response = await axios.post("/api/getSearchFixedAssets", {
                    description: app.description,
                    year: app.user.gestion,
                });
                app.dataSearched = response.data.dataSearched;
                console.log(app.dataSearched);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }

        },

        OnchangeContable(idx) {
            let resultado = this.dataAccountingItem.find(tipo => tipo.con_cod == idx);
            this.fixedAsset.id_contable = resultado.con_cod;
        },
        OnchangePresupuesto(idx) {
            let resultado = this.dataBudgetItem.find(tipo => tipo.act_par_cod == idx);
            this.fixedAsset.id_presupuesto = resultado.act_par_cod;
        },
        OnchangeMedida(idx) {
            let resultado = this.dataMeasurement.find(tipo => tipo.uni_des_cor == idx);
            this.fixedAsset.medida = resultado.uni_des_det;
        },

        initSearchPrg() {
            this.isVisible = true;
            this.tag = 'categoria';
            this.flag = 'categoria';
        },
        initSearchManager() {
            this.isVisible = true;
            this.tag = 'persona';
            this.flag = 'responsable';
        },

        updateIsVisible(visible, data) {
            this.isVisible = visible;
            this.data = data;
            console.log(this.isVisible + " " + this.data);
            if (data != null) {
                switch (this.flag) {
                    case 'categoria':
                        this.fixedAsset.cod_prg = data.id;
                        this.fixedAsset.des_prg = data.details;
                        break;
                    case 'responsable':
                        this.manager = data;
                        this.fixedAsset.ci_resp = data.id;
                        this.fixedAsset.des_resp = data.details;
                        console.log(data);
                        break;
                    default:
                        break;
                }
            }
        },

        initAddAditionalDescription() {
            this.dialogFormVisible = true;
            this.stateStore = "añadir";
        },
        initEditAditionalDescription(idx, row) {
            this.dialogFormVisible = true;
            this.aditionalDetails = row;
            this.stateStore = "editar";
        },

        initSelectedFixedAssets(idx, row){
            console.log(row);
            this.fixedAsset.codigo_anterior = row.codigo;
            this.fixedAsset.codigo = row.codigo;
            let variable = this.fixedAsset;
            this.dataFixedAssets.push(variable);
        },
        initRemoveSelectedFixedAssets(idx, row){
            this.dataFixedAssets.splice(idx, 1);
        },
        initStoreAditionalDescription() {
            this.dialogFormVisible = false;
            if (this.stateStore == "añadir") {
                let variable = this.aditionalDetails;
                this.fixedAsset.aditional.push(variable);
            }
            else {
            }
            this.aditionalDetails = {
                cantidad: 0,
                descripcion: '',
            };
        },

        initRemoveDetails(idx, row) {
            this.fixedAsset.aditional.splice(idx, 1);
        },

        //  * S2. Guardar la informacion de un nuevo documento de deuda.
        async storeDebtorDocument() {
            var app = this;
            try {
                let response = await axios.post("/api/storeDataRegularize", {
                    usuario: app.user,
                    documento: app.fixedAsset,
                    marker: "registrar",
                });
                app.numero = response.data;
                console.log(response);
                this.$confirm('Desea agregar algunas series?', 'Proceso de Verificacion', {
                    confirmButtonText: 'Continuar',
                    cancelButtonText: 'Cancelar',
                    type: 'success'
                }).then(() => {
                    /*pasa directamente al editar*/
                    this.$router.push({
                        name: "EditFixedAssetsDetails",
                        params: {
                            id: response.data,
                        },
                    });

                }).catch(() => {
                    /*pasa directamente a la lista de deudas*/
                    this.$router.push({ name: "RegularizeFixedAssets" });
                });

            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

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

.el-form .el-select {
    width: 100%;
}
</style>