<template>

    <div id="app">
        <v-app style="width: 15rem; height: 5rem;">
            <v-content>
                <v-container>
                    <v-layout wrap>

                        <div class="container">
                            <button @click="consultarTodo()" type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalShow">
                                Ver usuarios registrados
                            </button>


                            <!-- Modal lista usuarios-->
                            <div class="modal" id="modalShow" style="border-radius: 20px; margin: 15px;">
                                <div class="modal-dialog modal-dialog-centered modal-lg ">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title" id="exampleModalLongTitle">Usuarios registrados</h1>


                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <form @submit.prevent="buscarUsuario()" class="form-inline shadow-lg"
                                                  style="margin:5px;">


                                                <h5>Filtrar lista de usuarios:</h5>

                                                <div class="col" style="margin:5px;">
                                                    <v-select
                                                        :items="items"
                                                        v-model="item"
                                                        label="Seleccione una opción"
                                                    ></v-select>
                                                </div>

                                                <div class="col" style="margin:5px;">
                                                    <v-text-field
                                                        v-model="buscar"
                                                        :label="item"
                                                        required
                                                    ></v-text-field>
                                                </div>


                                                <button type="submit" class="btn btn-primary"
                                                        style="margin:5px; font-size: 11px;"> Buscar
                                                </button>
                                            </form>

                                            <spinner-component v-if="loading"></spinner-component>

                                            <table
                                                class="shadow-lg table table-striped table-bordered table-hover responsive"
                                                style="font-size: 12px;">
                                                <thead>
                                                <tr class="bg-secondary text-white">
                                                    <th scope="col">Nombre</th>
                                                    <th scope="col">Apellido</th>
                                                    <th scope="col">Usuario</th>
                                                    <th scope="col">Correo</th>
                                                    <th scope="col">Contraseña</th>
                                                    <th scope="col">Despacho</th>
                                                    <th scope="col">Tipo</th>
                                                    <th scope="col">Editar</th>
                                                    <th scope="col">Eliminar</th>
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
                                                        {{item.email}}

                                                    </td>
                                                    <td>

                                                        <v-card style="padding: 6px;"
                                                                class="mx-auto text-white text-center"
                                                                color="grey darken-1">
                                                            <i class="material-icons" style="font-size: 18px;">visibility_off</i>
                                                        </v-card>

                                                    </td>
                                                    <td>
                                                        {{item.despacho}}

                                                    </td>
                                                    <td>
                                                        {{item.tipo}}

                                                    </td>
                                                    <td>
                                                        <v-card @click="modalupdate(item)" style="padding: 6px;"
                                                                class="mx-auto text-center text-white"
                                                                color="yellow darken-4">
                                                            <i class="material-icons"
                                                               style="font-size: 18px;">mode_edit</i>
                                                        </v-card>

                                                    </td>
                                                    <td>
                                                        <v-card @click="borrar(item.id,index)" style="padding: 6px;"
                                                                class="mx-auto text-center text-white"
                                                                color="red darken-1">
                                                            <i class="material-icons" style="font-size: 18px;">delete_forever</i>
                                                        </v-card>
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal" id="modalUpdate">
                                <div class="modal-dialog modal-dialog-centered modal-lg ">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title">Editar usuario</h1>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form @submit.prevent="update()">
                                                <table
                                                    class="shadow-lg table table-bordered responsive">
                                                    <thead>
                                                    <tr class="bg-secondary text-white">
                                                        <th scope="col">Nombre</th>
                                                        <th scope="col">Apellido</th>
                                                        <th scope="col">Usuario</th>
                                                        <th scope="col">Correo</th>
                                                        <th scope="col">Contraseña</th>
                                                        <th scope="col">Despacho</th>
                                                        <th scope="col">Tipo</th>
                                                        <th scope="col">Editar</th>
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    <tr>

                                                        <td>

                                                            <input v-model="usuario.name"
                                                                   type="text"
                                                                   class="form-control input-sm"
                                                                   placeholder="Ingrese el nombre del usuario">


                                                        </td>
                                                        <td>

                                                            <input v-model="usuario.last_name"
                                                                   type="text"
                                                                   class="form-control input-sm"
                                                                   placeholder="Ingrese el apellido del usuario">


                                                        </td>

                                                        <td>
                                                            <input v-model="usuario.username"
                                                                   type="text"
                                                                   class="form-control input-sm"
                                                                   placeholder="Ingrese el usuario">
                                                        </td>

                                                        <td>
                                                            <input v-model="usuario.email"
                                                                   type="text"
                                                                   class="form-control input-sm"
                                                                   placeholder="Ingrese el correo electronico">
                                                        </td>

                                                        <td>
                                                            <input style="font-size: 10px;" v-model="usuario.password"
                                                                   type="text"
                                                                   class="form-control input-sm"
                                                                   placeholder="Solo en caso de cambiar">
                                                        </td>


                                                        <td>
                                                            <v-select
                                                                style="font-size: 12px;" class="form-control-sm"
                                                                :items="despachos"
                                                                label="Despacho"
                                                                v-model="usuario.despacho"
                                                            ></v-select>
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
                                                                <button type="submit">
                                                                    <i class="material-icons"
                                                                       style="font-size: 12px;">mode_edit</i>
                                                                </button>
                                                            </v-card>


                                                        </td>


                                                    </tr>
                                                    </tbody>

                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </v-layout>

                </v-container>
            </v-content>
        </v-app>
    </div>

</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                items: [
                    'Nombre del usuario', 'Despacho', 'Usuario'
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
                tipos: ['Superadministrador', 'Administrador', 'Supervisor', 'Gestor'],
            }
        },
        created() {

        },
        methods: {
            consultarTodo() {
                this.loading = true;
                axios.get('/usuario')
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
                if (this.item === "Despacho") this.opcion = 2;
                if (this.item === "Usuario") this.opcion = 3;
                axios.get('/usuario',
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
                this.usuario.email = item.email;
                this.usuario.despacho = item.despacho;
                this.usuario.tipo = item.tipo;
                $('#modalShow').modal('hide');
                $('#modalUpdate').modal('show');
            },
            update() {
                if (!this.validarUsername()) {
                    const params = {
                        name: this.usuario.name,
                        last_name: this.usuario.last_name,
                        username: this.usuario.username,
                        password: this.usuario.password,
                        email: this.usuario.email,
                        despacho: this.usuario.despacho,
                        tipo: this.usuario.tipo,
                    };
                    axios.put(`/usuario/${this.usuario.id}`, params)
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

            borrar(id, index) {

                swal({
                    title: "¿Estás seguro de que quieres eliminar?",
                    text: "¡Una vez eliminado, no se puede recuperar!",
                    icon: "warning",
                    buttons: {
                        cancel: 'Cancelar',
                        confirm: 'Si, eliminar',
                    },

                })
                    .then((loading) => {
                        if (loading) {

                            axios.delete(`/usuario/${id}`)
                                .then(() => {
                                    this.usuarios.splice(index, 1);
                                });

                            swal("¡Listo! Usuario eliminado con exito", {
                                icon: "success",
                            });

                        } else {
                            swal("¡El usuario sigue!", {
                                button: "Esta bien",
                            });
                        }
                    });


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
