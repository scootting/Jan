<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>deudores de la presente gestion</span>
                <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
                    @click="initAddDebtorDocument">registrar nueva deuda</el-button>
            </div>
            <br />
            <div style="margin-top: 15px">
                <el-input placeholder="INSERTE UNA DESCRIPCION" v-model="writtenTextParameter"
                    class="input-with-select">
                    <el-button slot="append" icon="el-icon-search" @click="getDebtorsDocument(1)"></el-button>
                </el-input>
            </div>
            <div>
                <el-table v-loading="loading" :data="dataDebtors" style="width: 100%" size="medium">
                    <el-table-column prop="fecha" label="fecha" width="90"></el-table-column>
                    <el-table-column prop="idc" label="no." width="90" align="center">
                        <template slot-scope="scope">
                            <el-tag size="medium">{{ scope.row.idc }}</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column prop="unidad" label="unidad" width="250"></el-table-column>
                    <el-table-column prop="ci_per" label="carnet" width="130" align="center">
                        <template slot-scope="scope">
                            <el-tag size="medium">{{
                        scope.row.ci_per
                    }}</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column prop="des_per" label="apellidos y nombres" width="250"></el-table-column>
                    <el-table-column prop="detalle" label="detalle" width="400"></el-table-column>
                    <el-table-column width="100">
                        <template slot-scope="scope">
                            <div v-if="scope.row.estado2 !== 'Regularizado'">
                                <el-tag type="danger" effect="dark">{{ scope.row.estado2 }}</el-tag>
                            </div>
                            <div v-else>
                                <el-tag type="success" effect="dark">{{ scope.row.estado2 }}</el-tag>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column fixed="right" width="220">
                        <template slot-scope="scope">
                            <el-button @click="initEditDocument(scope.$index, scope.row)" type="primary"
                                :disabled="scope.row.estado2 === 'Regularizado'" size="small">Editar</el-button>
                            <el-button @click="initRegularizeDocument(scope.$index, scope.row)" type="success"
                                :disabled="scope.row.estado2 === 'Regularizado'" size="small">Regularizar</el-button>
                        </template>
                    </el-table-column>
                    <!--
                    <el-table-column fixed="right" width="120">
                        <template slot-scope="scope">
                        </template>
                    </el-table-column>
P                    -->
                </el-table>
                <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
                    :current-page="pagination.current_page" :total="pagination.total"
                    @current-change="getDebtorsDocumentByYear"></el-pagination>
            </div>
        </el-card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: this.$store.state.user,
            writtenTextParameter: "",
            dataDebtors: [],
            pagination: {
                page: 1,
            },
            loading: true,
        };
    },
    mounted() {
        this.getDebtorsDocumentByYear(1);
    },
    methods: {

        async getDebtorsDocumentByYear(page) {
            this.loading = true;
            let app = this;
            try {
                let response = await axios.post("/api/getDebtorsDocumentByYear", {
                    description: app.writtenTextParameter,
                    page: page,
                    year: app.user.gestion,
                });
                app.dataDebtors = Object.values(response.data.data);
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

        initAddDebtorDocument() {
            this.$router.push({
                name: "adddebts",
            });
        },

        initEditDocument(idx, row) {
            this.$router.push({
                name: "editdebts",
                params: {
                    id: row.id_documento,
                },
            });
        },
        initRegularizeDocument(idx, row) {
            console.log(row);
            this.$router.push({
                name: "regularizedebts",
                params: {
                    id: row.id_concepto,
                },
            });
        },
        initinitVerifyDocument(idx, row) {

        }
    },
};
</script>

<style scoped>
.el-input .el-select {
    width: 180px;
}
</style>