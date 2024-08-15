<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>Transacciones por valor</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <div class="grid-content bg-purple" style="margin-top: 15px">
                <el-form :model="data" label-width="220px" size="small">
                    <el-form-item label="concepto">
                        <el-select v-model="data.concepto" value-key="descr" size="small"
                            placeholder="seleccione el valorado a consultar" @change="OnchangeDataValues">
                            <el-option v-for="item in dataValues" :key="item.id" :label="item.concepto"
                                :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="codigo valorado" disabled>
                        <el-input v-model="data.codigo_asociado" disabled></el-input>
                    </el-form-item>
                    <el-form-item label="descripcion del valorado" disabled>
                        <el-input v-model="data.des_codigo" disabled></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button size="small" type="primary" @click.prevent="getGroupValueTransactionsByCode()"
                            icon="el-icon-search">Buscar pagos</el-button>
                    </el-form-item>
                </el-form>
            </div>
            <br />
            <el-row :gutter="50">
                <el-col :span="24">
                    <div class="grid-content bg-purple">
                        <el-table :data="dataValueTransactions" border style="width: 100%" size="small">
                            <el-table-column prop="ci_per" label="numero de carnet" width="100">
                            </el-table-column>
                            <el-table-column prop="des_per" label="apellidos y nombres" width="250">
                            </el-table-column>
                            <el-table-column prop="can_val" label="coutas canceladas" align="right">
                            </el-table-column>
                            <el-table-column prop="pre_uni" label="precio unitario" align="right">
                            </el-table-column>
                            <el-table-column prop="imp_val" label="importe" align="right">
                            </el-table-column>
                            <el-table-column align="right">
                                <template slot-scope="scope">
                                    <el-button @click="getSingleValueTransactionsByCode(scope.$index, scope.row)"
                                        type="warning" size="mini">ver transacciones
                                    </el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </el-col>
            </el-row>
            <div style="margin-top: 15px">
                <el-button size="small" type="primary" icon="el-icon-printer" @click="initGetValueTransactionsReport"
                    plain>
                    imprimir los movimientos</el-button>
            </div>
        </el-card>
        <el-dialog title="Datos generales" :visible.sync="dialogTableVisible">
            <el-form :model="dataClient" label-width="220px" size="small">
                <el-form-item label="apellidos y nombres" disabled>
                    <el-input v-model="dataClient.ci_per" disabled></el-input>
                </el-form-item>
                <el-form-item label="carnet de identidad" disabled>
                    <el-input v-model="dataClient.des_per" disabled></el-input>
                </el-form-item>
            </el-form>
            <el-table :data="dataSingleValueTransactions">
                <el-table-column property="fec_tra" label="fecha" width="150"></el-table-column>
                <el-table-column property="can_val" label="cantidad" width="150"></el-table-column>
                <el-table-column property="pre_uni" label="precio unitario" width="200"></el-table-column>
                <el-table-column property="imp_val" label="importe"></el-table-column>
                <el-table-column property="obs" label="papeleta"></el-table-column>
            </el-table>
        </el-dialog>
    </div>
</template>

<script>
export default {
    name: "",
    data() {
        return {
            user: this.$store.state.user,
            dataValueTransactions: [],
            dataSingleValueTransactions: [],
            dataValues: [],
            data: {},
            dialogTableVisible: false,
            dataClient: {},
        };
    },
    mounted() {
        let app = this;
        console.log(app.user);
        app.GetUserValues();
    },
    methods: {
        test() {
            alert("test");
        },

        //  * TE4. Obtiene la lista de valores asignados a un usuario
        async GetUserValues() {
            var app = this;
            try {
                let response = await axios.post("/api/getUserValues", {
                    user: app.user,
                });
                app.dataValues = response.data;
                console.log(app.dataValues)
            } catch (error) {
                alert("error al buscar");
            }
        },

        //* actualizar un componente al hacer la seleccion nueva *//
        OnchangeDataValues(idx) {
            console.log(this.data);
            console.log(idx);
            let resultado = this.dataValues.find(tipo => tipo.id == idx);
            this.data.codigo_asociado = resultado.codigo_asociado;
            this.data.des_codigo = resultado.des_codigo;
            console.log(resultado);
            console.log(this.data);
        },

        //  * TE5. Obtiene las cuotas realizadas para un valorado
        async getGroupValueTransactionsByCode() {
            var app = this;
            try {
                let response = await axios.post("/api/getGroupValueTransactionsByCode", {
                    data: app.data,
                });
                this.dataValueTransactions = response.data;
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true
                });
            }
        },

        //  * TE6. Obtiene las cuotas realizadas para un valorado detallado por persona
        async getSingleValueTransactionsByCode(idx, row) {
            var app = this;
            this.dataClient = row;
            try {
                let response = await axios.post("/api/getSingleValueTransactionsByCode", {
                    data: app.data,
                    ci_per: row.ci_per,
                });
                app.dialogTableVisible = true;
                this.dataSingleValueTransactions = response.data;
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true
                });
            }
        },


        //  * TE3. Imprime el resumen
        initGetValueTransactionsReport() {
            let app = this;
            console.log(app.dataSaleDay);
            axios({
                url: "/api/getValueTransactionsReport/",
                params: {
                    codigo: app.dataValue.cod_val,
                    inicial: app.dataRange.initial,
                    final: app.dataRange.final,
                    usuario: app.user.usuario,
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

.el-form .el-select {
    width: 100%;
}
</style>