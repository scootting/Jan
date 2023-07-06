<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>verificacion de la solicitud</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <el-row :gutter="20">
                <el-col :span="12">
                    <div class="grid-content bg-purple">
                        <p>datos de la solicitud</p>
                        <el-form ref="form" :model="this.dataRequest" label-width="200px" size="mini">
                            <el-form-item label="numero de la solicitud">
                                <el-input v-model="dataRequest.idc" disabled></el-input>
                            </el-form-item>
                            <el-form-item label="fecha de la solicitud">
                                <el-input v-model="dataRequest.fecha" disabled></el-input>
                            </el-form-item>
                            <el-form-item label="detalle de la solicitud">
                                <el-input v-model="dataRequest.des_per" disabled></el-input>
                            </el-form-item>
                            <el-form-item label="importe de la solicitud">
                                <el-input v-model="dataRequest.importe" disabled></el-input>
                            </el-form-item>
                            <el-form-item label="estado de la solicitud">
                                <el-input v-model="dataRequest.estado" disabled></el-input>
                            </el-form-item>
                        </el-form>
                    </div>
                    <p></p>
                    <div class="grid-content bg-purple">
                        <p>conceptos registrados</p>
                        <el-table v-loading="loading" :data="dataDetailRequest" style="width: 100%">
                            <el-table-column prop="cod_val" label="cod"></el-table-column>
                            <el-table-column prop="des_val" label="descripcion"></el-table-column>
                            <el-table-column prop="can_val" label="cant" align="right"></el-table-column>
                            <el-table-column prop="imp_val" label="importe" align="right"></el-table-column>
                        </el-table>
                    </div>
                    <p></p>
                </el-col>
                <el-col :span="12">
                    <div class="grid-content bg-purple">
                        <p>depositos registrados</p>
                        <el-table v-loading="loading" :data="boucherRequest" style="width: 100%">
                            <el-table-column prop="fecha" label="fecha"></el-table-column>
                            <el-table-column prop="boucher" label="no. de deposito"></el-table-column>
                            <el-table-column prop="imp_bou" label="importe" align="right"></el-table-column>
                            <el-table-column align="right">
                                <template slot-scope="scope">
                                    <el-button @click="initGetDigitalBoucher(scope.$index, scope.row)" type="primary"
                                        size="mini" plain>Ver
                                        deposito
                                    </el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                    <p></p>
                    <div class="grid-content bg-purple">
                        <p>depositos encontrados en la cuenta unica</p>
                        <el-table v-loading="loading" :data="extractRequest" style="width: 100%">
                            <el-table-column prop="fecha" label="fecha"></el-table-column>
                            <el-table-column prop="id_doc" label="no. documento"></el-table-column>
                            <el-table-column prop="imp_doc" label="importe" align="right"></el-table-column>
                            <el-table-column align="right">
                                <template slot-scope="scope">
                                    <el-button @click="initDeleteBoucher(scope.$index, scope.row)" type="danger" size="mini"
                                        plain>quitar
                                    </el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                        <p></p>
                        <el-button @click="initVerifyRequestManually()" type="info" size="mini">Agregar Manualmente
                        </el-button>
                        <el-button @click="initVerifyRequestSaleInLine()" type="success" size="mini" plain>Verificar
                        </el-button>
                        <el-button @click="storeObservationRequestSale()" type="warning" size="mini" plain>Observar
                        </el-button>
                        <el-button @click="test()" type="danger" size="mini" plain>Anular
                        </el-button>
                    </div>
                </el-col>
            </el-row>
        </el-card>
        <!-- Form Add Document and Containers -->
        <el-dialog title="detalle del documento" :visible.sync="dialogFormVisible">
            <el-table :data="allExtractRequest" height="250" style="width: 100%" border>
                <el-table-column prop="fecha" label="fecha"></el-table-column>
                <el-table-column prop="id_doc" label="no. de deposito">
                    <template slot-scope="scope">
                        <el-tag size="success" type="info">{{ scope.row.id_doc }}</el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="imp_doc" label="importe"></el-table-column>
                <el-table-column prop="detalle" label="glosa"></el-table-column>
                <el-table-column align="right-center" label="operaciones" width="150">
                    <template slot-scope="scope">
                        <el-button :disabled="scope.row.guardado === true" type="danger" plain size="mini"
                            @click="initAddBoucherManually(scope.$index, scope.row)">agregar</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <span slot="footer" class="dialog-footer">
                <el-button type="danger" size="small" plain @click="dialogFormVisible = false">Cerrar</el-button>
            </span>
        </el-dialog>
        <!-- Form Add Document to Archive-->

    </div>
</template>
  
<script>
export default {
    name: "",
    data() {
        return {
            client: this.$store.state.user,
            id: this.$route.params.id,
            request: this.$route.params.request,
            loading: false,
            dialogFormVisible: false,    //para el dialogo
            dataRequest: {},
            dataDetailRequest: [],
            boucherRequest: [],
            extractRequest: [],
            allExtractRequest: [],
        };
    },
    mounted() {
        this.getDataRequestById();
    },
    methods: {
        test() {
            alert("bienvenido al modulo");
        },

        //  * T4. Obtener el detalle de una solicitud utilizando su id.
        //  * {boucher: boucher de la solicitud }
        async getDataRequestById() {
            var app = this;
            console.log(app.request);
            try {
                let response = await axios.post("/api/getDataRequestById/", {
                    id: app.request,
                });
                app.loading = false;
                console.log(response);
                app.dataRequest = response.data.data[0];
                app.dataDetailRequest = response.data.detail;
                app.boucherRequest = response.data.boucher;
                app.extractRequest = response.data.extract;
                app.allExtractRequest = response.data.all;
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        initVerifyRequestManually() {
            this.dialogFormVisible = true;   //para el dialogo
        },
        initAddBoucherManually(idx, row) {
            this.dialogFormVisible = false;
            let variable = this.document;
            this.extractRequest.push(row);
        },
        initDeleteBoucher(idx, row) {
            this.extractRequest.splice(idx, 1);
            console.log(this.extractRequest);
        },

        //  *  D2. Guardar los boucher generados por cada solicitud
        //  * {boucher: imagen del boucher }
        //  * {request: informacion del boucher }

        storeVerifyRequestSale() {
            var app = this;
        },
        storeObservationRequestSale() {
            var app = this;
        },

        //  * T40 trae el documento digitalizado
        async initGetDigitalBoucher(idx, row) {
            console.log(row);
            let app = this;
            axios({
                url: "/api/getDigitalBoucher/",
                params: {
                    id: row.id,
                    year: app.client.gestion,
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

.el-input.is-disabled .el-input__inner {
    background-color: #123456 !important;
    border-color: #123456 !important;
    color: #123456 !important;
    cursor: not-allowed;
}
</style>
  