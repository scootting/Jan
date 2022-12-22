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
                                    <el-input placeholder="Please input" v-model="dni" class="input-with-select">
                                        <el-button slot="append" icon="el-icon-search"
                                            @click="initSearchPerson">BUSCAR</el-button>
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
                            <el-button @click="initSearchPerson" type="success" size="mini" plain>Buscar
                            </el-button>
                        </div>
                        <p></p>
                        <div class="grid-content bg-purple">
                            <p>deudas</p>
                            <el-table :data="debts" style="width: 100%">
                                <el-table-column prop="tipo" label="tipo"></el-table-column>
                                <el-table-column prop="cantidad" label="cantidad"></el-table-column>
                                <el-table-column prop="detalle" label="detalle"></el-table-column>
                            </el-table>
                            <p></p>
                            <el-button @click="storeNewDebtorDocument()" type="success" size="mini" plain>Agregar
                            </el-button>
                            <el-button @click="storeNewDebtorDocument()" type="success" size="mini" plain>Guardar
                            </el-button>
                        </div>
                    </el-col>
                </el-row>
            </div>
            <person :centerDialogVisible="isVisible" :dataPerson="personal" @update-visible="getDataModal"
                @update-info="initGetPerson"></person>
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
            debts: [],
            dni: '',
            isVisible: false,
            persons: [],
            personal: {
            },
            debtorDocument: {
                id: "",
                referencia: "",
                fecha: "",
                unidad: "",
            },
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

        /*funciones del componente personas */
        initSearchPerson() {
            this.isVisible = true;
        },
        getDataModal(isVisible) {
            this.isVisible = isVisible;
        },

        /*remueve de la lista de deudores*/
        initGetPerson(isVisible, personal) {
            this.isVisible = isVisible;
            this.isPerson = personal;
            this.persons.push(personal);
        },
        /*remueve de la lista de deudores*/
        initRemovePerson(index, row) {
            this.persons.splice(index, 1);
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

.el-input.is-disabled .el-input__inner {
    background-color: #123456 !important;
    border-color: #123456 !important;
    color: #123456 !important;
    cursor: not-allowed;
}
</style>
  