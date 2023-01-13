<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>nuevo dia de venta de productos</span>
                <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
            </div>
            <div>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <div class="grid-content bg-purple">
                            <p>datos del dia</p>
                            <el-form ref="form" :model="farmSaleDays" label-width="120px" size="small">
                                <el-form-item label="tipo">
                                    <el-radio-group v-model="farmSaleDays.tip_tra" size="small">
                                        <el-radio-button label="1">ventas en efectivo</el-radio-button>
                                        <el-radio-button label="14">ventas al credito</el-radio-button>
                                        <el-radio-button label="0">regularizacion de ventas al creditos</el-radio-button>
                                    </el-radio-group>
                                </el-form-item>
                                <el-form-item label="fecha" prop="fecha">
                                    <el-date-picker type="date" placeholder="seleccione una fecha"
                                        v-model="farmSaleDays.fecha" style="width: 100%"></el-date-picker>
                                </el-form-item>
                                <el-form-item>
                                    <el-button size="small" type="primary" @click.prevent="initStoreFarmSaleDays"
                                        plain>Guardar</el-button>
                                </el-form-item>
                            </el-form>
                        </div>
                        <p></p>
                    </el-col>
                </el-row>
            </div>
        </el-card>
    </div>
</template>
  
<script>

export default {
    data() {
        return {
            user: this.$store.state.user,
            farmSaleDays: { tip_tra: 1, fecha: '' },
        };
    },
    mounted() {
        console.log(this.user);
    },
    methods: {
        //  * G2. Agregar un nuevo dia de venta de los productos de la granja
        async initStoreFarmSaleDays() {
            var app = this;
            try {
                let response = await axios.post("/api/storeFarmSaleDays", {
                    dia: app.farmSaleDays,
                    usuario: app.user,
                    marker: "registrar",
                });
                alert("se ha creado un nuevo dia de venta de productos");
                console.log(response);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        /* agrega una cosa que se adeuda */
        storeNewDebt() {
            let variable = this.debt;
            this.debt = { tipo: "fisica", cant: 1, desc: "" };
            this.debts.push(variable);
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
  