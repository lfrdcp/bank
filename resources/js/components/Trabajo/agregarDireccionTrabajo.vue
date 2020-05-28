<template>
    <div id="app">
        <v-app>
            <v-content>
                <v-container>

                    <v-col>
                        <v-btn
                            color="blue"
                            class="mx-auto text-center text-white"
                            @click.stop="dialog = true">
                            Agregar dirección de trabajo
                        </v-btn>
                    </v-col>


                    <v-dialog
                        v-model="dialog"
                        max-width="30%">
                        <v-form @submit.prevent="store" v-model="valid">
                            <v-card>

                                <v-toolbar color="primary" dark>
                                    <v-toolbar-title>Agregar dirección de trabajo</v-toolbar-title>
                                    <div class="flex-grow-1"></div>
                                    <v-icon>work_outline</v-icon>
                                </v-toolbar>

                                <v-container>
                                    <v-col>
                                        <v-text-field

                                            label="Teléfono"
                                            v-model="telefono"
                                            :rules="reglaTelefono"
                                            :counter="10"
                                            outlined
                                        ></v-text-field>

                                        <v-text-field
                                            label="Código Postal"
                                            v-model="cp"
                                            :rules="reglaCp"
                                            :counter="5"
                                            outlined
                                            hint="Por ejemplo: 62000"
                                        ></v-text-field>

                                        <v-text-field
                                            label="Cuadrante"
                                            v-model="cuadrante"
                                            :rules="reglaNumeros"
                                            outlined
                                        ></v-text-field>

                                        <v-text-field
                                            label="Zona geográfica"
                                            v-model="zona_geo"
                                            :rules="reglaNumeros"
                                            outlined
                                        ></v-text-field>

                                        <v-text-field
                                            label="Dirección"
                                            v-model="direccion"
                                            hint="Por ejemplo: Priv. #5 de Mayo"
                                            outlined
                                        ></v-text-field>

                                        <v-text-field
                                            label="Número exterior"
                                            v-model="num_ext"
                                            outlined
                                        ></v-text-field>

                                        <v-text-field
                                            label="Número interior"
                                            v-model="num_int"
                                            outlined
                                        ></v-text-field>

                                        <v-text-field
                                            label="Población"
                                            v-model="poblacion"
                                            outlined
                                        ></v-text-field>

                                        <v-text-field
                                            label="Colonia"
                                            v-model="colonia"
                                            outlined
                                        ></v-text-field>

                                        <v-text-field
                                            label="Estado"
                                            v-model="estado"
                                            outlined
                                        ></v-text-field>
                                    </v-col>

                                    <v-card-actions>
                                        <div class="flex-grow-1"></div>
                                        <v-btn
                                            @click="dialog = false"
                                            color="red"
                                            class="mx-auto text-center text-white"
                                        >
                                            Cerrar
                                        </v-btn>
                                        <v-btn
                                            @click="dialog = false"
                                            type="submit"

                                            class="mx-auto text-center text-white"
                                            color="blue">
                                            Agregar dirección
                                        </v-btn>
                                    </v-card-actions>
                                </v-container>
                            </v-card>
                        </v-form>
                    </v-dialog>


                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Cuadrante</th>
                            <th scope="col">Zona geográfica</th>
                            <th scope="col">Dirección</th>
                            <th scope="col">Número exterior</th>
                            <th scope="col">Número interior</th>
                            <th scope="col">Código Postal</th>
                            <th scope="col">Colonia</th>
                            <th scope="col">Población</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Editar</th>
                            <th scope="col">Eliminar</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item,index) in direcciones" style="font-size: 11px">
                            <th scope="row">{{index+1}}</th>
                            <td>{{item.cuadrante}}</td>
                            <td>{{item.zona_geo}}</td>
                            <td>{{item.direccion}}</td>
                            <td>{{item.num_ext}}</td>
                            <td>{{item.num_int}}</td>
                            <td>{{item.cp}}</td>
                            <td>{{item.colonia}}</td>
                            <td>{{item.poblacion}}</td>
                            <td>{{item.estado}}</td>
                            <td>
                                <v-btn v-if="tipo_usuario=='Administrador' || tipo_usuario=='Supervisor'"
                                    type="button"
                                    class="mx-auto text-center text-white"
                                    color="orange" @click="editarDireccionTrabajo(item)">
                                    <i class="material-icons">mode_edit</i>
                                </v-btn>
                            </td>
                            <td>
                                <v-btn v-if="tipo_usuario=='Administrador' || tipo_usuario=='Supervisor'"
                                    type="button"
                                    class="mx-auto text-center text-white"
                                    color="red" @click="eliminarDireccionTrabajo(item,index)">
                                    <i class="material-icons">delete_forever</i>
                                </v-btn>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </v-container>
            </v-content>
        </v-app>
    </div>

