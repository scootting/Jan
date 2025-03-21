<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>Nuevo Documento</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <el-row :gutter="20">
                <el-col :span="18">
                    <div class="grid-content bg-purple">
                        <el-form :model="assignment" label-width="320px" size="small">
                            <el-form-item label="fecha del registro">
                                <el-date-picker type="date" v-model="assignment.fecha"
                                    placeholder="seleccione una fecha" style="width: 100%" format="yyyy/MM/dd"
                                    value-format="yyyy-MM-dd"></el-date-picker>
                            </el-form-item>
                            <el-form-item label="tipo de documento">
                                <el-select v-model="assignment.des_tipo" value-key="descr" size="small"
                                    placeholder="seleccione el tipo de documento" @change="OnchangeTypesDocument">
                                    <el-option v-for="item in typesAssignments" :key="item.cod_tipo"
                                        :label="item.des_tipo" :value="item.id">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="unidad academica o administrativa">
                                <el-select v-model="assignment.des_prg" value-key="descr" size="small"
                                    placeholder="seleccione la unidad academica o administrativa"
                                    @change="OnchangeTypesPrograms">
                                    <el-option v-for="item in typesPrograms" :key="item.id" :label="item.des_prg"
                                        :value="item.id">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="" prop="ci_resp">
                                <el-input placeholder="presione el boton para buscar al responsable"
                                    v-model="assignment.ci_resp" class="input-with-select">
                                    <el-button slot="append" icon="el-icon-search"
                                        @click="initSearchManager">Buscar</el-button>
                                </el-input>
                            </el-form-item>
                            <el-form-item label="responsable" prop="responsable">
                                {{ assignment.des_resp }}
                            </el-form-item>
                            <el-form-item label="cargo">
                                <el-select v-model="assignment.des_cargo" value-key="descr" size="small"
                                    placeholder="seleccione el cargo del responsable" @change="OnchangeTypesPositions">
                                    <el-option v-for="item in typesPositions" :key="item.id" :label="item.des_cargo"
                                        :value="item.id">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" size="small" plain @click="test">Guardar</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                </el-col>
            </el-row>
        </el-card>
        <information :visible="isVisible" :tag='tag' @update-visible="updateIsVisible"></information>
    </div>
</template>

<script>
import information from "../components/Information.vue";

export default {
    name: "",
    components: {
        information,
    },

    data() {
        return {
            user: this.$store.state.user,
            typesAssignments: [],          //diferentes tipos de archivos que pertenecen a un documento
            typesPrograms: [],
            typesPositions: [],
            tag: "",
            flag: "",
            isVisible: false,           //componente campo visible
            marker: "",             //estado para ver si se aniade o se edita
            assignment: {
                id: "",
                fecha: "",
                cod_tipo: "",
                des_tipo: "",
                idx: 0,
                idc: "",
                cod_prg: "",
                ref_prg: "",
                des_prg: "",
                ci_resp: "",
                des_resp: "",
                id_cargo: "",
                des_cargo: "",
                ci_elab: "",
                ci_vobo: "",
                gestion: "2025",
                estado: "Solicitado",
            },
        };
    },
    mounted() {
        let app = this;
        app.assignment.id = app.$route.params.id;
        app.getDataAssignment();
    },
    methods: {
        test() {
            alert("test");
        },

        //  *  AF10. Obtiene la informacion necesaria para crear un documento de entrega
        async getDataAssignment() {
            var app = this;
            try {
                let response = await axios.post("/api/getDataAssignment", {
                    year: app.user.gestion,
                    id: app.id,
                });
                app.typesAssignments = response.data.types;          //diferentes tipos de archivos que pertenecen a un documento
                app.typesPrograms = response.data.categoryProgramatics;
                app.typesPositions = response.data.positions;
            } catch (error) {
                this.error = error.response.data;
                alert(error);
            }
        },

        OnchangeTypesDocument(idx) {
            let resultado = this.typesAssignments.find(tipo => tipo.id == idx);
            this.assignment.cod_tipo = resultado.cod_tipo;
            this.assignment.des_tipo = resultado.des_tipo;
            console.log(this.assignment);
        },

        OnchangeTypesPrograms(idx) {
            let resultado = this.typesPrograms.find(tipo => tipo.id == idx);
            this.assignment.cod_prg = resultado.cod_prg;
            this.assignment.ref_prg = resultado.ref_prg;
            this.assignment.des_prg = resultado.des_prg;
            console.log(this.assignment);
        },

        OnchangeTypesPositions(idx) {
            let resultado = this.typesPositions.find(tipo => tipo.id == idx);
            this.assignment.id_cargo = resultado.id;
            this.assignment.des_cargo = resultado.des_cargo;
            console.log(this.assignment);
        },

        initSearchManager() {
            this.isVisible = true;
            this.tag = 'persona';
            this.flag = 'responsable';
        },

        updateIsVisible(visible, data) {
            this.isVisible = visible;
            this.data = data;
            if (data != null) {
                this.assignment.ci_resp = data.id;
                this.assignment.des_resp = data.details;
                console.log(data);
            }
        },

        //  *  AF11. Obtiene la informacion necesaria para crear un documento de entrega
        async initStoreDataAssignment() {
            var app = this;
            try {
                let response = axios
                    .post("/api/storeDataAssignment", {
                        assignment: app.assignment,
                        user: app.user,
                        marker: 'agregar',
                    });
                app.$alert("Se ha registrado correctamente.", 'Gestor de mensajes', {
                    dangerouslyUseHTMLString: true
                });
                // redireccionando a la lista de documentos
                this.$router.push({
                    name: "assignment",
                });
            } catch (error) {
                console.log(error);
            };
        },
    }
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

.el-form .el-select {
    width: 100%;
}
</style>