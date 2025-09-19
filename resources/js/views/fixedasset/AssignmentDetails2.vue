<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span><strong>documento de revaluo de activos fijos no. {{ dataAssignment.idc }}</strong></span>
                <el-button style="float: right; margin: -8px 5px" size="small" type="info"
                    @click="initVerifyDataAssignmentDetails()">verificar documento</el-button>
            </div>
            <div>
                <el-row :gutter="20">
                    <el-col :span="24">
                        <div class="grid-content bg-purple">
                            <p>datos generales del documento</p>
                            <el-form ref="form" :model="dataAssignment" label-width="380px" size="small">
                                <el-form-item label="fecha">
                                    {{ dataAssignment.fecha }}
                                </el-form-item>
                                <el-form-item label="unidad academica o administrativa">
                                    {{ dataAssignment.des_prg }}
                                </el-form-item>
                                <el-form-item label="responsable">
                                    {{ dataAssignment.des_resp }}
                                </el-form-item>
                                <el-form-item label="estado">
                                    <el-tag effect="dark" type="info" size="medium">{{ dataAssignment.estado }}</el-tag>
                                </el-form-item>
                            </el-form>
                        </div>
                    </el-col>
                </el-row>
            </div>
            <el-col :span="24">
                <div class="grid-content bg-purple">
                    <p>activos fijos verificados</p>
                    <el-table :data="dataAssignmentsDetails" style="width: 100%" size="small"
                        @selection-change="handleSelectionChange">
                        <el-table-column type="selection"> </el-table-column>
                        <el-table-column prop="codigo" label="codigo" width="120"></el-table-column>
                        <el-table-column prop="fecha_adquisicion" label="fecha"
                            width="100"></el-table-column>
                        <el-table-column prop="descripcion" label="descripcion" width="620"></el-table-column>
                        <el-table-column prop="des_contable" label="partida" width="120"></el-table-column>
                        <el-table-column prop="importe" label="importe" width="120"></el-table-column>
                        <el-table-column align="right" width="150">
                            <template slot-scope="scope">
                                <el-button @click="initPrintDataFixedAsset(scope.$index, scope.row)" type="primary"
                                    size="mini">Imprimir</el-button>
                            </template>
                        </el-table-column>
                        <!--
-->
                    </el-table>
                    <p></p>
                    <el-button @click="initPrintDataAssignmentDetails" type="info" size="small"
                        :disabled="verifyVisible == false">imprimir
                        activos
                    </el-button>
                </div>
            </el-col>
        </el-card>

    </div>
</template>

<script>
import information from "../components/Information.vue";

export default {
    data() {
        return {
            verifyVisible: true,
            user: this.$store.state.user,
            id: this.$route.params.id,
            dataAssignment: {},             // informacion del documento de entrega, revaluo, o transferencia
            dataAssignmentsDetails: [],     // lista de los bienes de uso, de un documento de entrega verificado.
        };
    },
    mounted() {
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
                app.dataAssignment = response.data.dataAssignment[0];
                app.dataAssignmentsDetails = response.data.dataAssignmentsDetails;
                console.log(response);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        initVerifyDataAssignmentDetails() {
            var app = this;
            try {
                let response = axios
                    .post("/api/verifyDataAssignmentDetails", {
                        assignment: app.dataAssignment,
                        user: app.user,
                        marker: 'verificar',
                    });
                app.$alert("Se ha registrado correctamente los cambios al documento", 'Gestor de mensajes', {
                    dangerouslyUseHTMLString: true
                });
                this.getDataAssignmentDetails();
            } catch (error) {
                app.$alert("Se ha registrado correctamente la informacion", 'Gestor de mensajes', {
                    dangerouslyUseHTMLString: true
                });
            };
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
                        reporte: "FixedAssetsQr_F1",
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