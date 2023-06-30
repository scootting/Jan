<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>contenido del contenedor</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <el-row :gutter="20">
                <el-col :span="12">
                    <div class="grid-content bg-purple">
                        <el-button size="small" type="primary" @click.prevent="addSelectedContainer('source')" plain>Agregar
                            Contenedor</el-button>
                        <el-form :model="container" label-width="220px" size="small">
                            <el-form-item label="tipo">
                                <el-input type="textarea" v-model="container.descr"></el-input>
                            </el-form-item>
                            <el-form-item label="codigo">
                                <el-input v-model="container.id_doc"></el-input>
                            </el-form-item>
                            <el-form-item label="gestion">
                                <el-input v-model="container.gestion"></el-input>
                            </el-form-item>
                            <el-form-item label="glosa">
                                <el-input type="textarea" v-model="container.glosa"></el-input>
                            </el-form-item>
                        </el-form>
                    </div>
                    <div class="grid-content bg-purple">
                        <span>documentos</span>
                        <div>
                            <el-table :data="documents" border style="width: 100%" size="small">
                                <el-table-column prop="fecha" label="fecha" width="100" align="center">
                                </el-table-column>
                                <el-table-column prop="id_doc" label="codigo" width="100" align="center">
                                    <template slot-scope="scope">
                                        <el-tag size="medium">{{
                                            scope.row.id_doc
                                        }}</el-tag>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="glosa" label="glosa" width="300" align="right">
                                </el-table-column>
                                <el-table-column align="right-center" label="operaciones" width="120">
                                    <template slot-scope="scope">
                                        <el-button :disabled="scope.row.guardado === true" type="danger" plain size="mini"
                                            @click="initTransferDocumentOrContainer(scope.$index, scope.row, 'source', 'documents')">traspasar</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                    </div>
                    <div class="grid-content bg-purple">
                        <span>contenedores</span>
                        <div>
                            <el-table :data="fileContainer" border style="width: 100%" size="small">
                                <el-table-column prop="fecha" label="fecha" width="100" align="center">
                                </el-table-column>
                                <el-table-column prop="id_doc" label="codigo" width="250" align="center">
                                    <template slot-scope="scope">
                                        <el-tag size="medium">{{
                                            scope.row.id_doc
                                        }}</el-tag>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="glosa" label="descripcion" width="100" align="right">
                                </el-table-column>
                                <el-table-column align="right-center" label="operaciones" width="150">
                                    <template slot-scope="scope">
                                        <el-button type="danger" plain size="mini"
                                            @click="initTransferDocumentOrContainer(scope.$index, scope.row, 'source', 'containers')">traspasar</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                    </div>
                </el-col>
                <el-col :span="12">
                    <div class="grid-content bg-purple">
                        <el-button size="small" type="primary" @click.prevent="addSelectedContainer('target')" plain>Agregar
                            Contenedor</el-button>
                        <el-form :model="container2" label-width="220px" size="small">
                            <el-form-item label="tipo">
                                <el-input type="textarea" v-model="container2.descr"></el-input>
                            </el-form-item>
                            <el-form-item label="codigo">
                                <el-input v-model="container2.id_doc"></el-input>
                            </el-form-item>
                            <el-form-item label="gestion">
                                <el-input v-model="container2.gestion"></el-input>
                            </el-form-item>
                            <el-form-item label="glosa">
                                <el-input type="textarea" v-model="container2.glosa"></el-input>
                            </el-form-item>
                        </el-form>
                    </div>
                    <div class="grid-content bg-purple">
                        <span>documentos</span>
                        <div>
                            <el-table :data="documents2" border style="width: 100%" size="small">
                                <el-table-column prop="fecha" label="fecha" width="100" align="center">
                                </el-table-column>
                                <el-table-column prop="id_doc" label="codigo" width="100" align="center">
                                    <template slot-scope="scope">
                                        <el-tag size="medium">{{
                                            scope.row.id_doc
                                        }}</el-tag>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="glosa" label="glosa" width="300" align="right">
                                </el-table-column>
                                <el-table-column align="right-center" label="operaciones" width="120">
                                    <template slot-scope="scope">
                                        <el-button type="danger" plain size="mini"
                                            @click="initTransferDocumentOrContainer(scope.$index, scope.row, 'target', 'documents')">traspasar</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                    </div>
                    <div class="grid-content bg-purple">
                        <span>contenedores</span>
                        <div>
                            <el-table :data="fileContainer2" border style="width: 100%" size="small">
                                <el-table-column prop="fecha" label="fecha" width="100" align="center">
                                </el-table-column>
                                <el-table-column prop="id_doc" label="codigo" width="250" align="center">
                                    <template slot-scope="scope">
                                        <el-tag size="medium">{{
                                            scope.row.id_doc
                                        }}</el-tag>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="glosa" label="descripcion" width="100" align="right">
                                </el-table-column>
                                <el-table-column align="right-center" label="operaciones" width="150">
                                    <template slot-scope="scope">
                                        <el-button type="danger" plain size="mini"
                                            @click="initTransferDocumentOrContainer(scope.$index, scope.row, 'target', 'containers')">traspasar</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                    </div>
                </el-col>
            </el-row>
            <!-- Form Add Document and Containers -->
            <el-dialog title="contenedores" :visible.sync="dialogFormVisible">
                <el-table v-loading="loading" :data="data" style="width: 100%">
                    <el-table-column width="90" label="No.">
                        <template slot-scope="scope">
                            <div slot="reference" class="name-wrapper">
                                <el-tag size="medium">{{ scope.row.id_doc }}</el-tag>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="fecha" width="100" label="fecha"></el-table-column>
                    <el-table-column width="250" label="tipo">
                        <template slot-scope="scope">
                            <div slot="reference" class="name-wrapper">
                                <el-tag size="medium" type="danger">{{ scope.row.descr }}</el-tag>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="glosa" width="250" label="descripcion del contenedor"></el-table-column>
                    <el-table-column align="right-center" width="150" label="Operaciones" fixed="right">
                        <template slot-scope="scope">
                            <el-button @click="getDocumentsByFileContainer(scope.row, scope.row.id)" type="success" plain
                                size="mini">seleccionar
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
                    :current-page="pagination.current_page" :total="pagination.total"
                    @current-change="getFileContainer"></el-pagination>
                <span slot="footer" class="dialog-footer">
                    <el-button type="danger" size="small" plain @click="dialogFormVisible = false">Cerrar</el-button>
                </span>
            </el-dialog>
            <!-- Form Add Document to Archive-->
            <el-button type="warning" @click="onStoreTransferDocumentsAndContainers" size="small">Guardar cambios</el-button>
        </el-card>
    </div>
