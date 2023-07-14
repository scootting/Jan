<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>documentos registrados</span>
                <el-button style="text-align: right; float: right" size="small" type="primary" plain
                    @click="table = true">Abrir reservaciones</el-button>
            </div>
            <el-row :gutter="20">
                <el-col :span="6">
                    <div class="grid-content bg-purple">
                        <span>reserva</span>
                        <el-table v-loading="loading" :data="reservations" style="width: 100%" size="small">
                            <el-table-column prop="id_doc" label="codigo" width="100" align="center">
                                <template slot-scope="scope">
                                    <el-tag type="success" size="medium">{{ scope.row.id_doc }}</el-tag>
                                </template>
                            </el-table-column>
                            <!--
                            <el-table-column>
                                <template slot-scope="scope">
                                    <el-button @click="initSeeBookingDocument(scope.$index, scope.row)" type="warning"
                                        size="mini" plain>ver</el-button>
                                </template>
                            </el-table-column>

                            -->
                            <el-table-column>
                                <template slot-scope="scope">
                                    <el-button @click="initRemoveBooking(scope.$index, scope.row)" type="danger" size="mini"
                                        plain>quitar</el-button>
                                </template>
                            </el-table-column>
                        </el-table>

                        <br>
                        <el-button type="primary" size="small" plain @click="initStoreBookingDocument">Guardar
                            solicitud</el-button>
                    </div>
                </el-col>
                <el-col :span="18">
                    <div class="grid-content bg-purple">
                        <span>busqueda</span>
                        <div style="margin-top: 15px;">
                            <el-input placeholder="INSERTE UNA DESCRIPCION" v-model="writtenTextParameter"
                                class="input-with-select">
                                <el-button slot="append" icon="el-icon-search" @click="getDataDocument(1)"></el-button>
                            </el-input>
                        </div>
                        <br />
                        <div>
                            <el-table v-loading="loading" :data="documents" style="width: 100%" size="small">
                                <el-table-column prop="id_doc" label="codigo" width="100" align="center">
                                    <template slot-scope="scope">
                                        <el-tag type="danger" size="medium">{{ scope.row.id_doc }}</el-tag>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="descr" label="tipo" width="250" align="center">
                                    <template slot-scope="scope">
                                        <el-tag size="medium">{{ scope.row.descr }}</el-tag>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="gestion" label="gestion" width="90" align="center"></el-table-column>
                                <el-table-column prop="fecha" label="fecha" width="90" align="center"></el-table-column>
                                <el-table-column prop="numeral" label="numero" width="100" align="center"></el-table-column>
                                <el-table-column prop="glosa" label="glosa" width="350"></el-table-column>
                                <el-table-column align="right" fixed="right" width="100">
                                    <template slot-scope="scope">
                                        <div v-if="scope.row.reserva !== 'Prestado'">
                                            <el-button @click="initAddBooking(scope.$index, scope.row)" type="primary"
                                                :disabled="scope.row.reserva === 'Prestado'" size="mini"
                                                plain>Agregar</el-button>
                                        </div>
                                        <div v-else>
                                            <el-tag type="danger" effect="dark">Prestado</el-tag>
                                        </div>
                                        <el-button :disabled="scope.row.digital === 0" type="primary" plain size="mini"
                                            @click="getDigitalDocumentById(scope.$index, scope.row)">ver archivo</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
                                :current-page="pagination.current_page" :total="pagination.total"
                                @current-change="getDataDocument"></el-pagination>
                        </div>
                    </div>
                </el-col>
            </el-row>
        </el-card>
        <!--
        <el-drawer title="Documentos reservados" :visible.sync="table" direction="rtl" size="35%">
            <el-table :data="reservations">
                <el-table-column prop="id_doc" label="codigo" width="180" align="center">
                    <template slot-scope="scope">
                        <el-tag type="danger" size="medium">{{ scope.row.id_doc }}</el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="descr" label="codigo" width="280" align="center">
                    <template slot-scope="scope">
                        <el-tag size="medium">{{ scope.row.descr }}</el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="numeral" label="numero"></el-table-column>
                <el-table-column align="right">
                    <template slot-scope="scope">
                        <el-button @click="initAddReservation(scope.$index, scope.row)" type="danger" size="mini"
                            plain>Quitar</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </el-drawer>
        -->
    </div>
</template>
  
<script>
export default {
    data() {
        return {
            table: false,
            user: this.$store.state.user,
            documents: [],
            reservations: [],
            pagination: {
                page: 1,
            },
            writtenTextParameter: "",
            loading: true,
        };
    },
    mounted() {
        console.log(this.user);
        this.getDataDocument();
    },
    methods: {
        //  * A16. Busca los documentos para la solicitud de prestamo
        async getDataDocument(page) {
            this.loading = true;
            let app = this;
            try {
                let response = await axios.post("/api/getDataDocument", {
                    description: app.writtenTextParameter,
                    page: page,
                });
                app.documents = Object.values(response.data.data);
                app.pagination = response.data;
                console.log(this.documents);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
            app.loading = false;
        },
        initAddBooking(idx, row) {

            let resultado = this.reservations.find(item => item.id == row.id);
            console.log(resultado + "   " + row.id);
            if (resultado === undefined) {
                this.reservations.push(row);
            }
        },
        initRemoveBooking(idx, row) {
            this.reservations.splice(idx, 1);
        },
        initSeeBookingDocument(idx, row) {

        },
        //  * A18. Guardar la reserva de documentos por el usuario
        initStoreBookingDocument() {
            console.log(this.reservations);
            var app = this;
            try {
                let response = axios
                    .post("/api/storeBookingDocument", {
                        user: app.user,
                        reservations: app.reservations,
                        marker: "registrar",
                    });
                console.log(response);
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
        getDigitalDocumentById(idx, row) {
            let app = this;
            console.log(row);
            axios({
                url: "/api/getDigitalDocumentById2/",
                params: {
                    id: row.digital,
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
    },
};
</script>
  
<style scoped>
.el-input .el-select {
    width: 180px;
}
</style>
  