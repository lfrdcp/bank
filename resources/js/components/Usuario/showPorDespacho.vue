<template>
    <div id="app">
        <v-app>
            <v-content>


                <spinner-component v-if="loading"></spinner-component>

                <v-form v-model="valid" v-if="!loading" @submit.prevent="buscarUsuario()">
                    <v-container>
                        <v-row>
                            <v-col>
                                <v-select
                                    :items="items"
                                    v-model="item"
                                    label="Seleccione una opción"
                                ></v-select>
                            </v-col>

                            <v-col>
                                <v-text-field
                                    v-model="buscar"
                                    :label="item"
                                    :rules="nombre_reglas"
                                ></v-text-field>
                            </v-col>

                            <v-btn
                                type="submit"
                                :disabled="!valid"
                                color="primary"
                                class="mr-4">
                                Buscar
                            </v-btn>


                        </v-row>
                    </v-container>
                </v-form>




                <table v-if="!loading"
                       class="shadow-lg table table-striped table-bordered table-hover responsive"
                       style="font-size: 12px;">
                    <thead>
                    <tr class="bg-secondary text-white">
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Contraseña</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Editar</th>

                    </tr>
                    </thead>
                    <tbody>

                    <tr v-for="(item, index) in usuarios" :key="index">

                        <td>
                            {{item.name}}

                        </td>
                        <td>
                            {{item.last_name}}


                        </td>
                        <td>
                            {{item.username}}

                        </td>

                        <td>

                            <v-card style="padding: 6px;"
                                    class="mx-auto text-white text-center"
                                    color="grey darken-1">
                                <i class="material-icons" style="font-size: 18px;">visibility_off</i>
                            </v-card>

                        </td>

                        <td>
                            {{item.tipo}}

                        </td>
                        <td>
                            <v-btn
                                color="yellow darken-4"
                                dark
                                @click.stop="dialog = true"
                                @click="modalupdate(item)">
                                <i class="material-icons"
                                   style="font-size: 18px;">mode_edit</i>
                            </v-btn>
                        </td>
                    </tr>

                    </tbody>
                </table>

                <v-dialog v-model="dialog" max-width="550">
                    <v-card>
                        <v-toolbar color="yellow darken-4" dark>
                            <v-app-bar-nav-icon><i class="material-icons" style="font-size: 18px;">mode_edit</i></v-app-bar-nav-icon>

                            <v-toolbar-title>
                                Editar usuario: {{usuario.name}}
                            </v-toolbar-title>

                        </v-toolbar>


                        <v-card-text>
                            <form @submit.prevent="update()">
                                <table
                                    class="shadow-lg table table-bordered responsive">
                                    <thead>
                                    <tr class="bg-secondary text-white">
                                        <th scope="col">Contraseña</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Editar</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <tr>
                                        <td>
                                            <input style="font-size: 10px;" v-model="usuario.password"
                                                   type="text"
                                                   class="form-control input-sm"
                                                   placeholder="Solo en caso de cambiar">
                                        </td>
                                        <td>
                                            <v-select
                                                style="font-size: 12px;" class="form-control-sm"
                                                :items="tipos"
                                                label="Tipo"
                                                v-model="usuario.tipo"
                                            ></v-select>
                                        </td>
                                        <td>
                                            <v-card style="padding: 6px;"
                                                    class="mx-auto text-center text-white"
                                                    color="yellow darken-4">
                                                <button type="submit"  @click="dialog = false">
                                                    <i class="material-icons"
                                                       style="font-size: 12px;">mode_edit</i>
                                                </button>
                                            </v-card>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>
                        </v-card-text>

                    </v-card>
                </v-dialog>


            </v-content>
        </v-app>
    </div>

</template>

<script>
    import axios from 'axios';

    export default {
        name: "showPorDespacho",
        data() {
            return {
                items: [
                    'Nombre del usuario', 'Usuario'
                ],
                usuario: {
                    id: '',
                    name: '',
                    last_name: '',
                    username: '',
                    password: '',
                    email: '',
                    despacho: '',
                    tipo: '',
                },
                opcion: '',
                buscar: '',
                item: '',
                loading: true,
                usuarios: [],
                despachos: [],
                tipos: ['Administrador', 'Supervisor', 'Gestor'],
                nombre_reglas: [v => !!v || 'Se necesita poner un usuario a buscar'],
                valid: true,
                dialog: false,
            }
        },
        created() {
            this.consultarTodo();
        },
        methods: {
            consultarTodo() {
                this.loading = true;
                axios.get('/gestionar_usuarios')
                    .then(res => {
                        this.loading = false;
                        this.usuarios = res.data.usuarios;
                        this.despachos = res.data.despachos;
                        if (this.despachos.length === 0) {
                            swal("La base de datos no tiene despachos registrados", {
                                icon: "info",
                                button: "Entendido",
                            });
                        }

                        if (this.usuarios.length === 0) {
                            swal("La base de datos no tiene usuarios registrados", {
                                icon: "info",
                                button: "Entendido",
                            });
                        }
                    })
            },
            buscarUsuario() {
                this.loading = true;
                this.usuarios = [];
                if (this.item === "Nombre del usuario") this.opcion = 1;
                if (this.item === "Usuario") this.opcion = 2;
                axios.get('/gestionar_usuarios',
                    {
                        params: {
                            buscar: this.buscar,
                            opcion: this.opcion
                        }
                    })
                    .then(res => {
                        this.loading = false;
                        this.usuarios = res.data.usuarios;
                        this.despachos = res.data.despachos;
                        if (this.despachos.length === 0) {
                            swal("La base de datos no tiene despachos registrados", {
                                icon: "info",
                                button: "Entendido",
                            });
                        }

                        if (this.usuarios.length === 0) {
                            swal("No se encontro nada", {
                                icon: "info",
                                button: "Entendido",
                            });
                        }
                    })
            },
            modalupdate(item) {
                this.usuario.id = item.id;
                this.usuario.name = item.name;
                this.usuario.last_name = item.last_name;
                this.usuario.username = item.username;
                this.usuario.tipo = item.tipo;
            },
            update() {
                if (!this.validarUsername()) {
                    const params = {
                        name: this.usuario.name,
                        last_name: this.usuario.last_name,
                        username: this.usuario.username,
                        password: this.usuario.password,
                        tipo: this.usuario.tipo,
                    };
                    axios.put(`/gestionar_usuarios/${this.usuario.id}`, params)
                        .then(res => {
                            console.log(res.data)
                        });
                    $('#editarDespacho').modal('hide');
                    swal("¡Listo!", "Se edito el usuario", "success");
                    $('#modalUpdate').modal('hide');
                    this.consultarTodo();
                    $('#modalShow').modal('show');
                } else {
                    swal("¡Oh!", "Al parecer el usuario que deseas ingresar ya existe", "info");
                }

            },
            validarUsername() {
                const index = this.usuarios.findIndex(item => item.id === this.usuario.id);
                var igual = false;
                var i;
                for (i = 0; i < this.usuarios.length; i++) {
                    if (i !== index) {
                        if (this.usuario.username === this.usuarios[i].username) {
                            igual = true;
                            break;
                        }
                    }
                }
                return igual;
            },

        }
    }
</script>

<style>
    .modal-body {
        overflow-x: auto;
    }

    .modal-lg {
        max-width: 95%;
    }

    .v-select input {
        font-size: 12px;
    }


</style>
