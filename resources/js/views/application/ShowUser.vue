<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>perfil</span>
                <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
            </div>
            <div>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <div class="grid-content bg-purple">
                            <p>datos personales</p>
                            <el-form ref="form" :model="this.user" label-width="200px" size="mini">
                                <el-form-item label="carnet de identidad">
                                    <el-input v-model="user.nodip" disabled></el-input>
                                </el-form-item>
                                <el-form-item label="apellido apellidos y nombres">
                                    <el-input v-model="user.descripcion" disabled></el-input>
                                </el-form-item>
                                <el-form-item label="usuario">
                                    <el-input v-model="user.usuario" disabled></el-input>
                                </el-form-item>
                                <el-form-item label="cargo">
                                    <el-input v-model="user.des_car" disabled></el-input>
                                </el-form-item>
                                <el-form-item label="contraseña">
                                    <el-input v-model="user.clave" type="password" disabled></el-input>
                                </el-form-item>
                                <el-form-item>
                                    <el-button type="primary" size="small" @click="updatePersonPassword()">cambiar
                                        contraseña</el-button>
                                </el-form-item>
                            </el-form>
                        </div>
                    </el-col>
                    <el-col :span="12">
                        <div class="grid-content bg-purple">
                            <p>perfiles asignados</p>
                        </div>
                    </el-col>
                </el-row>
            </div>
        </el-card>
        <!--                  
      *** - dialogo usando ElementUI - ***
    -->
        <el-dialog title="contraseña" :visible.sync="centerDialogVisible" width="40%" center>
            <el-form :model="this.pass" label-width="210px" size="mini" :rules="rules" ref="pass">
                <el-form-item label="contraseña actual" prop="actual">
                    <el-input v-model="pass.actual" type="password"></el-input>
                </el-form-item>
                <el-form-item label="nueva contraseña" prop="nuevo">
                    <el-input v-model="pass.nuevo" type="password"></el-input>
                </el-form-item>
                <el-form-item label="confirme la nueva contraseña" prop="confirma">
                    <el-input v-model="pass.confirma" type="password"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button @click="centerDialogVisible = false" size="small">Cancelar</el-button>
                    <el-button type="primary" size="small" @click="changePassword('pass')">cambiar
                        contraseña</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>
    </div>
</template>

<script>
export default {
    name: "Profile",
    data() {
        var validateLast = (rule, value, callback) => {
            if (value === '') {
                callback(new Error('por favor ingrese la anterior contraseña'));
            } else {
                setTimeout(() => {
                    if (this.pass.actual !== '') {
                        this.$refs.pass.validateField('actual');
                    }
                    callback();
                }, 1000);
            }
        };
        var validatePass = (rule, value, callback) => {
            if (value === '') {
                callback(new Error('por favor ingrese la nueva contraseña'));
            } else {
                setTimeout(() => {
                    if (this.pass.confirma !== '') {
                        this.$refs.pass.validateField('confirma');
                    }
                    callback();
                }, 1000);
            }
        };
        var validatePass2 = (rule, value, callback) => {
            if (value === '') {
                callback(new Error('por favor ingrese la nueva contraseña'));
            } else if (value !== this.pass.nuevo) {
                callback(new Error('no coinciden las contraseñas!!!'));
            } else {
                setTimeout(() => {
                    callback();
                }, 1000);
            }
        };
        return {
            messages: {},
            user: this.$store.state.user,
            centerDialogVisible: false,
            pass: {
                actual: '',
                nuevo: '',
                confirma: '',
            },
            rules: {
                actual: [
                    { validator: validateLast, trigger: 'blur' }
                ],
                nuevo: [
                    { validator: validatePass, trigger: 'blur' }
                ],
                confirma: [
                    { validator: validatePass2, trigger: 'blur' }
                ],
            }
        };
    },
    mounted() { },
    methods: {
        test() {
            alert("bienvenido al modulo");
        },
        updatePersonPassword() {
            this.centerDialogVisible = true;
        },
        async changePassword(formName) {
            const isValid = await new Promise((resolve) => {
                this.$refs[formName].validate((valid) => {
                    resolve(valid);
                });
            });
            if (isValid) {
                var app = this;
                try {
                    let response = await axios.post("/api/changePassword", {
                        id: app.user.nodip,
                        actual: app.pass.actual,
                        nuevo: app.pass.nuevo,
                        confirma: app.pass.confirma,
                    });
                    app.$alert("se ha actualizado la contraseña correctamente!!! ... la siguiente vez que inicie sesion debera usar su nueva contraseña", "Gestor de mensajes", {
                        dangerouslyUseHTMLString: true,
                    });
                    this.centerDialogVisible = false;
                } catch (error) {
                    this.error = error.response.data;
                    app.$alert(this.error.message, "Gestor de errores", {
                        dangerouslyUseHTMLString: true,
                    });
                }
            } else {
                console.warn('Validation failed');
                return false;
            }
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
    padding-left: 40px;
    padding-right: 40px;
}

.el-dialog>.el-form {
    padding-left: 30px;
    padding-right: 30px;
}
</style>
