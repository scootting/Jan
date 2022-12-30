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
                                <el-form-item label="id" prop="id">
                                    <el-input v-model="debtorDocument.id"></el-input>
                                </el-form-item>
                                <el-form-item label="referencia" prop="referencia">
                                    <el-input type="textarea" autosize placeholder="Ingrese una referencia"
                                        v-model="debtorDocument.referencia">
                                    </el-input>
                                </el-form-item>
                                <el-form-item label="fecha" prop="fecha">
                                    <el-date-picker type="date" placeholder="seleccione una fecha"
                                        v-model="debtorDocument.fecha" style="width: 100%"></el-date-picker>
                                </el-form-item>
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
                            </el-form>
                        </div>
                        <p></p>
                    </el-col>
                    <el-col :span="12">
                        <div class="grid-content bg-purple">
                            <p>deudores</p>
                            <el-table :data="debtors" style="width: 100%" size="small">
                                <el-table-column prop="nro_dip" label="dni"></el-table-column>
                                <el-table-column prop="des_per" label="descripcion"></el-table-column>
                                <el-table-column align="right">
                                    <template slot-scope="scope">
                                        <el-button @click="initRemoveDebtors(scope.$index, scope.row)" type="primary"
                                            plain size="small">Quitar</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <p></p>
                            <el-button @click="initSearchDebtor" type="primary" size="small" plain>Buscar
                            </el-button>
                        </div>
                        <p></p>
                        <div class="grid-content bg-purple">
                            <p>deudas</p>
                            <el-table :data="debts" style="width: 100%" size="small">
                                <el-table-column prop="tipo" label="tipo"></el-table-column>
                                <el-table-column prop="cant" label="cantidad"></el-table-column>
                                <el-table-column prop="desc" label="detalle"></el-table-column>
                                <el-table-column align="right">
                                    <template slot-scope="scope">
                                        <el-button @click="initRemoveDebt(scope.$index, scope.row)" type="primary" plain
                                            size="small">Quitar</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <p></p>
                            <el-button @click="dialogFormVisible = true" type="primary" size="small" plain>Agregar
                            </el-button>
                            <el-button @click="storeDebtorDocument()" type="danger" size="small" plain>Guardar
                            </el-button>
                        </div>
                    </el-col>
                </el-row>
            </div>
            <information :visible="isVisible" :tag='tag' @update-visible="updateIsVisible"></information>
            <!-- componente para agregar deudas -->
            <el-dialog title="agregar deuda" :visible.sync="dialogFormVisible">
                <el-form :model="debt" label-width="150px" size="small">
                    <el-form-item label="deuda">
                        <el-radio-group v-model="debt.tipo" size="small">
                            <el-radio-button label="fisica"></el-radio-button>
                            <el-radio-button label="economica"></el-radio-button>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item label="cantidad">
                        <el-input-number v-model="debt.cant" controls-position="right" :min="1"></el-input-number>
                    </el-form-item>
                    <el-form-item label="glosa o descripcion">
                        <el-input type="textarea" v-model="debt.desc"></el-input>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button type="primary" size="small" plain @click="storeNewDebt">agregar</el-button>
                    <el-button type="danger" size="small" plain @click="dialogFormVisible = false">cerrar</el-button>
                </span>
            </el-dialog>
            <!-- componente para agregar deudas -->
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
            debts: [],                  //cosas adeudas
            manager: {},                //responsable (director de carrera, jefe de division)
            prg: {},                    //categoria programatica
            debtorDocument: {
                id: "",
                referencia: "",
                fecha: "",
            },                          //documento de deuda
            debt: { tipo: "fisica", cant: 1, desc: "" }, //deuda
        };
    },
    mounted() { 
        console.log(this.user);
    },
    methods: {
        //  * S2. Guardar la informacion de un nuevo documento de deuda.
        async storeDebtorDocument() {
            var app = this;
            var newPerson = app.person;
            try {
                let response = await axios.post("/api/storeDebtorDocument", {
                    usuario: app.user,
                    documento: app.debtorDocument,
                    deudores: app.debtors,
                    deudas: app.debts,
                    responsable: app.manager,
                    programa: app.prg,
                    marker: "registrar",
                });
                alert("se ha creado el registro de la persona");
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
        },

        /* remueve de la lista de deudores */
        initRemoveDebtors(index, row) {
            this.debtors.splice(index, 1);
        },

        /* agrega una cosa que se adeuda */
        storeNewDebt() {
            let variable = this.debt;
            this.debt = { tipo: "fisica", cant: 1, desc: "" };
            this.debts.push(variable);
        },

        /* quita la cosa que se adeuda */
        initRemoveDebt(index, row) {
            this.dialogFormVisible = false;
            this.debts.splice(index, 1);
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
  