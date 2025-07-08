<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>nuevo documento de deuda</span>
                <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
            </div>
            <div>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <div class="grid-content bg-purple">
                            <p>datos de la solicitud</p>
                            <el-form ref="form" :model="debtorDocument" label-width="120px" size="small">
                                <el-form-item label="unidad" prop="details">
                                    <el-input placeholder="" v-model="prg.details" class="input-with-select">
                                        <el-button slot="append" icon="el-icon-search"
                                            @click="initSearchPrg">BUSCAR</el-button>
                                    </el-input>
                                </el-form-item>
                                <el-form-item label="responsable" prop="resp">
                                    <el-input placeholder="" v-model="manager.details" class="input-with-select">
                                        <el-button slot="append" icon="el-icon-search"
                                            @click="initSearchManager">BUSCAR</el-button>
                                    </el-input>
                                </el-form-item>
                                <el-form-item label="fecha" prop="fecha">
                                    <el-date-picker type="date" placeholder="seleccione una fecha"
                                        v-model="debtorDocument.fecha" style="width: 100%"></el-date-picker>
                                </el-form-item>
                                <el-form-item label="referencia" prop="referencia">
                                    <el-input type="textarea" autosize placeholder="Ingrese una referencia"
                                        v-model="debtorDocument.detalle">
                                    </el-input>
                                </el-form-item>
                            </el-form>
                        </div>
                        <p></p>
                    </el-col>
                    <el-col :span="12">
                        <div class="grid-content bg-purple">
                            <p>deudores</p>
                            <el-table :data="debtors" style="width: 100%" size="small">
                                <el-table-column prop="nro_dip" label="ci" width="120"></el-table-column>
                                <el-table-column prop="des_per" label="apellidos y nombres" width="220"></el-table-column>
                                <el-table-column align="right">
                                    <template slot-scope="scope">
                                        <el-button @click="initRemoveDebtors(scope.$index, scope.row)" type="primary" plain
                                            size="small">Quitar</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <p></p>
                            <el-button @click="initSearchDebtor" type="primary" size="small" plain>Buscar
                            </el-button>
                        </div>
                    </el-col>
                </el-row>
                <el-row>
                    <el-button @click="storeDebtorDocument" type="primary" size="small">guardar informacion
            </el-button>

                </el-row>
            </div>
            <information :visible="isVisible" :tag='tag' @update-visible="updateIsVisible"></information>
        </el-card>
    </div>
</template>
  
<script>
import information from "../components/Information.vue";

export default {
    components: {
        information,
    },
    data() {
        return {
            user: this.$store.state.user,
            isVisible: false,           //componente campo visible
            tag: '',                    //componente que informacion desea traer
            flag: '',                   //deudor, responsable, categoria programatica
            dialogFormVisible: false,   //hace visible el formulario de cosas adeudadas
            debtors: [],                //deudores
            manager: {},                //responsable (director de carrera, jefe de division)
            prg: {},                    //categoria programatica
            debtorDocument: {},                          //documento de deuda
            numero: 0,
        };
    },
    mounted() {
        console.log(this.user);
    },
    methods: {
        //  * S2. Guardar la informacion de un nuevo documento de deuda.
        async storeDebtorDocument() {
            var app = this;
            try {
                let response = await axios.post("/api/storeDebtorDocument", {
                    usuario: app.user,
                    documento: app.debtorDocument,
                    deudores: app.debtors,
                    responsable: app.manager,
                    programa: app.prg,
                    marker: "registrar",
                });
                app.numero = response.data;
                console.log(response);
                this.$confirm('Cuenta con la documentacion que corresponde a la deuda?', 'Proceso de Verificacion', {
                    confirmButtonText: 'Continuar',
                    cancelButtonText: 'Cancelar',
                    type: 'success'
                }).then(() => {
                    /*pasa directamente al editar*/
                    this.$router.push({
                        name: "editdebts",
                        params: {
                            id: response.data,
                        },
                    });

                }).catch(() => {
                    /*pasa directamente a la lista de deudas*/
                    this.$router.push({ name: "debts" });
                });

            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

        noDebtorDocument() {
            this.$router.push("/api");
        },

        initSearchPrg() {
            this.isVisible = true;
            this.tag = 'categoria';
            this.flag = 'categoria';
        },

        initSearchDebtor() {
            this.isVisible = true;
            this.tag = 'persona';
            this.flag = 'deudor';
        },
        initSearchManager() {
            this.isVisible = true;
            this.tag = 'persona';
            this.flag = 'responsable';
        },

        updateIsVisible(visible, data) {
            this.isVisible = visible;
            this.data = data;
            console.log(this.isVisible + " " + this.data);
            if (data != null) {
                switch (this.flag) {
                    case 'categoria':
                        this.prg = data;
                        break;
                    case 'deudor':
                        this.debtors.push(data);
                        break;
                    case 'responsable':
                        this.manager = data;
                        break;
                    default:
                        break;
                }
            }
        },

        /* remueve de la lista de deudores */
        initRemoveDebtors(index, row) {
            this.debtors.splice(index, 1);
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
  