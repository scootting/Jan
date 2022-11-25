<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>verificacion de la solicitud</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <el-row :gutter="20">
                <el-col :span="12">
                    <div class="grid-content bg-purple">
                        <p>datos de la solicitud</p>
                        <el-form ref="form" :model="this.dataRequest" label-width="200px" size="mini">
                            <el-form-item label="tag">
                                <el-input v-model="dataRequest.idc" disabled></el-input>
                            </el-form-item>
                            <el-form-item label="fecha">
                                <el-input v-model="dataRequest.fecha" disabled></el-input>
                            </el-form-item>
                            <el-form-item label="detalle">
                                <el-input v-model="dataRequest.des_per" disabled></el-input>
                            </el-form-item>
                            <el-form-item label="importe">
                                <el-input v-model="dataRequest.importe" disabled></el-input>
                            </el-form-item>
                            <el-form-item label="estado">
                                <el-input v-model="dataRequest.estado" disabled></el-input>
                            </el-form-item>
                        </el-form>
                    </div>
                    <p></p>
                    <div class="grid-content bg-purple">
                        <p>conceptos registrados</p>
                        <el-table v-loading="loading" :data="dataDetailRequest" style="width: 100%">
                            <el-table-column prop="cod_val" label="cod"></el-table-column>
                            <el-table-column prop="des_val" label="descripcion"></el-table-column>
                            <el-table-column prop="can_val" label="cant" align="right"></el-table-column>
                            <el-table-column prop="imp_val" label="importe" align="right"></el-table-column>
                        </el-table>
                    </div>
                    <p></p>
                </el-col>
                <el-col :span="12">
                    <div class="grid-content bg-purple">
                        <p>depositos registrados</p>
                        <el-table v-loading="loading" :data="boucherRequest" style="width: 100%">
                            <el-table-column prop="fecha" label="fecha"></el-table-column>
                            <el-table-column prop="boucher" label="no. documento"></el-table-column>
                            <el-table-column prop="imp_bou" label="importe" align="right"></el-table-column>
                        </el-table>
                    </div>
                    <p></p>
                    <div class="grid-content bg-purple">
                        <p>depositos encontrados en la cuenta unica</p>
                        <el-table v-loading="loading" :data="boucherRequest" style="width: 100%">
                            <el-table-column prop="fecha" label="fecha"></el-table-column>
                            <el-table-column prop="boucher" label="no. documento"></el-table-column>
                            <el-table-column prop="imp_bou" label="importe" align="right"></el-table-column>
                        </el-table>
                        <p></p>
                        <el-button @click="initVerifyRequestSaleInLine()" type="success" size="mini" plain>Verificar
                        </el-button>
                        <el-button @click="storeObservationRequestSale()" type="warning" size="mini" plain>Observar
                        </el-button>
                        <el-button @click="test()" type="danger" size="mini" plain>Anular
                        </el-button>
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
            client: this.$store.state.user,
            id: this.$route.params.id,
            request: this.$route.params.request,
            loading:false,
            dataRequest: {},
            dataDetailRequest: [],
            boucherRequest: [],
        };
    },
    mounted() {
        this.getDataRequestById();
    },
    methods: {
        test() {
            alert("bienvenido al modulo");
        },

        //  * T4. Obtener el detalle de una solicitud utilizando su id.
        //  * {boucher: boucher de la solicitud }
        async getDataRequestById() {
            var app = this;
            console.log(app.request);
            try {
                let response = await axios.post("/api/getDataRequestById/", {
                    id: app.request,
                });
                app.loading = false;
                console.log(response);
                app.dataRequest = response.data.data[0];
                app.dataDetailRequest = response.data.detail;
                app.boucherRequest = response.data.boucher;
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        //  *  D2. Guardar los boucher generados por cada solicitud
        //  * {boucher: imagen del boucher }
        //  * {request: informacion del boucher }
        storeVerifyRequestSale() {
            var app = this;
        },
        storeObservationRequestSale() {
            var app = this;
        },
    },
};
</script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
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
  