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
                                <el-form-item label="unidad" prop="des_prg">
                                    <el-input placeholder="" v-model="dni" class="input-with-select">
                                        <el-button slot="append" icon="el-icon-search"
                                            @click="initSearchPrg">BUSCAR</el-button>
                                    </el-input>
                                </el-form-item>
                                <el-form-item label="responsable" prop="resp">
                                    <el-input placeholder="" v-model="manager.des_per" class="input-with-select">
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
                            <el-table :data="persons" style="width: 100%" size="small">
                                <el-table-column prop="nro_dip" label="dni"></el-table-column>
                                <el-table-column prop="des_per" label="descripcion"></el-table-column>
                                <el-table-column align="right">
                                    <template slot-scope="scope">
                                        <el-button @click="initRemovePerson(scope.$index, scope.row)" type="primary"
                                            plain size="small">Quitar</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <p></p>
                            <el-button @click="initSearchPerson" type="primary" size="small" plain>Buscar
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
                            <el-button @click="storeNewDebtorDocument()" type="danger" size="small" plain>Guardar
                            </el-button>
                        </div>
                    </el-col>
                </el-row>
            </div>
            <person :centerDialogVisible="isVisible" :dataPerson="person" @update-visible="getDataModal"
                @update-info="storeNewPerson"></person>
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
import person from "../components/Person.vue";
export default {
    name: "",
    components: {
        person,
    },
    data() {
        return {
            dni: '',
            flag: '',//ver si la persona a buscar es deudor o responsable
            dialogFormVisible: false,//hace visible el formulario de cosas adeudadas
            isVisible: false,//hace visible el formulario de categorias programaticas
            persons: [],//deudores
            debts: [],//adeudos
            manager: {},//resposable de la deuda
            person: {},
            debtorDocument: {
                id: "",
                referencia: "",
                fecha: "",
                unidad: "",
            },//documento de deuda
            debt: {
                tipo: "fisica",
                cant: 0,
                desc: "",
            }//deuda
        };
    },
    mounted() { },
    methods: {
        //  * S2. Guardar la informacion de un nuevo documento de deuda.
        async storeNewDebtorDocument() {
            var app = this;
            var newPerson = app.person;
            try {
                let response = await axios.post("/api/person", {
                    persona: newPerson,
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

        },

        /*funciones del componente para buscar personas */
        /*persona: deudor */
        initSearchPerson() {
            this.isVisible = true;
            this.flag = 'Deudor'
        },
        /*persona: responsable */
        initSearchManager() {
            this.isVisible = true;
            this.flag = 'Responsable'
        },

        getDataModal(isVisible) {
            this.isVisible = isVisible;
        },

        /* agrega a la lista de deudores */
        storeNewPerson(isVisible, person) {
            this.isVisible = isVisible;
            this.isPerson = person;
            if (this.flag == 'Deudor')
                this.persons.push(person);
            else
                this.manager = person;
        },
        /* remueve de la lista de deudores */
        initRemovePerson(index, row) {
            this.persons.splice(index, 1);
        },

        /* agrega una cosa que se adeuda */
        storeNewDebt() {
            let temp = this.debt;            
            this.debts.push(temp);
            this.debt.tipo = "fisica";
            this.debt.cant = 0;
            this.debt.desc = "";
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
  