
<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>Presupuestos individuales</span>
                <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
                    @click="initAddSingleBudget">nuevo presupuesto individual</el-button>
            </div>
            <!--
            <div style="margin-top: 15px">
                <el-input placeholder="INSERTE UNA DESCRIPCION" v-model="writtenTextParameter" class="input-with-select"
                    @keyup.enter.native="test">
                    <el-button slot="append" icon="el-icon-search" @click="getInventories"></el-button>
                </el-input>
            </div>
            -->
            <br />
            <div>
                <el-table v-loading="loading" :data="data" style="width: 100%">
                    <el-table-column width="75" label="No.">
                        <template slot-scope="scope">
                            <div slot="reference" class="name-wrapper">
                                <el-tag size="medium">{{  scope.row.numeral  }}</el-tag>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="fecha" width="100" label="fecha">
                    </el-table-column>
                    <el-table-column prop="des_cat_prg" width="250" label="programa">
                    </el-table-column>
                    <el-table-column prop="glosa" width="450" label="documento">
                    </el-table-column>
                    <el-table-column width="150" label="estado">
                        <template slot-scope="scope">
                            <div slot="reference" class="name-wrapper">
                                <el-tag size="medium">{{  scope.row.estado  }}</el-tag>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column align="right-center" width="250" label="Operaciones">
                        <template slot-scope="scope">
                            <el-button :disabled="data[scope.$index].verificado == true"
                                @click="editSingle(scope.$index, scope.row)" type="info" plain size="mini">editar
                            </el-button>
                            <el-button :disabled="data[scope.$index].verificado == true"
                                @click="getDetailBySigleBudget(scope.row.numeral)" type="danger" plain size="mini">
                                editar movimientos
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
                    :current-page="pagination.current_page" :total="pagination.total"
                    @current-change="getSinglesBudgest">
                </el-pagination>
            </div>
        </el-card>
    </div>
</template>
  
  <script>
export default {
    name: "Inventarios2",
    data() {
        return {
            //todo lo revisado en variables
            loading: true,
            user: this.$store.state.user,
            data: [],
            pagination: {
                page: 1,
            },

            messages: {},
            verificado: false,
            showReportes: false,
            writtenTextParameter: "",
        };
    },
    mounted() {
        let app = this;
        this.getSinglesBudgest(app.pagination.page);
    },
    methods: {
        //  * PIN1. Obtener una lista de los presupuestos individuales de el recurso utilizado.
        async getSinglesBudgest(page) {
            let app = this;
            try {
                let response = await axios.post("/api/singlebudget", {
                    user: app.user.usuario,
                    year: app.user.gestion,
                    page: page,
                    //descripcion: app.writtenTextParameter.toUpperCase(),
                });
                app.loading = false;
                console.log(response);
                app.data = Object.values(response.data.data);
                app.pagination = response.data;
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        //  * PIN(R1). Ruta para ir al modulo del presupuesto individual.
        getDetailBySigleBudget(numeral) {
            this.$router.push({
                name: "singlebudgetdetail",
                params: {
                    id_single: numeral,
                },
            });
        },

        //  * PIN(R2). Ruta para ir al modulo para un nuevo presupuesto individual.
        initAddSingleBudget() {
            this.$router.push({
                name: "addsinglebudget",
            });
        },



        //  * 2. Imprimir el reporte del inventario general o detallado de el recurso utilizado.
        initPrintDetailsInventory(row) {
            console.log(row);
            axios({
                url: "/api/inventoryReport/",
                params: {
                    office: row.ofc_cod,
                    document: row.no_cod,
                    year: row.gestion,
                    report: "InventoryDetails",
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
        initEditPerson(index, row) {
            console.log(index, row);
            let personal = row.personal;
            this.$router.push({
                name: "editperson",
                params: {
                    id: personal.trim(),
                },
            });
        },
        */
    },
};
</script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
  
  <style scoped>
  </style>