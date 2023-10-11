
<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>lista de mesas habilitadas</span>
                <el-button style="text-align: right; float: right" size="small" icon="el-icon-plus"
                    @click="getGeneralReport2('DOCENTES')">RESUMEN GENERAL DOCENTES</el-button>
                <el-button style="text-align: right; float: right" size="small" icon="el-icon-plus"
                    @click="getGeneralReport2('ESTUDIANTES')">RESUMEN GENERAL ESTUDIANTES</el-button>
                <el-button style="text-align: right; float: right" size="small" icon="el-icon-plus"
                    @click="getGeneralReport">RESUMEN DE VOTACION DE TODAS LAS MESAS</el-button>
            </div>
            <br />
            <div>
                <el-table v-loading="loading" :data="dataTablets" style="width: 100%">
                    <el-table-column width="130" label="estamento">
                        <template slot-scope="scope">
                            <div slot="reference" class="name-wrapper">
                                <h3>{{ scope.row.estamento }}</h3>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column width="190" label="mesa">
                        <template slot-scope="scope">
                            <div slot="reference" class="name-wrapper">
                                <h3> MESA NO. {{ scope.row.numero }}</h3>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column width="190" label="votos contabilizados" align="right">
                        <template slot-scope="scope">
                            <div slot="reference" class="name-wrapper">
                                <h3>{{ scope.row.votos }}</h3>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column align="right-center" width="520" label="Operaciones" fixed="right">
                        <template slot-scope="scope">
                            <el-button @click="setCountVotes(scope.row.id)" type="success" plain size="mini">contabilizar
                                votos
                            </el-button>
                            <el-button @click="getDigitalActOfVotes(scope.row.id)" type="info" plain size="mini">digitalizar
                                acta
                            </el-button>
                            <el-button @click="getReportTablet(scope.row.id)" type="info" plain size="mini">generar reporte
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </el-card>
    </div>
</template>
<script>
export default {
    data() {
        return {
            loading: false,
            user: this.$store.state.user,
            dataTablets: [],
            writtenTextParameter: "",
        };
    },
    mounted() {
        this.getInformationTablets();
    },
    methods: {
        //  * 1. Obtener una lista de inventarios por usuario de el recurso utilizado.
        getInformationTablets() {
            this.loading = true;
            let app = this;
            axios
                .post("/api/getInformationTablets", {
                    year: app.user.gestion,
                    description: app.writtenTextParameter.toUpperCase(),
                })
                .then((response) => {
                    app.loading = false;
                    app.dataTablets = response.data.dataTablets;
                })
                .catch((error) => {
                    this.error = error;
                    this.$notify.error({
                        title: "Error",
                        message: this.error.message,
                    });
                });
        },

        setCountVotes(id) {
            this.$router.push({
                name: "tabletdetails",
                params: {
                    id: id,
                },
            });
        },
        getDigitalActOfVotes(id) {

        },
        getReportTablet(id) {
            var app = this;
            let id_election = 2;
            axios({
                url: "/api/getReportTablet/",
                params: {
                    id_tablet: id,
                    id_election: id_election,
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
        getGeneralReport() {
            var app = this;
            let id_election = 2;
            axios({
                url: "/api/getReportGeneralTablet/",
                params: {
                    id_election: id_election,
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
        getGeneralReport2(stament) {
            var app = this;
            let id_election = 2;
            axios({
                url: "/api/getReportGeneralTablet2/",
                params: {
                    id_election: id_election,
                    stament: stament,
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
        }
    },
};
</script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
  
<style scoped></style>
  