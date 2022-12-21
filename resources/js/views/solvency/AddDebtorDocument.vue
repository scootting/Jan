<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>nuevo documento de deuda</span>
                <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
            </div>
            <div>
                <el-row>
                    <el-col :span="15">
                        <el-tabs v-model="activeName" @tab-click="handleClick">
                            <el-tab-pane label="Informacion general" name="first">
                                <el-form ref="form" :model="person" label-width="260px">
                                    <el-form-item size="small" label="numero de identificacion" prop="personal">
                                        <el-input size="small" v-model="person.nro_dip"></el-input>
                                    </el-form-item>
                                    <el-form-item size="small" label="nombres" prop="nombres">
                                        <el-input size="small" v-model="person.nombres"></el-input>
                                    </el-form-item>
                                    <el-form-item size="small" label="apellido paterno">
                                        <el-input size="small" v-model="person.paterno"></el-input>
                                    </el-form-item>
                                    <el-form-item size="small" label="apellido materno" prop="materno">
                                        <el-input size="small" v-model="person.materno"></el-input>
                                    </el-form-item>
                                    <el-form-item size="small" label="fecha de nacimiento" prop="nacimiento">
                                        <el-date-picker size="small" type="date" placeholder="seleccione una fecha"
                                            v-model="person.fec_nacimiento" style="width: 100%"></el-date-picker>
                                    </el-form-item>
                                    <el-form-item size="small" label="genero">
                                        <el-radio-group v-model="person.id_sexo" size="small">
                                            <el-radio-button label="M"></el-radio-button>
                                            <el-radio-button label="F"></el-radio-button>
                                        </el-radio-group>
                                    </el-form-item>
                                    <el-form-item>
                                        <el-button size="small" type="primary" @click.prevent="storeNewDebtorDocument"
                                            plain>Guardar</el-button>
                                        <el-button size="small" type="danger" @click="noDebtorDocument"
                                            plain>Cancelar</el-button>
                                    </el-form-item>
                                </el-form>
                            </el-tab-pane>
                            <el-tab-pane label="Informacion detallada" name="second">Config</el-tab-pane>
                        </el-tabs>
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
            messages: {},
            activeName: 'first',
            person: {
                nro_dip: "",
                nombres: "",
                paterno: "",
                materno: "",
                fec_nacimiento: "",
                id_sexo: "M",
            },
        };
    },
    mounted() { },
    methods: {
        //  * S2. Guardar la informacion de un nuevo documento de deuda.
        handleClick(tab, event) {
            console.log(tab, event);
        },
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
    },
};
</script>
  <!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.el-card {
    background: #ffffff;
}

.el-form {
    padding-left: 120px;
    padding-right: 120px;
    padding-top: 60px;
}
</style>
  