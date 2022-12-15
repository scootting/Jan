<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>material</span>
            </div>
            <div style="margin-top: 15px">
                <!--
          <el-input placeholder="INSERTE UNA DESCRIPCION" v-model="writtenTextParameter" class="input-with-select">
            <el-button slot="append" icon="el-icon-search" @click="getMaterials(1)"></el-button>
          </el-input>

            -->
            </div>
            <br />
            <div style="margin-top: 15px margin-left: 145px">
                <el-table v-loading="loading" :data="dataMovement" height="550" style="width: 100%">
                    <el-table-column prop="fec_tra" label="fecha" width="120">
                        <template slot-scope="scope">
                            <el-tag>{{ scope.row.fec_tra }}</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column prop="codigo" label="recepcion" width="120"></el-table-column>
                    <el-table-column prop="cod_prg" label="programa" width="120"></el-table-column>
                    <el-table-column prop="des_prg" label="descripcion" width="320">
                        <template slot-scope="scope">
                            <el-tag>{{ scope.row.des_prg }}</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column label="Cantidad" align="center">
                        <el-table-column prop="ing_can_mat" label="Entradas" width="120" align="right">
                        </el-table-column>
                        <el-table-column prop="ped_can_mat" label="Salidas" width="120" align="right">
                        </el-table-column>
                        <el-table-column prop="sal_can_mat" label="Saldos" width="120" align="right">
                        </el-table-column>
                    </el-table-column>
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
            mat_cod: this.$route.params.id,
            dataMovement: [],
        };
    },
    mounted() {
        this.getMovementOfMaterial();
    },
    methods: {
        test() {
            console.log("hola mundo");

        },
        //  * M2. Obtiene la lista de movimientos del material
        async getMovementOfMaterial() {
            this.loading = true;
            let app = this;
            try {
                let response = await axios.post("/api/getMovementOfMaterial", {
                    codigo: app.mat_cod,
                    gestion: app.user.gestion,
                });
                app.dataMovement = Object.values(response.data);
                app.loading = false;
                console.log(response);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
    },
};
</script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
  
<style scoped>

</style>
  