<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>detalle de ingresos del curso de postgrado</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <el-row :gutter="50">
                <el-col :span="24">
                    <div>
                        <el-table :data="dataInputsCourse" border style="width: 100%" height="400" size="small">
                            <el-table-column prop="ci_per" label="ci" width="120">
                                <template slot-scope="scope">
                                    <el-tag size="small" type="primary">{{ scope.row.ci_per }}</el-tag>
                                </template>
                            </el-table-column>
                            <el-table-column prop="glosa" label="apellidos y nombres" width="250"></el-table-column>
                            <el-table-column prop="fec_tra" label="fecha"></el-table-column>
                            <el-table-column prop="imp_val" label="importe">
                                <template slot-scope="scope">
                                    <el-tag size="small" type="default">{{ scope.row.imp_val }}</el-tag>
                                </template>
                            </el-table-column>
                            <el-table-column align="right-center" label="operaciones" width="180">
                                <template slot-scope="scope">
                                    <el-button :disabled="scope.row.guardado === true" type="primary" plain size="mini"
                                        @click="initEditDocumentOfArchive(scope.$index, scope.row)">revertir</el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                        <!--
                -->
                    </div>
                </el-col>
            </el-row>
            <el-button type="primary" @click="initAddInputCourse" size="small">Agregar ingreso automaticamente</el-button>
            <el-button type="success" @click="initAddInputManualCourse" size="small">Agregar ingreso manualmente</el-button>

            <!-- Form Add Register Input Manual-->
            <el-dialog title="detalle" :visible.sync="dialogFormVisible2">
                <el-form :model="document" label-width="220px" size="small">
                    <el-form-item label="tipo de documento">
                        <el-select v-model="document.descr" value-key="descr" size="small"
                            placeholder="seleccione el tipo de documento" @change="OnchangeTypeDocument">
                            <el-option v-for="item in typesDocument" :key="item.id" :label="item.descr" :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Numero del documento">
                        <el-input v-model="document.numeral" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="fecha del registro">
                        <el-date-picker type="date" v-model="document.fecha" placeholder="seleccione una fecha"
                            style="width: 100%" format="yyyy/MM/dd" value-format="yyyy-MM-dd"></el-date-picker>
                    </el-form-item>
                    <el-form-item label="glosa o descripcion">
                        <el-input type="textarea" v-model="document.glosa" autocomplete="off"></el-input>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button type="primary" size="small" plain @click="AddArchiveToDocument">Confirmar</el-button>
                    <el-button type="danger" size="small" plain @click="dialogFormVisible = false">Cancelar</el-button>
                </span>
            </el-dialog>
            <!-- Form Add Register Input Manual-->

            <!-- Form Add Register Input Auto-->
            <el-dialog title="detalle de transacciones no conciliadas" :visible.sync="dialogFormVisible">
                <el-table :data="dataInputs" style="width: 100%" height="250" @selection-change="handleSelectionChange">
                    <el-table-column type="selection"> </el-table-column>
                    <el-table-column prop="ci_per" label="ci" width="120">
                        <template slot-scope="scope">
                            <el-tag size="small" type="primary">{{ scope.row.ci_per }}</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column prop="des_per" label="apellidos y nombres" width="250"></el-table-column>
                    <el-table-column prop="fec_tra" label="fecha"></el-table-column>
                    <el-table-column prop="imp_val" label="importe">
                        <template slot-scope="scope">
                            <el-tag size="small" type="default">{{ scope.row.imp_val }}</el-tag>
                        </template>
                    </el-table-column>
                </el-table>
                <span slot="footer" class="dialog-footer">
                    <el-button type="primary" size="small" plain @click="initStoreInputCourse">Agregar</el-button>
                    <el-button type="danger" size="small" plain @click="dialogFormVisible = false">Cancelar</el-button>
                </span>
            </el-dialog>
            <!-- Form Add Register Input Auto-->
        </el-card>
    </div>
</template>
    
