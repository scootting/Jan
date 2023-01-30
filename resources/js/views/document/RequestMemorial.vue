<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>lista de solicitudes de memoriales universitarios</span>
            </div>
            <br />
            <div>
                <el-table v-loading="loading" :data="dataRequestsMemorial" style="width: 100%">
                    <el-table-column label="Fecha" width="150">
                        <template slot-scope="scope">
                            <i class="el-icon-time"></i>
                            <span style="margin-left: 10px">{{ scope.row.fec_tra }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column label="numero" width="100">
                        <template slot-scope="scope">
                            <div slot="reference" class="name-wrapper">
                                <el-tag size="medium">{{ scope.row.idc }}</el-tag>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="solicitante" width="250">
                        <template slot-scope="scope">
                            <span>{{ scope.row.des_per }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column label="solicitud" width="350">
                        <template slot-scope="scope">
                            <span>solicitud de memorial para {{ scope.row.des_tipo }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column label="estado" width="80">
                        <template slot-scope="scope">
                            <div slot="estado" class="name-wrapper">
                                <el-tag size="medium">{{ scope.row.estado }}</el-tag>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column align="right" width="250">
                        <template slot-scope="scope">
                            <el-button @click="initVerifyRequestMemorial(scope.$index, scope.row)" type="warning" plain
                                size="mini">
                                verificar la solicitud</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
                    :current-page="pagination.current_page" :total="pagination.total" @current-change="getRequestsMemorial">
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
            dataRequestsMemorial: [],
            pagination: {
                page: 1,
            },
        };
    },
    mounted() {
        let app = this;
        this.getRequestsMemorial(app.pagination.page);
    },
    methods: {
        test() {
            alert("en proceso de desarrollo");
        },

        //  * M1. Obtener la lista de memoriales para su verificacion 
        async getRequestsMemorial(page) {
            let app = this;
            try {
                let response = await axios.post("/api/getRequestsMemorial", {
                    year: app.user.gestion,
                    page: page,
                });
                app.loading = false;
                app.dataRequestsMemorial = Object.values(response.data.data);
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
        //  * M3. Imprimir la solicitud de elaboracion de memorial universitario              
        initReportRequestMemorial(idx, row) {
            let app = this;
            console.log(app.dataSaleDay);
            axios({
                url: "/api/reportRequestMemorial/",
                params: {
                    voucher: app.voucher,
                    gestion: app.dataSaleDay.gestion,
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

        },
        //  * M2. Obtener la lista de memoriales para su verificacion 
        initVerifyRequestMemorial(index, row) {
            console.log(index, row);
            let id = row.id;
            this.$router.push({
                name: "requestmemorialdetail",
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
  