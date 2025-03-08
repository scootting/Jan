<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>Nuevo Documento</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <el-row :gutter="20">
                <el-col :span="12">
                    <div class="grid-content bg-purple">
                        <el-form :model="assignment" label-width="220px" size="small">
                            <el-form-item label="tipo de documento">
                                <el-select v-model="assignment.descr" value-key="descr" size="small"
                                    placeholder="seleccione el tipo de documento" @change="OnchangeTypeDocument">
                                    <el-option v-for="item in typesAssignments" :key="item.id" :label="item.descr"
                                        :value="item.id">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="Numero del documento">
                                <el-input v-model="assignment.numeral" autocomplete="off"></el-input>
                            </el-form-item>
                            <el-form-item label="fecha del registro">
                                <el-date-picker type="date" v-model="assignment.fecha"
                                    placeholder="seleccione una fecha" style="width: 100%" format="yyyy/MM/dd"
                                    value-format="yyyy-MM-dd"></el-date-picker>
                            </el-form-item>
                            <el-form-item label="glosa o descripcion">
                                <el-input type="textarea" v-model="assignment.glosa" autocomplete="off"></el-input>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" size="small" plain @click="test">Confirmar</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                </el-col>
            </el-row>
        </el-card>
    </div>
</template>

<script>

export default {
    name: "",
    data() {
        return {
            user: this.$store.state.user,
            id: '',
            typesAssignments: [],          //diferentes tipos de archivos que pertenecen a un documento
            typesPrograms: [],
            stateStore: "",             //estado para ver si se aniade o se edita
            assignment: {
                tipo: "",
                des_tipo: "",
                idx: 0,
                idc: "",
                cod_prg: "",
                des_prg: "",
                ci_resp: "",
                ci_elab: "",
                ci_vobo: "",
                gestion: "",
                estado: "Solicitado",
            },
        };
    },
    mounted() {
        let app = this;
        app.id = app.$route.params.id;
        app.getDataAssignment();
    },
    methods: {
        test() {
            alert("test");
        },

        //  * AF1. Obtiene la lista de tipos de documentos para activos fijos

        OnchangeTypeDocument() {

        },
        async getDataAssignment() {
            var app = this;
            try {
                let response = await axios.post("/api/getDataPrograms", {
                    year: app.user.gestion,
                });
                app.typesAssignments = response.data.typesAssignments,          //diferentes tipos de archivos que pertenecen a un documento
                app.typesPrograms = response.data.typesPrograms,

                console.log(app.typesPrograms);
            } catch (error) {
                this.error = error.response.data;
                alert(error);
            }

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