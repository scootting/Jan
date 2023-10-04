<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>conteo de votos para la mesa {{ id }}</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <el-row>
                <p>
                    <el-button type="primary" size="small" @click="storeVotesForCandidates">Guardar Resultados</el-button>
                </p>
            </el-row>
            <el-row :gutter="20">
                <el-col :span="24">
                    <div class="grid-content bg-purple">
                        <el-row>
                            <el-col :span="4" v-for="(item, index) in dataCandidates" :key="index">
                                <div style="align-items: center;">
                                    <el-card>
                                        <h4>{{ item.sigla }}</h4>
                                        <h1>{{ item.detalle }}</h1>
                                        <el-input placeholder="Please input" v-model="item.votos"></el-input>
                                    </el-card>
                                </div>
                            </el-col>
                        </el-row>
                    </div>
                </el-col>
            </el-row>
        </el-card>
    </div>
</template>
  
<script>
export default {
    name: "",
    data() {
        return {
            id: 0,
            loading: true,
            dataCandidates: [],
        };
    },
    mounted() {
        this.id = this.$route.params.id;
        this.getInformationCandidates();
    },
    methods: {
        test() {
            alert("Hola, como estas hoy?");
        },

        async getInformationCandidates() {
            var app = this;
            try {
                let response = await axios.post("/api/getInformationCandidates", {
                    id_tablet: app.id,
                    id_election: '2',
                });
                app.loading = false;
                app.dataCandidates = response.data.dataCandidates;
                console.log(app.dataCandidates);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
        async storeVotesForCandidates() {
            var app = this;
            console.log(this.dataCandidates);
            try {
                let response = await axios.post("/api/storeVotesForCandidates", {
                    id_mesa: app.id,
                    dataCandidates: app.dataCandidates,
                });
                console.log(response);
                app.$alert("Se ha registrado correctamente los votos", 'Gestor de mensajes', {
                    dangerouslyUseHTMLString: true
                });
                this.$router.push({
                    name: "tablets",
                });

            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        }
    },
};
</script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.bg-purple .el-card .el-button {
    font-size: 5rem;
    display: block;
    margin: 0 auto;
}

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
</style>
  