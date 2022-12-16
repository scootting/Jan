<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>Lista de deudores</span>
                <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
                    @click="initAddDebtorDocument">nueva documento de deuda</el-button>
            </div>
            <br />
            <div style="margin-top: 15px">
                <el-input placeholder="INSERTE UNA DESCRIPCION" v-model="writtenTextParameter"
                    class="input-with-select">
                    <el-button slot="append" icon="el-icon-search" @click="getDebtorsDocument"></el-button>
                </el-input>
            </div>
            <div>
                <el-table v-loading="loading" :data="dataDebtors" style="width: 100%">
                    <el-table-column prop="ci_per" label="dni"></el-table-column>
                    <el-table-column prop="des_per" label="descripcion"></el-table-column>
                </el-table>
                <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
                    :current-page="pagination.current_page" :total="pagination.total"
                    @current-change="getDebtorsDocument"></el-pagination>
            </div>
        </el-card>
    </div>
</template>
  
<script>
export default {
    data() {
        return {
            writtenTextParameter: '',
            dataDebtors: [],
            pagination: {
                page: 1,
            },
            loading: true,
        };
    },
    mounted() {
        this.getDebtorsDocument();
    },
    methods: {
        async getDebtorsDocument(page) {
            this.loading = true;
            let app = this;
            try {
                let response = await axios.post("/api/getDebtorsDocument", {
                    description: app.writtenTextParameter,
                    page: page,
                });
                app.dataDebtors = Object.values(response.data.data);
                app.pagination = response.data;
                app.loading = false;
                console.log(response);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        initAddDebtorDocument() {
            this.$router.push({
                name: "addDocumentOfDebtor",
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
  