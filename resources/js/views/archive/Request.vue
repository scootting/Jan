<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>solicitudes de reserva</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="initGetReportBorrowed()">documentos prestados</el-button>
            </div>
            <el-alert title="importante" type="warning"
                description="las solicitudes de reserva tienen una duracion valida de 30 dias, sino se realizan se anulara la solicitud."
                show-icon>
            </el-alert>
            <br />
            <div>
                <el-table v-loading="loading" :data="bookings" style="width: 100%">
                    <el-table-column prop="fecha" label="fecha" width="150"></el-table-column>
                    <el-table-column label="idc" width="150">
                        <template slot-scope="scope">
                            <div slot="reference" class="name-wrapper">
                                <el-tag size="medium">{{ scope.row.idc }}</el-tag>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="ci_per" label="ci" width="150"></el-table-column>
                    <el-table-column prop="des_per" label="descripcion" width="300"></el-table-column>
                    <el-table-column prop="estado" label="estado" width="150"></el-table-column>
                    <el-table-column align="right" width="320" fixed="right">
                        <template slot-scope="scope">
                            <el-button @click="initEditrequest(scope.$index, scope.row)" type="warning" plain size="mini">
                                ver la solicitud</el-button>
                            <el-button @click="initPrintBooking(scope.$index, scope.row)" type="primary" size="mini"
                                plain>imprimir solicitud</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
                    :current-page="pagination.current_page" :total="pagination.total" @current-change="getRequests">
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
            bookings: [],
            pagination: {
                page: 1,
            },
        };
    },
    mounted() {
        let app = this;
        this.getRequests(app.pagination.page);

    },
    methods: {
        test() {
            alert("en proceso de desarrollo");
        },
        //  * 17. Obtener una lista de las solicitudes de reserva por usuario.
        async getRequests(page) {
            let app = this;
            try {
                let response = await axios.post("/api/getBookingDocument", {
                    user: '',
                    year: app.user.gestion,
                    page: page,
                });
                app.loading = false;
                app.bookings = Object.values(response.data.data);
                app.pagination = response.data;
                console.log(response.data.data);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        //  *  Route. Iniciar una nueva solicitud para la reserva de documentos
        initEditrequest(idx, row) {
            this.$router.push({
                name: "requestdocumentdetails",
                params: {
                    id: row.id,
                },
            });
        },
        initGetReportBorrowed(){
            let app = this;
            axios({
                url: "/api/getReportBorrowed/",
                params: {
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
        initPrintBooking(idx, row) {
            console.log(row.id);
            let app = this;
            axios({
                url: "/api/getReportBooking/",
                params: {
                    id: row.id,
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

        /*
        initPrintRequest(idx, row) { },
        */
    },
};
</script>
  
<style scoped>
.el-input .el-select {
    width: 180px;
}
</style>
  