</template>
<script>
export default {
    name: "",
    data() {
        return {
            user: this.$store.state.user,
            loading: false,
            data: [],
            pagination: {
                page: 1,
            },

            id: null,
            id2: null,                    //identificador del contenedor 
            container: {},                //informacion del contenedor 
            container2: {},                //informacion del contenedor 
            documents: [],               //lista de documentos que pertenecen al contenedor
            documents2: [],               //lista de documentos que pertenecen al contenedor
            fileContainer: [],           //lista de contenedores que pertenecen al contenedor
            fileContainer2: [],           //lista de contenedores que pertenecen al contenedor
            allDocumentsAndContainers: [],
            dialogFormVisible: false,    //para el dialogo
            stateContainer: "",              //estado para ver que contenedor es

            dataDocuments: [],            //la posible lista de documentos que no estan en otro contenedor
            dataContainers: [],            //la posible lista de contenedores que no estan relacionados
            selected: [],         //documentos nuevos selecccionados
        };
    },
    mounted() {
        let app = this;
        app.getFileContainer(1);
        /*
        app.id = app.$route.params.id;
        app.getDocumentAndFilesContainerById();
        app.getDocumentAndContainerFree();*/
    },

    methods: {
        test() {
            alert("test");
        },

        //  * 1. Obtener una lista de inventarios por usuario de el recurso utilizado.
        getFileContainer(page) {
            this.loading = true;
            let app = this;
            axios
                .post("/api/archive", {
                    year: app.user.gestion,
                    page: page,
                    id: 'C',
                })
                .then((response) => {
                    app.loading = false;
                    app.data = Object.values(response.data.data);
                    console.log(app.data);
                    app.pagination = response.data;
                })
                .catch((error) => {
                    this.error = error;
                    this.$notify.error({
                        title: "Error",
                        message: this.error.message,
                    });
                });
        },

        //  * A12. Obtiene la lista de documentos y contenedores que sean menor al actual
        async getDocumentAndFilesContainerById(id) {
            let app = this;
            try {
                let response = await axios.post("/api/getDocumentAndFilesContainerById", {
                    id: id,
                });
                if (this.stateContainer == "source") {
                    app.documents = response.data.documents;
                    app.fileContainer = response.data.fileContainer;
                    app.container = response.data.container[0];
                } else {
                    app.documents2 = response.data.documents;
                    app.fileContainer2 = response.data.fileContainer;
                    app.container2 = response.data.container[0];
                }
            } catch (error) {
                console.log(error);
            }
        },
        addSelectedContainer(container) {
            this.dialogFormVisible = true;
            this.stateContainer = container;
        },
        getDocumentsByFileContainer(row, id) {
            console.log(row);
            let app = this;
            if (this.stateContainer == "source") {
                this.id = id;
            }
            else {
                this.id2 = id;
            }
            console.log(this.id);
            console.log(this.id2);
            console.log(this.stateContainer);
            app.getDocumentAndFilesContainerById(id);
        },
        async onStoreTransferDocumentsAndContainers() {
            var app = this;
            try {
                this.allDocumentsAndContainers = app.documents.concat(app.documents2).concat(app.fileContainer).concat(app.fileContainer2);
                console.log(app.allDocumentsAndContainers);
                let response = await axios
                    .post("/api/storeTransferDocumentsAndContainers", {
                        all: app.allDocumentsAndContainers,
                        marker: "editar",//editar
                    });
                console.log(response);
                app.$alert("Se ha registrado correctamente los cambios", 'Gestor de mensajes', {
                    dangerouslyUseHTMLString: true
                });
            } catch (error) {
                app.$alert("No se registro nada", 'Gestor de mensajes', {
                    dangerouslyUseHTMLString: true
                });
            };
        },
        initTransferDocumentOrContainer(idx, row, container, types) {
            let app = this;
            let temp = row;
            if (container == "source") {
                if (types == "documents") {
                    this.documents.splice(idx, 1);
                    temp.id_raiz = app.id2;
                    this.documents2.push(temp);
                }
                else {
                    this.container.splice(idx, 1);
                    temp.id_raiz = app.id2;
                    this.container2.push(temp);
                }
                console.log(this.id);
                console.log(this.id2);
                console.log(this.documents);
                console.log(this.documents2);
            }
            else {
                if (types == "documents") {
                    this.documents2.splice(idx, 1);
                    temp.id_raiz = app.id;
                    this.documents.push(temp);
                }
                else {
                    this.container2.splice(idx, 1);
                    temp.id_raiz = app.id;
                    this.container.push(temp);
                }
            }
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
  
  