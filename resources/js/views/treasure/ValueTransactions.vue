<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>Transacciones por valor</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <div class="grid-content bg-purple" style="margin-top: 15px">
                <el-form :model="dataValue" label-width="220px" size="small">
                    <el-form-item label="buscar">
                        <el-input placeholder="inserte el codigo del valor" v-model="dataValue.cod_val"
                            class="input-with-select">
                            <el-button slot="append" icon="el-icon-search" @click="GetValueById()"></el-button>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="descripcion" disabled>
                        <el-input v-model="dataValue.des_val" disabled></el-input>
                    </el-form-item>
                </el-form>
                <el-form :model="dataRange" label-width="220px" size="small">
                    <el-form-item label="rango inicial">
                        <el-date-picker type="date" v-model="dataRange.initial" placeholder="seleccione una fecha"
                            style="width: 100%" format="yyyy/MM/dd" value-format="yyyy-MM-dd"></el-date-picker>
                    </el-form-item>
                    <el-form-item label="rango final">
                        <el-date-picker type="date" v-model="dataRange.final" placeholder="seleccione una fecha"
                            style="width: 100%" format="yyyy/MM/dd" value-format="yyyy-MM-dd"></el-date-picker>
                    </el-form-item>
                    <el-form-item>
                        <el-button size="small" type="primary" @click.prevent="GetValueTransactionsById()"
                            icon="el-icon-search" plain>Buscar movimientos</el-button>
                    </el-form-item>
                </el-form>
            </div>
            <br />
            <el-row :gutter="50">
                <el-col :span="24">
                    <div class="grid-content bg-purple">
                        <el-table :data="dataValueTransactions" border style="width: 100%" size="small">
                            <el-table-column prop="fec_tra" label="fecha" width="100">
                            </el-table-column>
                            <el-table-column prop="id_dia" label="id" width="100">
                            </el-table-column>
                            <el-table-column prop="des_tip" label="tipo" width="150">
                            </el-table-column>
                            <el-table-column prop="nro_com" label="papeleta" width="100">
                            </el-table-column>
                            <el-table-column prop="obs" label="descripcion" width="550">
                            </el-table-column>
                            <el-table-column prop="can_val" label="cantidad" align="right">
                            </el-table-column>
                            <el-table-column prop="pre_uni" label="pre. uni." align="right">
                            </el-table-column>
                            <el-table-column prop="imp_val" label="Precio" align="right">
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
    </div>
</template>

<script>
export default {
    name: "",
    data() {
        return {
            user: this.$store.state.user,
            dataValueTransactions: [],
            dataValue: {},
            dataRange: {},
        };
    },
    mounted() {
        let app = this;
    },
    methods: {
        test() {
            alert("test");
        },

        //  * TE1. Obtiene el valor 
        async GetValueById() {
            var app = this;
            try {
                let response = await axios.post("/api/getValueById", {
                    dataValue: app.dataValue,
                });
                app.dataValue = response.data[0];
            } catch (error) {
                alert("error al buscar");
            }
        },

        //  * TE2. Obtiene las transacciones de un valor que se vende de acuerdo a un rango de fechas
        async GetValueTransactionsById() {
            var app = this;
            try {
                let response = await axios.post("/api/getValueTransactionsById", {
                    dataRange: app.dataRange,
                    dataValue: app.dataValue,
                });
                this.dataValueTransactions = response.data;

            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true
                });

            }
        },

        //  * Imprime el reporte de las transacciones de un valor que se vende de acuerdo a un rango especifico
        initGetValueTransactionsReport() {
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