<script>
export default {
    name: "",
    data() {
        return {
            user: this.$store.state.user,
            id: null,                    //identificador del curso de postgrado
            dataInputs: [],             //transacciones no conciliadas
            dataSelected: [],             //para las transacciones seleccionadas
            dataInputsCourse: [],       //ingresos conciliados

            dialogFormVisible: false,   //para el dialogo
            dialogFormVisible2: false,   //para el dialogo


            documentsArchive: [],       //lista de archivos pertenecientes a un documento
            typesDocument: [],          //diferentes tipos de archivos que pertenecen a un documento
            stateStore: "",             //estado para ver si se aniade o se edita
            document: {
                id_doc: "",
                indice: 0,
                numeral: "",
                glosa: "",
                fecha: "",
                id_tipo: 'A',
                id_arch: null,
                descr: "",
                gestion: "",
            },
        };
    },
    mounted() {
        let app = this;
        app.id = app.$route.params.id;
        console.log(this.user);
        app.getDataInputsCourse();
    },
    methods: {
        test() {
            alert("test");
        },

        //  * RP5. obtiene los ingresos  conciliados del curso de postgrado
        async getDataInputsCourse() {
            let app = this;
            try {
                let response = await axios.post("/api/getInputTransactionsOfCourse", {
                    year: this.user.gestion,
                    id: this.id,
                });
                app.dataInputsCourse = response.data;
            } catch (error) {
                console.log(error);
            }

        },
        //  * RP3. Obtiene las transacciones realizadas en caja universitaria del valorado - curso de postgrado
        async initAddInputCourse() {
            let app = this;
            try {
                let response = await axios.post("/api/getInputCourse", {
                    year: this.user.gestion,
                    id: this.id,
                });
                app.dataInputs = response.data;
                app.dialogFormVisible = true;
                console.log(app.dataInputs);
            } catch (error) {
                console.log(error);
            }
        },

        //  * RP4. Guarda las transacciones conciliadas del curso de postgrado
        async initStoreInputCourse() {
            var app = this;
            try {
                app.dataInputsCourse = app.dataSeleted;
                let response = axios
                    .post("/api/storeInputCourse", {
                        id: app.id,
                        dataSelected: app.dataSelected,
                        user: app.user,
                        marker: "registrar", //editar
                    });
                app.dialogFormVisible = false;
                app.dataSelected = {};
                app.getDataInputsCourse();
                app.$alert("Se ha registrado correctamente los archivos del documento", 'Gestor de mensajes', {
                    dangerouslyUseHTMLString: true
                });
            } catch (error) {
                console.log(error);
                app.$alert("No se registro nada", 'Gestor de mensajes', {
                    dangerouslyUseHTMLString: true
                });
            };
        },

        handleSelectionChange(val) {
            this.dataSelected = val;
            console.log(this.dataSelected);
        },

        initAddInputManualCourse() {

        },





        //  * A9. Obtiene la lista de tipos de documentos que pertenecen a un archivo
        async getTypesDocument() {
            let app = this;
            try {
                let response = await axios.post("/api/getTypesDocumentById", {
                    id_type: "A",
                });
                app.typesDocument = response.data;
            } catch (error) {
                console.log(error);
            }
        },
        //  * A2. Obtiene la lista de documentos que pertenecen a un archivo
        async getDocumentsbyArchive() {
            let app = this;
            try {
                let response = await axios.post("/api/getDocumentsbyArchive", {
                    id: app.id,
                    year: app.user.gestion,
                });
                app.documentsArchive = response.data;
                console.log(app.documentsArchive);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        //  * A11. Guardar los archivos del documento
        async initStoreArchivesOfDocument() {
            var app = this;
            var newArchivesOfDocument = app.documentsArchive;
            var newDocument = app.id;
            var newYear = app.user.gestion;
            try {
                console.log(newArchivesOfDocument);
                let response = axios
                    .post("/api/storeArchivesOfDocument", {
                        archivesOfDocument: newArchivesOfDocument,
                        document: newDocument,  //add
                        year: newYear,
                        marker: "añadir", //editar
                    });
                app.$alert("Se ha registrado correctamente los archivos del documento", 'Gestor de mensajes', {
                    dangerouslyUseHTMLString: true
                });
                this.$router.push({
                    name: "archive",
                });
            } catch (error) {
                app.$alert("No se registro nada", 'Gestor de mensajes', {
                    dangerouslyUseHTMLString: true
                });
            };
        },

        //  * Inicia la edicion de un documento
        initEditDocumentOfArchive(idx, row) {
            this.document = row;
            this.stateStore = "editar";
            this.dialogFormVisible = true;
        },

        //  * Iniciar el cuadro de dialogo para insertar un nuevo archivo
        initAddDocumentOfArchive() {
            this.stateStore = "añadir";
            this.dialogFormVisible = true;
        },

        //  * Guarda los cambios de un nuevo documento sea nuevo o uno ya existente
        AddArchiveToDocument() {
            this.dialogFormVisible = false;
            if (this.stateStore == "añadir") {
                let variable = this.document;
                this.documentsArchive.push(variable);
            }
            else {

            }
            this.document = { id_doc: "", indice: 0, numeral: "", glosa: "", fecha: "", id_tipo: 'A', id_arch: null, descr: "", gestion: "" };
            console.log(this.documentsArchive);
            this.OnUpdateIndex();
        },

        //  * Quita un documento ya existente
        DeleteDocumentOfArchive(idx, row) {
            this.documentsArchive.splice(idx, 1);
            console.log(this.documentsArchive);
            this.OnUpdateIndex();
        },

        //  * Actualiza el indice de la lista
        OnUpdateIndex() {
            let indice = 0;
            this.documentsArchive.forEach(element => {
                indice += 1;
                element.indice = indice;
            });
        },

        //* actualizar un componente al hacer la seleccion nueva *//
        OnchangeTypeDocument(idx) {
            console.log(this.document);
            console.log(idx);
            let resultado = this.typesDocument.find(tipo => tipo.id == idx);
            this.document.id_arch = resultado.id;
            this.document.descr = resultado.descr;
            console.log(resultado);
            console.log(this.document);
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

.el-form .el-select {
    width: 100%;
}
</style>
    