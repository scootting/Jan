<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>documento de regularizacion de activos</span>
                <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
            </div>
            <div>
                <el-row :gutter="20">
                    <el-col :span="24">
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
                        </div>
                    </el-col>
                </el-row>
                <el-tabs type="border-card" v-model="activeTab">
                    <!-- Pestaña para buscar activos-->
                    <el-tab-pane name="tab1">
                        <span slot="label"><i class="el-icon-paperclip"></i> imprimir</span>
                        <el-row :gutter="20">
                            <el-col :span="24">
                                <div class="grid-content bg-purple">
                                    <p>Activos fijos</p>
                                    <el-table :data="dataFixedAssets" style="width: 100%" size="small"
                                        @selection-change="handleSelectionChange">
                                        <el-table-column type="selection"> </el-table-column>
                                        <el-table-column prop="codigo" label="codigo" width="190"></el-table-column>
                                        <el-table-column prop="codigo_anterior" label="codigo anterior"
                                            width="190"></el-table-column>
                                        <el-table-column prop="descripcion" label="descripcion"
                                            width="420"></el-table-column>
                                        <el-table-column prop="estado" label="estado" width="90"></el-table-column>
                                    </el-table>
                                    <p></p>
                                    <el-button @click="initPrintSelectedFixedAssets" type="primary" size="small">imprimir
                                        seleccionados
                                    </el-button>
                                </div>
                            </el-col>
                        </el-row>
                    </el-tab-pane>
                    <!-- Pestaña para editar o agregar activos-->
                    <el-tab-pane name="tab2" :disabled="isTabDisabled">
                        <span slot="label"><i class="el-icon-s-tools"></i> Editar</span>
                    </el-tab-pane>
                </el-tabs>
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
            marker: 'editar',
            activeTab: "tab1", // Controla qué pestaña está activa
            isTabDisabled: true,
            dataFixedAssets: [],


            selectedFixedAssets: [],
            itemSelected: 0,

            estados: [], // lista de estados disponibles por activo fijo
            dataFixedAssets: [],
            fixedAsset: {
                idx: 0,
                codigo: '',
                codigo_anterior: '',
                descripcion: '',
                medida: '',
                cantidad: 1,
                importe: 0,
                fecha_adquisicion: '',
                id_contable: 0,
                des_contable: '',
                id_presupuesto: '',
                des_presupuesto: '',
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
        this.getStatesByActive();
    },
    methods: {
        test() { },

        toggleTab() {
            // Alternar entre habilitar y deshabilitar la pestaña 2
            this.marker = 'regularizar';
        },

        handleSelectionChange(val) {
            this.selectedFixedAssets = val;
            this.itemSelected = this.selectedFixedAssets.length;

        },

        initPrintSelectedFixedAssets() {
            let list = [];
            for (var item in this.selectedFixedAssets) {
                list.push(this.selectedFixedAssets[item]["id"]);
            }
            console.log(list);
            if (list.length != 0) {
                axios({
                    url: "/api/reportSelectedFixedAssets2/",
                    params: {
                        lista: list,
                        reporte: "FixedAssetsQr_B4",
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
            } else {
                alert("debe seleccionar por lo menos un elemento");
            }
        },

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
                app.dataFixedAssets = response.data.dataFixedAssets;
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        //  * 4. Obtener una lista de estados por cada activo fijo utilizado.
        async getStatesByActive() {
            let app = this;
            try {
                let response = await axios.get("/api/getStatesByActive", {
                });
                app.estados = response.data;
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

        initRemoveSelectedFixedAssets(idx, row) {
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