<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>regularizacion de la deuda contraida con la institucion</span>
                <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
            </div>
            <div>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <div class="grid-content bg-purple">
                            <p>datos del documento de deuda</p>
                            <el-form ref="form" :model="debts" label-width="120px" size="small">
                                <el-form-item label="numero" prop="numero">
                                    <el-input v-model="debts.idc" disabled></el-input>
                                </el-form-item>
                                <el-form-item label="fecha" prop="fecha">
                                    <el-input v-model="debts.fecha" disabled></el-input>
                                </el-form-item>
                                <el-form-item label="carnet" prop="ci_per">
                                    <el-input v-model="debts.ci_per" disabled></el-input>
                                </el-form-item>
                                <el-form-item label="apellidos y nombres" prop="des_per">
                                    <el-input v-model="debts.des_per" disabled></el-input>
                                </el-form-item>
                                <el-form-item label="detalle" prop="detalle">
                                    <el-input type="textarea" autosize placeholder="Ingrese una referencia"
                                        v-model="debts.detalle">
                                    </el-input>
                                </el-form-item>
                            </el-form>
                        </div>
                    </el-col>
                    <el-col :span="12">
                        <div class="grid-content bg-purple">
                            <p>datos del documento de regularizacion</p>
                            <el-form ref="form" :model="pays" label-width="120px" size="small">
                                <el-form-item label="responsable" prop="responsable">
                                    <el-input placeholder="" v-model="manager.details" class="input-with-select">
                                        <el-button slot="append" icon="el-icon-search"
                                            @click="initSearchManager">BUSCAR</el-button>
                                    </el-input>
                                </el-form-item>
                                <el-form-item label="autoriza" prop="mae">
                                    <el-input placeholder="" v-model="director.details" class="input-with-select">
                                        <el-button slot="append" icon="el-icon-search"
                                            @click="initSearchDirector">BUSCAR</el-button>
                                    </el-input>
                                </el-form-item>
                                <el-form-item label="fecha" prop="fecha">
                                    <el-date-picker type="date" placeholder="seleccione una fecha"
                                        v-model="pays.fecha" style="width: 100%"></el-date-picker>
                                </el-form-item>
                                <el-form-item label="referencia" prop="des_ref">
                                    <el-input type="textarea" autosize placeholder="Ingrese las referencia a documentos y cartas que autorizan la regularizacion"
                                        v-model="pays.des_ref">
                                    </el-input>
                                </el-form-item>
                                <el-form-item label="detalle" prop="des_reg">
                                    <el-input type="textarea" autosize placeholder="Ingrese el detalle y forma que se realiza la regularizacion"
                                        v-model="pays.des_reg">
                                    </el-input>
                                </el-form-item>
                            </el-form>
                        </div>
                    </el-col>
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
            id: '',                         //id de la deuda
            debts:{},                       //deudas            
            user: this.$store.state.user,   //usuario
            pays: {},                       //pagos
            manager: {},                //responsable (director de carrera, jefe de division)
            director: {},                //responsable (director de carrera, jefe de division)
            prg: {},                    //categoria programatica

            isVisible: false,           //componente campo visible
            tag: '',                    //componente que informacion desea traer
            flag: '',                   //deudor, responsable, categoria programatica

        };
    },
    mounted() {
        let app = this;
        app.id = app.$route.params.id;
        this.getDebtsById();
    },
    methods: {
        //  * SO3. Obtiene la informacion necesario del recurso solicitado por su id
        async getDebtsById() {
            let app = this;
            try {
                let response = await axios.post("/api/getDebtsById", {
                    id: app.id,
                });
                app.debts = response.data[0];
                console.log(app.debts);
            } catch (error) {
                console.log(error);
            }
        },

        initSearchPrg() {
            this.isVisible = true;
            this.tag = 'categoria';
            this.flag = 'categoria';
        },

        initSearchManager() {
            this.isVisible = true;
            this.tag = 'persona';
            this.flag = 'responsable';
        },
        initSearchDirector() {
            this.isVisible = true;
            this.tag = 'persona';
            this.flag = 'director';
        },

        updateIsVisible(visible, data) {
            this.isVisible = visible;
            this.data = data;
            console.log(this.isVisible + " " + this.data);
            switch (this.flag) {
                case 'categoria':
                    this.prg = data;
                    break;
                case 'director':
                    this.director = data;
                    break;
                case 'responsable':
                    this.manager = data;
                    break;
                default:
                    break;
            }
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
  