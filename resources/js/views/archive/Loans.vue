<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>documentos registrados</span>
                <el-button style="text-align: right; float: right" size="small" type="primary" plain
                    @click="table = true">Abrir reservaciones</el-button>
                <!--
            <el-button type="text" @click="table = true">Abrir reservacion de documentos</el-button>
            <p>{{ reservations }}</p>
                -->
            </div>
            <div style="margin-top: 15px;">
                <el-input placeholder="INSERTE UNA DESCRIPCION" v-model="writtenTextParameter" class="input-with-select">
                    <el-button slot="append" icon="el-icon-search" @click="getDataDocument(1)"></el-button>
                </el-input>
            </div>
            <br />
            <div>
                <el-table v-loading="loading" :data="documents" style="width: 100%" size="medium">
                    <el-table-column prop="id_doc" label="codigo" width="90" align="center">
                        <template slot-scope="scope">
                            <el-tag type="danger" size="medium">{{ scope.row.id_doc }}</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column prop="descr" label="codigo" width="280" align="center">
                        <template slot-scope="scope">
                            <el-tag size="medium">{{ scope.row.descr }}</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column prop="gestion" label="gestion" width="90" align="center"></el-table-column>
                    <el-table-column prop="fecha" label="fecha" width="90" align="center"></el-table-column>
                    <el-table-column prop="numeral" label="numero" width="90" align="center"></el-table-column>
                    <el-table-column prop="glosa" label="glosa" width="450"></el-table-column>
                    <el-table-column align="right">
                        <template slot-scope="scope">
                            <el-button @click="initAddReservation(scope.$index, scope.row)" type="warning" size="mini"
                                plain>Agregar
                                documento</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
                    :current-page="pagination.current_page" :total="pagination.total"
                    @current-change="getDataDocument"></el-pagination>
            </div>
        </el-card>
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
    </div>
</template>
  
<script>
export default {
    data() {
        return {
            table: false,
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
                console.log(response);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
            app.loading = false;
        },
        initAddReservation(idx, row) {
            this.reservations.push(row);
        },
        cancelForm() {
            this.loading = false;
            this.dialog = false;
            clearTimeout(this.timer);
        }

    },
};
</script>
  
<style scoped>
.el-input .el-select {
    width: 180px;
}
</style>
  