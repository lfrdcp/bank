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
                            Agregar aval
                        </v-btn>
                    </v-col>

                    <v-dialog v-model="dialog" max-width="30%">

                        <v-form @submit.prevent="store" v-model="valid">
                            <v-card>
                                <v-toolbar color="primary" dark>
                                    <v-toolbar-title>Agregar aval</v-toolbar-title>
                                    <div class="flex-grow-1"></div>
                                    <v-icon>person_add</v-icon>
                                </v-toolbar>
                                <v-container>

                                    <v-col>
                                        <v-text-field
                                            label="Nombre de aval"
                                            v-model="nombre_aval"
                                            :rules="reglasLetras"
                                            outlined
                                        ></v-text-field>
                                        <v-container fluid>
                                            <v-row justify="center">
                                                <v-switch style="padding: 10px;"
                                                          inset
                                                          color="blue"
                                                          v-model="agregarTel"
                                                          label="Agregar teléfono"
                                                ></v-switch>
                                            </v-row>
                                        </v-container>

                                    </v-col>


                                    <v-col v-if="agregarTel">
                                        <v-text-field

                                            label="Teléfono"
                                            v-model="telefono"
                                            :rules="reglaTelefono"
                                            :counter="10"
                                            outlined
                                        ></v-text-field>
                                    </v-col>


                                    <v-col v-if="agregarDir">
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
                                            Agregar datos
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
                            <th scope="col">Nombre</th>
                            <!--      <th scope="col">Editar nombre</th>
                                  <th scope="col">Editar dirección</th>-->
                            <th scope="col">Agregar teléfono</th>
                            <th scope="col" v-if="tipo_usuario=='Administrador' || tipo_usuario=='Supervisor'">Eliminar al aval</th>
                        </tr>
                        </thead>
                        <tbody v-for="(item,index) in avales">
                        <tr v-for="(item1,index1) in item">
                            <th scope="row">{{index+1}}</th>
                            <td>{{item1.nombre_aval}}</td>
                            <!--<td>
                                <v-btn
                                    type="button"
                                    class="mx-auto text-center text-white"
                                    color="orange" @click="editarNombre(item1)">
                                    <i class="material-icons">mode_edit</i>
                                </v-btn>
                            </td>
                            <td>
                                <v-btn
                                    type="button"
                                    class="mx-auto text-center text-white"
                                    color="orange" @click="editarDirecion(direcciones[index])">
                                    <i class="material-icons">mode_edit</i>
                                </v-btn>
                            </td>-->
                            <td>
                                <!-- <v-btn v-if="telefonos[index]!=''"
                                        type="button"
                                        class="mx-auto text-center text-white"
                                        color="orange" @click="editarTelefono(telefonos[index])">
                                     <i class="material-icons">mode_edit</i>
                                 </v-btn>-->
                                <v-btn
                                    type="button"
                                    class="mx-auto text-center text-white"
                                    color="blue" @click="agregarTelefono(item1)">
                                    <i class="material-icons">add_circle_outline</i>
                                </v-btn>
                            </td>
                            <td>
                                <v-btn v-if="tipo_usuario=='Administrador' || tipo_usuario=='Supervisor'"
                                       type="button"
                                       class="mx-auto text-center text-white"
                                       color="red" @click="eliminarAval(item1,index)">
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
                nombre_aval: '',

                telefono: '',

                agregarDir: true,
                agregarTel: false,
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

                valid: true,
                dialog: false,

                avales: [],
                direcciones: [],
                telefonos: [],

                reglaTelefono: [v => v.length > 6 || 'Como minimo son 7 números', v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números'],

                reglaCp: [v => v.length <= 5 || 'El máximo es 5', v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números'],
                reglaNumeros: [v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números'],
                reglasLetras: [v => /^[a-zA-Z\\ñ\\ ]+$/.test(v) || 'Solo puede ingresar letras'],
            }
        },
        created() {
            axios.get(`/agregarDatosAval/${this.id_cliente}`)
                .then(res => {
                    this.avales = res.data.datos;
                    this.direcciones = res.data.direccion;
                    this.telefonos = res.data.telefono;
                    console.log(res.data);
                });

        },
        methods: {

            editarNombre(aval) {
                EventBus.$emit('aval', aval);
                EventBus.$emit('dialogAval', true);
            },

            editarDirecion(direccion) {
                EventBus.$emit('direccion', direccion);
                EventBus.$emit('dialogDireccion', true);
            },

            editarTelefono(telefono) {
                EventBus.$emit('telefono', telefono);
                EventBus.$emit('dialogTelefono', true);
            },

            agregarTelefono(aval) {
                EventBus.$emit('aval', aval);
                EventBus.$emit('dialogAgregarTelefono', true);
                EventBus.$emit('id_cliente', this.id_cliente);
            },

            eliminarAval(aval, index) {
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
                            axios.delete(`/eliminarAval`, {
                                params: {
                                    id_aval: aval.id_aval,
                                    id_cliente: this.id_cliente
                                }
                            }).then(() => {
                                this.avales.splice(index, 1);
                            });
                            swal({
                                title: "¡Eliminado!",
                                text: "Aval eliminado con exito",
                                icon: "success",
                                button: "Esta bien",
                            });
                        } else {
                            swal({
                                title: "¡Mensaje!",
                                text: "El aval sigue",
                                icon: "info",
                                button: "Esta bien",
                            });
                        }
                    });
            },

            store() {

                swal({
                    title: "¡Cargando!",
                    text: "Por favor espere",
                    icon: "info",
                    button: false,
                });

                const params1 = {
                    nombre_aval: this.nombre_aval,
                    id_cliente: this.id_cliente,
                    agregarTel: this.agregarTel,
                    agregarDir: true
                };

                if (this.agregarTel) params1.telefono = this.telefono;

                if (this.agregarDir) {
                    params1.cuadrante = this.cuadrante;
                    params1.zona_geo = this.zona_geo;
                    params1.direccion = this.direccion;
                    params1.num_ext = this.num_ext;
                    params1.num_int = this.num_int;
                    params1.tipo_direccion = 'aval';
                    params1.cp = this.cp;
                    params1.colonia = this.colonia;
                    params1.poblacion = this.poblacion;
                    params1.estado = this.estado;
                }
                console.log('params');
                console.log(params1);

                axios.post('/guardarDatosAval', params1).then(function (res) {
                    console.log('la respuesta es:');
                    console.log(res.data);
                    swal({
                        title: "¡Mensaje!",
                        text: res.data,
                        button: false,
                    });
                }).catch(function (err) {
                    console.log(err);
                });

              /*  axios.get(`/agregarDatosAval/${this.id_cliente}`)
                    .then(res => {
                        this.avales = res.data.datos;
                        this.direcciones = res.data.direccion;
                        this.telefonos = res.data.telefono;
                        console.log(res.data);
                    });*/
            }
        }
    }
</script>

<style scoped>

</style>
