<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>documentos digitalizados del estado financiero</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <!--
        <div class="grid-content bg-purple">
          <el-row :gutter="20">
            <el-form :model="archive" label-width="220px" size="small" :disabled="true">
              <el-col :span="12">
                <el-form-item label="codigo">
                  <el-input v-model="archive.id_doc"></el-input>
                </el-form-item>
                <el-form-item label="fecha">
                  <el-input v-model="archive.fecha"></el-input>
                </el-form-item>
                <el-form-item label="glosa">
                  <el-input type="textarea" v-model="archive.glosa"></el-input>
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item label="gestion">
                  <el-input v-model="archive.gestion"></el-input>
                </el-form-item>
                <el-form-item label="estado">
                  <el-input v-model="archive.reserva"></el-input>
                </el-form-item>
                <el-form-item label="tipo">
                  <el-input type="textarea" v-model="archive.descr"></el-input>
                </el-form-item>
              </el-col>
            </el-form>
            <div align="right">
              <el-button size="small" type="primary" @click.prevent="initGoToContainer"
                :disabled="archive.contenido === 0">Ver Contenedor</el-button>
              <el-button size="small" type="warning" @click.prevent="initRemoveLinkToContainer" :disabled="archive.contenido === 0">Liberar del
                contenedor</el-button>
            </div>
          </el-row>
        </div>
        <br>
        -->
            <el-row :gutter="50">
                <el-col :span="24">
                    <div>
                        <el-table :data="documents" border style="width: 100%" size="small">
                            <el-table-column prop="idx" label="indice" align="right" width="100">
                            </el-table-column>
                            <el-table-column prop="descripcion" label="descripcion" width="250" align="left">
                                <template slot-scope="scope">
                                    <el-tag size="medium">{{ scope.row.descripcion }}</el-tag>
                                </template>
                            </el-table-column>

                            <el-table-column align="right-center" label="operaciones" width="250" fixed="right">
                                <template slot-scope="scope">
                                    <el-button :disabled="scope.row.guardado === true" type="primary" size="mini"
                                        @click="getDigitalDocumentById(scope.$index, scope.row)">ver
                                        documento</el-button>
                                    <el-button :disabled="scope.row.guardado === true" type="danger" size="mini"
                                        @click="DeleteDocumentById(scope.$index, scope.row)">quitar</el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </el-col>
            </el-row>
            <!-- Form Add Document to Archive-->
            <el-button type="primary" @click="initAddDocument" size="mini">Agregar nuevo documento</el-button>
            <el-dialog title="detalle del documento" :visible.sync="dialogFormVisible">
                <el-form :model="document" :rules="rules" label-width="220px" size="small" ref="documentForm">
                    <el-form-item label="Numero del documento" prop="idx">
                        <el-input v-model.number="document.idx" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="glosa o descripcion" prop="descripcion">
                        <el-input type="textarea" v-model="document.descripcion" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-upload ref="upload" action="/api/storeDigitalDocument" :auto-upload="false"
                            :file-list="digitalDocument" :multiple="false" :limit="1" :data="document" accept=".pdf"
                            :headers="requestHeaders" :on-success="handleSuccessBoucher" :on-remove="test">
                            <!---->
                            <p></p>
                            <el-button slot="trigger" size="small" type="primary">subir archivo digitalizado</el-button>
                            <div slot="tip" class="el-upload__tip">
                                Solo puede subir archivos pdf
                            </div>
                        </el-upload>
                    </el-form-item>
                    <!--
          -->
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button type="primary" size="mini"
                        @click="storeDigitalDocument('documentForm')">Confirmar</el-button>
                    <el-button type="danger" size="mini" plain @click="dialogFormVisible = false">Cancelar</el-button>
                </span>
            </el-dialog>
            <!-- Form Add Document to Archive-->
            <!--
            <el-button type="success" @click="initStoreArchivesOfDocument" size="small">Guardar documento</el-button>
            <el-button type="danger" @click="initGetReportDocument" size="small">Imprimir documento</el-button>
        -->
        </el-card>
    </div>
</template>

<script>
export default {
    name: "",
    data() {
        return {
            requestHeaders: {
                "X-CSRF-TOKEN": window.axios.defaults.headers.common["X-CSRF-TOKEN"],
                Authorization: "Bearer " + this.$store.state.token,
            },
            user: this.$store.state.user,
            id: null,                    //identificador del documento 
            documents: [],       //lista de archivos pertenecientes a un documento        
            dialogFormVisible: false,   //para el dialogo
            digitalDocument: [],       //lista de documentos digitales 

            stateStore: "",             //estado para ver si se aniade o se edita
            estado_finaciero: {},                //informacion principal del documento
            document: {
                id: "",
                idx: '',
                descripcion: "",
            },
            rules: {
                idx: [
                    { required: true, message: 'Debe agregar un numero', trigger: 'blur' },
                    { type: 'number', message: 'Lo agregado no es numero', trigger: 'blur' }
                ],
                descripcion: [
                    { required: true, message: 'Debe agregar texto', trigger: 'blur' },
                    { min: 5, max: 100, message: 'El texto debe contener minimo 5 letras', trigger: 'blur' }
                ]
            },

        };
    },
    mounted() {
        let app = this;
        app.id = app.$route.params.id;
        app.getDocumentsbyFinalcialStatemet();
    },
    methods: {
        test() {
            alert("test");
        },

        //  * A2. Obtiene la lista de documentos que pertenecen a un archivo
        async getDocumentsbyFinalcialStatemet() {
            let app = this;
            try {
                let response = await axios.post("/api/getDocumentsbyFinalcialStatemet", {
                    id: app.id,
                    year: app.user.gestion,
                });
                app.documents = response.data;
                console.log(response.data);
                console.log("entra aca nuevamente");
                console.log(app.documents);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        handleSuccessBoucher(response, file, fileList) {
            this.$alert('Gracias, acaba de subir el documento digital ' + file.name + ' satifactoriamente.', 'confirmacion', {
                confirmButtonText: 'bueno',
            });
            this.digitalDocument = [];
            console.log(response, file, fileList);
            this.fileList = fileList;
        },

        //  * EF3. Guarda los documentos digitalizados
        storeDigitalDocument(formName) {
            this.$refs[formName].validate((valid) => {
                if (valid) {
                    var app = this;
                    app.document.id = app.id;
                    this.$refs.upload.submit();
                    app.dialogFormVisible = false;
                    app.getDocumentsbyFinalcialStatemet();
                    app.resetForm(formName);
                } else {
                    console.log('error submit!!');
                    return false;
                }
            });
        },

        //  * Iniciar el cuadro de dialogo para insertar un nuevo archivo
        initAddDocument() {
            this.stateStore = "añadir";
            this.dialogFormVisible = true;
        },
        //formulario reseteado
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },

        //  * EF4. Obtener documentos digitalizados
        getDigitalDocumentById(idx, row) {
            let app = this;
            console.log(row);
            axios({
                url: "/api/getDigitalFinancialDocument/",
                params: {
                    id: row.id,
                    year: app.user.gestion,
                },
                method: "GET",
                responseType: "blob",
            }).then((response) => {
                let pdfData = response.data;
                console.log(response);
                let blob = new Blob([pdfData], { type: 'application/pdf' });
                let url = URL.createObjectURL(blob);
                window.open(url);
            });
        },
        //  * EF5. Elimina un documento mal subido
        async DeleteDocumentById(idx, row) {
            let app = this;
            try {
                let response = await axios.post("/api/setDeleteDigitalDocument", {
                    id: row.id,
                    year: app.user.gestion,
                });
                app.getDocumentsbyFinalcialStatemet();

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