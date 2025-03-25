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
                            <el-form ref="form" :model="dataAssignment" label-width="380px" size="small">
                                <el-form-item label="descripcion de la unidad" prop="idc">
                                    {{ dataAssignment.des_prg }}
                                </el-form-item>
                                <el-form-item label="unidad academica o administrativa" prop="idc">
                                    {{ dataAssignment.cod_prg }}
                                </el-form-item>
                                <el-form-item label="codigo del documento" prop="idc">
                                    {{ dataAssignment.idc }}
                                </el-form-item>
                            </el-form>
                        </div>
                    </el-col>
                </el-row>
                <!--
                -->
            </div>
            <el-col :span="24">
                <div class="grid-content bg-purple">
                    <p>lista de activos</p>
                    <el-table :data="dataAssignmentDetails" style="width: 100%" size="small"
                        @selection-change="handleSelectionChange">
                        <el-table-column type="selection"> </el-table-column>
                        <el-table-column prop="codigo" label="codigo" width="120"></el-table-column>
                        <el-table-column prop="descripcion" label="descripcion" width="220"></el-table-column>
                        <el-table-column prop="des_contable" label="partida" width="220"></el-table-column>
                        <el-table-column prop="cantidad" label="cantidad" width="220"></el-table-column>
                        <el-table-column prop="importe" label="importe" width="220"></el-table-column>
                        <el-table-column align="right" width="200">
                            <template slot-scope="scope">
                                <el-button @click="initEditDataFixed(scope.$index, scope.row)" type="primary"
                                    size="mini">Editar</el-button>
                                <el-button @click="initRemoveDataFixed(scope.$index, scope.row)" type="primary"
                                    size="mini">Quitar</el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                    <p></p>
                    <el-button @click="initPrintDataAssignmentDetails" type="info" size="small"
                        :disabled="verifyVisible == false">imprimir
                        activos
                    </el-button>
                </div>
            </el-col>
        </el-card>

        <!-- Form Add Document to Archive-->
        <el-dialog title="accesorios" :visible.sync="dialogFormVisible">
            <el-form :model="dataAditional" label-width="220px" size="small">
                <el-form-item label="cantidad">
                    <el-input v-model="dataAditional.cantidad" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="descripcion">
                    <el-input type="textarea" v-model="dataAditional.descripcion" autocomplete="off"></el-input>
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
            marker: 'registrar',
            dialogFormVisible: false,
            verifyVisible: false,
            dataAssignment: {},
            dataBudgetItem: [],         //partida presupuestaria
            dataAccountingItem: [],     //partida contable
            dataMeasurement: [],        //unidades de medida

            dataAssignmentDetails: [],
            fixedAsset: {
                idx: 0,
                descripcion: '',
                medida: '',
                cantidad: 1,
                importe: 0,
                fecha_adquisicion: '',
                id_contable: '',
                des_contable: '',
                id_presupuesto: '',
                des_presupuesto: '',
                estado: '',
                cod_prg: '',
                des_prg: '',
                ci_resp: '',
                id_asignaciones: '',
                adicional: [],
            },
            dataAditional: {
                cantidad: 0,
                descripcion: '',
            },
        };
    },
    mounted() {
        console.log(this.user);
        this.GetDataFixedAsssetDetails();
    },

    methods: {

        handleSelectionChange(val) {
            this.selectedFixedAssets = val;
        },

        //  *  AF16. Obtiene los activos registrados dentro de un documento       
        async GetDataFixedAsssetDetails() {
            var app = this;
            try {
                let response = await axios.post("/api/getDataFixedAsssetDetails", {
                    id: app.id,
                    year: app.user.gestion,
                });
                app.dataAssignment = response.data.dataAssignment[0]; // todo del documento de entrega
                app.dataBudgetItem = response.data.dataBudgetItem; // partida presupuestaria    
                app.dataAccountingItem = response.data.dataAccountingItem; // codigo contable
                app.dataMeasurement = response.data.dataMeasurement; // tipo de activo
                app.data = response.data.dataAssignmentDetails;
                app.dataAssignmentDetails = response.data.dataAssignmentsDetails;
                if (app.dataAssignmentDetails.length != 0) {
                    app.verifyVisible = "true"
                }

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
            this.fixedAsset.des_contable = resultado.con_des;
        },
        OnchangePresupuesto(idx) {
            let resultado = this.dataBudgetItem.find(tipo => tipo.act_par_cod == idx);
            this.fixedAsset.id_presupuesto = resultado.act_par_cod;
            this.fixedAsset.des_presupuesto = resultado.par_des;
        },
        OnchangeMedida(idx) {
            let resultado = this.dataMeasurement.find(tipo => tipo.uni_des_cor == idx);
            this.fixedAsset.medida = resultado.uni_des_det;
        },

        initAddAditionalDescription() {
            this.dialogFormVisible = true;
            this.stateStore = "añadir";
        },

        initEditAditionalDescription(idx, row) {
            this.dialogFormVisible = true;
            this.dataAditional = row;
            this.stateStore = "editar";
        },

        initStoreAditionalDescription(idx) {
            this.dialogFormVisible = false;
            if (this.stateStore == "añadir") {
                let variable = this.dataAditional;
                this.fixedAsset.adicional.push(variable);
            }
            else {
                let variable = this.dataAditional;
                this.fixedAsset.adicional[idx] = variable;
            }
            this.dataAditional = {
                cantidad: 0,
                descripcion: '',
            };
        },

        initRemoveDetails(idx, row) {
            this.fixedAsset.adicional.splice(idx, 1);
        },

        initAddDataFixed() {
            let variable = this.fixedAsset;
            console.log(variable);
            this.dataAssignmentDetails.push(variable);
            this.fixedAsset = {
                idx: 0,
                codigo: '',
                codigo_anterior: '',
                descripcion: '',
                medida: '',
                cantidad: 1,
                importe: 0,
                fecha_adquisicion: '',
                id_contable: '',
                des_contable: '',
                id_presupuesto: '',
                des_presupuesto: '',
                estado: '',
                cod_prg: '',
                des_prg: '',
                ci_resp: '',
                id_asignaciones: '',
                adicional: [],
            };
        },
        initRemoveDataFixed(idx, row) {
            this.dataAssignmentDetails.splice(idx, 1);
        },
        initEditDataFixed(idx, row) {
            let variable = row;
            this.fixedAsset = variable;
        },

        //  *  AF17. imprimir los activos registrados dentro de un documento       
        initPrintDataAssignmentDetails() {
            let list = [];

            for (var item in this.selectedFixedAssets) {
                list.push(this.selectedFixedAssets[item]["codigo"]);
                //list.push(this.selectedFixedAssets[item]["codigo"].replaceAll('/','-'));
            }
            console.log(list);
            if (list.length != 0) {
                axios({
                    url: "/api/printDataAssignmentDetails/",
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