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
                        <span slot="label"><i class="el-icon-search"></i> Buscar</span>
                        <el-row :gutter="20">
                            <el-col :span="24">
                                <div class="grid-content bg-purple">
                                    <p>activos registrados de anteriores gestiones</p>
                                    <el-input placeholder="ingrese una descripcion" v-model="description"
                                        class="input-with-select" size="small">
                                        <el-button slot="append" icon="el-icon-search"
                                            @click="getSearchFixedAssets">BUSCAR</el-button>
                                    </el-input>
                                    <p></p>
                                    <el-table :data="dataSearched" style="width: 100%" height="400"
                                        @selection-change="handleSelectionChange">
                                        <el-table-column type="selection"> </el-table-column>
                                        <el-table-column prop="des_prg" label="des_prg"></el-table-column>
                                        <el-table-column label="codigo">
                                            <template slot-scope="scope">
                                                <div slot="reference" class="name-wrapper">
                                                    <el-tag size="medium">{{ scope.row.codigo }}</el-tag>
                                                </div>
                                            </template>
                                        </el-table-column>
                                        <el-table-column prop="descripcion" label="descripcion"></el-table-column>
                                    </el-table>
                                    <p>cantidad de activos seleccionados: {{ itemSelected }}</p>
                                    <el-button @click="toggleTab" type="success" size="small">seleccionar activos
                                    </el-button>
                                    <el-button @click="toggleTab2" type="info" size="small">agregar nuevos activos
                                    </el-button>
                                </div>
                            </el-col>
                        </el-row>
                    </el-tab-pane>
                    <!-- Pestaña para editar o agregar activos-->
                    <el-tab-pane name="tab2" :disabled="isTabDisabled">
                        <span slot="label"><i class="el-icon-s-tools"></i> Editar</span>
                        <el-row :gutter="20">
                            <el-col :span="12">
                                <div class="grid-content bg-purple">
                                    <el-form ref="form" :model="fixedAsset" label-width="180px" size="small">
                                        <p>datos generales del activo</p>
                                        <el-form-item label="numero de carnet" prop="ci_resp">
                                            <el-input placeholder="" v-model="fixedAsset.ci_resp"
                                                class="input-with-select">
                                                <el-button slot="append" icon="el-icon-search"
                                                    @click="initSearchManager">BUSCAR</el-button>
                                            </el-input>
                                        </el-form-item>
                                        <el-form-item label="apellidos y nombres" prop="idc">
                                            {{ fixedAsset.des_resp }}
                                        </el-form-item>
                                        <el-form-item label="partida contable">
                                            <el-select v-model="fixedAsset.id_contable" value-key="id_contable"
                                                size="small" placeholder="seleccione la partida contable"
                                                @change="OnchangeContable">
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
                                                v-model="fixedAsset.fecha_adquisicion"
                                                style="width: 100%"></el-date-picker>
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
                                        <el-form-item label="descripcion general">
                                            <el-input type="textarea" placeholder="Ingrese una descripcion general"
                                                v-model="fixedAsset.descripcion">
                                            </el-input>
                                        </el-form-item>
                                        <el-form-item label="estado">
                                            <el-input v-model="fixedAsset.estado" autocomplete="off"></el-input>
                                            <el-select slot-scope="scope" v-model="fixedAsset.estado" value-key="estado"
                                                placeholder="seleccione el estado" size="mini">
                                                <el-option v-for="item in estados" :key="item.id" :label="item.desc"
                                                    :value="item.id">
                                                </el-option>
                                            </el-select>
                                        </el-form-item>
                                    </el-form>
                                </div>
                            </el-col>
                            <el-col :span="12">
                                <div class="grid-content bg-purple">
                                    <p>informacion adicional del activo fijo</p>
                                    <el-table :data="fixedAsset.aditional" style="width: 100%" size="small">
                                        <el-table-column prop="cantidad" label="cantidad" width="90"></el-table-column>
                                        <el-table-column prop="descripcion" label="descripcion" width="220"></el-table-column>
                                        <el-table-column align="right" width="120">
                                            <template slot-scope="scope">
                                                <el-button
                                                    @click="initEditAditionalDescription(scope.$index, scope.row)"
                                                    type="primary" size="mini">Editar</el-button>
                                                <el-button @click="initRemoveDetails(scope.$index, scope.row)"
                                                    type="primary" size="mini">Quitar</el-button>
                                            </template>
                                        </el-table-column>
                                    </el-table>
                                    <p></p>
                                    <el-button @click="initAddAditionalDescription" type="primary" size="small">agregar
                                    </el-button>
                                </div>
                            </el-col>
                        </el-row>
                        <p></p>
                        <el-button @click="initStoreActiveFixed2" type="success" size="small">guardar activos
                        </el-button>
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
            marker: 'agregar',
            activeTab: "tab1", // Controla qué pestaña está activa
            isTabDisabled: true,
            selectedFixedAssets: [],
            itemSelected: 0,
            editFixedAssets: [],

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
                id_contable: '',
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
            const descripciones = this.selectedFixedAssets.map(obj => obj.descripcion.trim());
            console.log(descripciones);
            const todasIguales = descripciones.every((descripcion, _, arr) => descripcion === arr[0]);
            if (!todasIguales) {
                // true si hay al menos una diferente, false si todas son iguales          
                this.$alert('Debe solo seleccionar activos que cuenten con la misma descripcion', 'Alerta', {
                    confirmButtonText: 'OK',
                    callback: action => {
                        //aca la siguiente accion
                    }
                });
            } else {
                //false
                this.isTabDisabled = !this.isTabDisabled;
                this.activeTab = 'tab2';
                this.editFixedAssets = this.selectedFixedAssets;
                this.selectedFixedAssets = [];
                //pasar informacion para su tabulacion
                this.fixedAsset.cantidad = this.itemSelected;
                this.fixedAsset.descripcion = this.editFixedAssets[0].descripcion.trim();
                this.fixedAsset.importe = this.editFixedAssets[0].importe;
                this.fixedAsset.id_contable = this.editFixedAssets[0].id_contable;
                this.fixedAsset.des_contable = this.editFixedAssets[0].des_contable;
                this.fixedAsset.id_presupuesto = this.editFixedAssets[0].id_presupuesto;
                this.fixedAsset.des_presupuesto = this.editFixedAssets[0].des_presupuesto;
                this.fixedAsset.fecha_adquisicion = this.editFixedAssets[0].fecha_adquisicion;
                console.log(this.editFixedAssets);
                console.log(this.fixedAsset);
            }
            this.marker = 'regularizar';
        },

        toggleTab2() {
            // Alternar entre habilitar y deshabilitar la pestaña 2
            this.isTabDisabled = !this.isTabDisabled;
            this.activeTab = 'tab2';
            this.editFixedAssets = this.selectedFixedAssets;
            this.selectedFixedAssets = [];
            console.log(this.fixedAsset);
            this.marker = 'registrar';
        },

        handleSelectionChange(val) {
            this.selectedFixedAssets = val;
            this.itemSelected = this.selectedFixedAssets.length;
            // Extraer las descripciones en un nuevo array
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

        //  *  AC6. Guarda y regulariza los activos ya registrados en gestiones anteriores
        async initStoreActiveFixed2() {
            console.log(this.fixedAsset);
            var app = this;
            try {
                let response = axios
                    .post("/api/storeActiveFixed2", {
                        fixedAsset: app.fixedAsset,
                        document: app.dataDocument,
                        editFixedAssets: app.editFixedAssets,
                        user: app.user,
                        marker: app.marker,
                    });
                app.$alert("Se ha registrado correctamente los activos", 'Gestor de mensajes', {
                    dangerouslyUseHTMLString: true
                });
                app.dialogFormVisible = false;
                app.getAssignments();
            } catch (error) {
                app.$alert("Se ha registrado correctamente la informacion", 'Gestor de mensajes', {
                    dangerouslyUseHTMLString: true
                });
            };

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