</template>

<script>
    import EventBus from '../../EventBus';

    export default {

        props: {
            id_cliente: String,
            tipo_usuario: String
        },
        data() {
            return {
                telefono: '',

                cuadrante: '',
                zona_geo: '',
                direccion: '',
                num_ext: '',
                num_int: '',
                tipo_direccion: '',
                cp: '',
                colonia: '',
                poblacion: '',
                estado: '',

                editado: false,

                valid: true,
                dialog: false,
                loading: true,

                direcciones: [],
                trabajos: [],

                reglaTelefono: [v => v.length > 6 || 'Como minimo son 7 números', v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números'],
                reglaCp: [v => v.length <= 5 || 'El máximo es 5', v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números'],
                reglaNumeros: [v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números'],
                reglasLetras: [v => /^[a-zA-Z]+$/.test(v) || 'Solo puede ingresar letras'],
            }
        },
        created() {
            axios.get(`/trabajo/${this.id_cliente}`)
                .then(res => {
                    this.direcciones = res.data.direccion;
                    this.trabajos = res.data.trabajo;
                    console.log(this.direcciones);
                });


            EventBus.$on('editado', data => {
                this.editado = data;
            });

            if (this.editado) {
                axios.get(`/agregarDatosCliente/${this.id_cliente}`)
                    .then(res => {
                        this.direcciones = res.data;
                        console.log(this.direcciones);
                    });
            }
        },
        methods: {
            eliminarDireccionTrabajo(direccion, index) {
                swal({
                    title: "¿Estas seguro de que quieres eliminar?",
                    text: "¡Una vez eliminado, no se puede recuperar!",
                    icon: "warning",
                    buttons: {
                        cancel: 'Cancelar',
                        confirm: 'Si, eliminar',
                    }
                })
                    .then((loading) => {
                        if (loading) {
                            axios.delete(`/trabajo/${direccion.id_direccion}`,)
                                .then((res) => {
                                    console.log(res.data);
                                    this.direcciones.splice(index, 1);
                                });
                            swal({
                                title: "¡Eliminado!",
                                text: "Dirección eliminada con exito",
                                icon: "success",
                                button: "Esta bien",
                            });
                        } else {
                            swal({
                                title: "¡Mensaje!",
                                text: "La dirección sigue",
                                icon: "info",
                                button: "Esta bien",
                            });
                        }
                    });
            },

            editarDireccionTrabajo(direccion) {
                console.log(direccion);
                EventBus.$emit('direccionTrabajo', direccion);
                EventBus.$emit('dialogDireccionTrabajo', true);
            },
            store() {

                swal({
                    title: "¡Cargando!",
                    text: "Por favor espere",
                    icon: "info",
                    button: false,
                });


                axios.post('/trabajo', {
                    num_tel: this.telefono,
                    cuadrante: this.cuadrante,
                    zona_geo: this.zona_geo,
                    direccion: this.direccion,
                    num_ext: this.num_ext,
                    num_int: this.num_int,
                    tipo_direccion: 'trabajo',
                    cp: this.cp,
                    colonia: this.colonia,
                    poblacion: this.poblacion,
                    estado: this.estado,
                    id_cliente: this.id_cliente
                }).then(function (res) {
                    swal(res.data, "Se agregaron los datos del trabajo", "success");
                    console.log(res.data);
                }).catch(function (err) {
                    console.log(err);
                });

                axios.get(`/trabajo/${this.id_cliente}`)
                    .then(res => {
                        this.direcciones = res.data.direccion;
                        this.trabajos = res.data.trabajo;
                        console.log(this.direcciones);
                    });
            }
        }
    }
</script>

<style scoped>

</style>
