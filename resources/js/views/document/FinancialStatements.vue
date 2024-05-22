<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>estados financieros</span>
            </div>
            <br />
            <div>
                <el-table v-loading="loading" :data="dataFinancialStatements" style="width: 100%">
                    <el-table-column label="gestion" width="100">
                        <template slot-scope="scope">
                            <div slot="reference" class="name-wrapper">
                                <el-tag size="medium">{{ scope.row.gestion }}</el-tag>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="glosa" width="650">
                        <template slot-scope="scope">
                            <div slot="estado" class="name-wrapper">
                                <el-tag size="medium">{{ scope.row.glosa }}</el-tag>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column align="right" width="250">
                        <template slot-scope="scope">
                            <el-button @click="initFinancialStatementDetails(scope.$index, scope.row)" type="primary"
                                size="mini">
                                verificar documentacion</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
                    :current-page="pagination.current_page" :total="pagination.total" @current-change="getFinancialStatements">
                </el-pagination>
            </div>
        </el-card>
    </div>
</template>
  
<script>
export default {
    name: "lista_de_solicitudes_para_la_venta_en_linea",
    data() {
        return {
            loading: true,
            user: this.$store.state.user,
            dataFinancialStatements: [],
            pagination: {
                page: 1,
            },
        };
    },
    mounted() {
        let app = this;
        this.getFinancialStatements(app.pagination.page);
    },
    methods: {
        test() {
            alert("en proceso de desarrollo");
        },

        //  * EF1. Obtener la lista de estados financieros 
        async getFinancialStatements(page) {
            let app = this;
            try {
                let response = await axios.post("/api/getFinancialStatements", {
                    year: app.user.gestion,
                    page: page,
                });
                app.loading = false;
                app.dataFinancialStatements = Object.values(response.data.data);
                app.pagination = response.data;
                console.log(response.data.data);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
                app.loading = false;
            }
        },
        //  * M2. Obtener la lista de memoriales para su verificacion 
        initFinancialStatementDetails(index, row) {
            console.log(index, row);
            let id = row.id;
            this.$router.push({
                name: "financialstatementdetails",
                params: {
                    id: id,
                },
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
  