<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>Lista de regularizacion de la gestion</span>
            </div>
            <div style="margin-top: 15px">
                <el-input placeholder="INSERTE UNA DESCRIPCION" v-model="writtenTextParameter" class="input-with-select">
                    <el-button slot="append" icon="el-icon-search" @click="getCreditorsDocument(1)"></el-button>
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
                    <el-table-column prop="des_prg" label="unidad" width="275"></el-table-column>
                    <el-table-column prop="ci_per" label="carnet" width="130" align="center">
                        <template slot-scope="scope">
                            <el-tag size="medium">{{
                                scope.row.ci_per
                            }}</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column prop="des_per" label="apellidos y nombres" width="250"></el-table-column>
                    <el-table-column prop="detalle" label="detalle" width="300"></el-table-column>
                    <el-table-column width="120" fixed="right">
                        <template slot-scope="scope">
                            <div v-if="scope.row.estado2 !== 'Regularizado'">
                                <el-tag type="danger" effect="dark">{{ scope.row.estado2 }}</el-tag>
                            </div>
                            <div v-else>
                                <el-tag type="success" effect="dark">{{ scope.row.estado2 }}</el-tag>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column fixed="right" width="120">
                        <template slot-scope="scope">
                            <el-button @click="initEditDocument(scope.$index, scope.row)" type="primary"
                                size="small">Editar</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
                    :current-page="pagination.current_page" :total="pagination.total"
                    @current-change="getCreditorsDocument"></el-pagination>
            </div>
        </el-card>
    </div>
</template>
  
<script>
export default {
    data() {
        return {
            writtenTextParameter: "",
            dataDebtors: [],
            pagination: {
                page: 1,
            },
            loading: true,
        };
    },
    mounted() {
        this.getCreditorsDocument(1);
    },
    methods: {

        async getCreditorsDocument(page) {
            this.loading = true;
            let app = this;
            try {
                let response = await axios.post("/api/getCreditorsDocument", {
                    description: app.writtenTextParameter,
                    page: page,
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
                name: "editregularize",
                params: {
                    id: row.id_documento,
                },
            });
        },
        initinitVerifyDocument(idx, row){

        }
    },
};
</script>
  
<style scoped>
.el-input .el-select {
    width: 180px;
}
</style>
  