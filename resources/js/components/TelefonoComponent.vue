<template>
    <div id="app">
        <v-app>

            <div class="container" align="left">
                <v-form @submit.prevent="editarTelefono(telefono)" v-if="modoEditar" v-model="valid">
                    <h2>Editar teléfono</h2>

                    <v-text-field
                        class="my-input"
                        v-model="telefono.numero_tel"
                        counter="10"
                        :rules="
                        [v => !!v || 'Necesita ingresar un teléfono',
                        v => (v && v.length > 6) || 'Debe ingresar como minimo 7 números',
                        v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números']"
                        label="Número de teléfono"
                        required
                    ></v-text-field>

                    <v-card style="padding: 6px;"
                            class="mx-auto text-center text-white"
                            color="blue ">
                        <v-btn
                            type="submit"
                            class="mx-auto text-center text-white"
                            color="blue "
                            :disabled="!valid"
                        >
                            <i class="material-icons">done</i>
                        </v-btn>

                    </v-card>


                    <v-card style="padding: 6px;"
                            class="mx-auto text-center text-white"
                            color="red">
                        <v-btn
                            @click="cancelarEdicion"
                            type="submit"
                            class="mx-auto text-center text-white"
                            color="red">
                            <i class="material-icons">cancel</i>
                        </v-btn>

                    </v-card>

                </v-form>

                <v-form @submit.prevent="agregar" v-else v-model="valid">
                    <h2>Agregar teléfono</h2>
                    <v-text-field
                        class="my-input"
                        v-model="telefono.numero_tel"
                        counter="10"
                        :rules="
                        [v => !!v || 'Necesita ingresar un teléfono',
                        v => (v && v.length > 6) || 'Debe ingresar como minimo 7 números',
                        v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números']"
                        label="Número de teléfono"
                        required
                    ></v-text-field>
                    <v-card style="padding: 6px;"
                            class="mx-auto text-center text-white"
                            color="blue ">
                        <v-btn
                            type="submit"
                            :disabled="!valid"
                            class="mx-auto text-center text-white"
                            color="blue "
                        >
                            <i class="material-icons">done</i>
                        </v-btn>

                    </v-card>
                </v-form>
                <hr>
                <h2>Lista de teléfonos:</h2>

                <v-data-table
                    :headers="headers"
                    :items="telefonos"
                    :items-per-page="15"
                    :footer-props="flechas"

                    class="elevation-1"
                >
                    <template v-slot:item.editar="{ item }">

                        <v-btn v-if="tipo_usuario=='Administrador' || tipo_usuario=='Supervisor'"
                            @click="editarFormulario(item)"
                            class="mx-auto text-center text-white"
                            color="orange">
                            <i class="material-icons">mode_edit</i>
                        </v-btn>

                    </template>
                    <template v-slot:item.eliminar="{ item }">

                        <v-btn v-if="tipo_usuario=='Administrador' || tipo_usuario=='Supervisor'"
                            @click="eliminarTelefono(item)"
                            class="mx-auto text-center text-white"
                            color="red">
                            <i class="material-icons">delete_forever</i>
                        </v-btn>

                    </template>
                </v-data-table>

            </div>

        </v-app>
    </div>
</template>

<script>
    import axios from 'axios'
    import swal from 'sweetalert'


    export default {

        props: {
            id_c: String,
            tipo_usuario: String
        },
        data() {
            return {
                id_tel: '',
                nuevo: '',
                valid: true,
                telefonos: [],
                todos: [],
                telefono: {id_cli: this.id_c, id_tel: '', numero_tel: ''},
                modoEditar: false,
                loading: true,
                headers: [
                    {text: 'Número', value: 'numero_tel'},
                    {text: 'Editar', value: 'editar', sortable: false},
                    {text: 'Eliminar', value: 'eliminar', sortable: false},
                ],
                flechas: {
                    showFirstLastPage: true,
                    prevIcon: 'undo',
                    nextIcon: 'redo'
                },

            }
        },
        created() {
            axios.get(`/telefono/${this.id_c}`)
                .then(res => {
                    this.loading = false;
                    this.telefonos = res.data.telefonos;
                    this.todos = res.data.todos;
                    this.id_cliente = this.id_c;
                });
        },

        methods: {
            agregar() {
                this.nuevo = 0;
                if (this.telefono.numero_tel.trim() === '') {
                    swal("Debes completar todos los campos antes de guardar");
                    return;
                }
                if (this.verTelTodos(this.telefono.numero_tel) !== '') {
                    this.nuevo = 1;
                    this.id_tel = this.verTelTodos(this.telefono.numero_tel);
                }


                axios.post('/telefono', {
                    numero_tel: this.telefono.numero_tel,
                    id_cli: this.id_c,
                    nuevo: this.nuevo,
                    id_tel: this.id_tel
                })
                    .then(res => {
                        console.log(res.data)
                    });

                axios.get(`/telefono/${this.id_c}`)
                    .then(res => {
                        this.loading = false;
                        this.telefonos = res.data.telefonos;
                        this.todos = res.data.todos;
                        this.id_cliente = this.id_c;
                    });
                if (this.nuevo === 1) {
                    swal({
                        title: "¡Agregado!",
                        text: "Tambien lo tiene otra persona. La página recargara para actualizar los datos, por favor espere",
                        icon: "success",
                        button: "Entendido",
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 4000);
                } else {
                    swal({
                        title: "¡Agregado!",
                        text: "La página recargara para actualizar los datos, por favor espere",
                        icon: "success",
                        button: "Entendido",
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 4000);
                }

                this.telefono = {numero_tel: ''};
            },
            editarFormulario(item) {
                this.telefono.numero_tel = item.numero_tel;
                this.telefono.id_tel = item.id_tel;
                this.modoEditar = true;
            },
            editarTelefono(telefono) {
                const params = {numero_tel: telefono.numero_tel};
                axios.put(`/telefono/${this.telefono.id_tel}`, params)
                    .then(res => {
                        this.telefono = {id_tel: '', numero_tel: '', id_cli: this.id_c};
                        this.modoEditar = false;
                    });
                axios.get(`/telefono/${this.id_c}`)
                    .then(res => {
                        this.loading = false;
                        this.telefonos = res.data.telefonos;
                        this.todos = res.data.todos;
                        this.id_cliente = this.id_c;
                    });
                swal({
                    title: "¡Editado!",
                    text: "La página recargara para actualizar los datos, por favor espere",
                    icon: "success",
                    button: "Entendido",
                });
                setTimeout(function () {
                    location.reload();
                }, 4000);
            },
            eliminarTelefono(telefono) {
                this.loading = true;
                swal({

                    title: "¿Estas seguro de que quieres eliminar?",
                    text: "¡Una vez eliminado, no se puede recuperar!",
                    icon: "warning",
                    buttons: {
                        cancel: 'Cancelar',
                        confirm: 'Si, eliminar',
                    },
                })
                    .then((loading) => {
                        if (loading) {

                            axios.delete(`/telefono/${telefono.id_tel}`, {
                                params: {
                                    id_cliente: this.id_c
                                }
                            }).then(res => {
                                console.log(res.data);
                            });

                            axios.get(`/telefono/${this.id_c}`)
                                .then(res => {
                                    this.loading = false;
                                    this.telefonos = res.data.telefonos;
                                    this.todos = res.data.todos;
                                    this.id_cliente = this.id_c;
                                });
                            swal({
                                title: "¡Eliminado!",
                                text: "La página recargara para actualizar los datos, por favor espere",
                                icon: "success",
                                button: "Entendido",
                            });
                            setTimeout(function () {
                                location.reload();
                            }, 4000);
                            this.modoEditar = false;
                            this.telefono = {id_tel: '', numero_tel: '', id_cli: this.id_c};
                        } else {
                            swal({
                                title: "¡Mensaje!",
                                text: "El teléfono sigue",
                                icon: "info",
                                button: "Entendido",
                            });
                        }
                    });

            },
            cancelarEdicion() {
                this.modoEditar = false;
                this.telefono = {id_tel: '', numero_tel: '', id_cli: this.id_c};
            },
            verTelTodos(telefono) {
                var igual = false;
                for (let i = 0; i < this.todos.length; i++) {
                    if (this.todos[i].numero_tel !== null) {
                        if (telefono === this.todos[i].numero_tel.trim()) {
                            return this.todos[i].id_tel;
                        }
                    }
                }
                return '';
            },
        }
    }
</script>
