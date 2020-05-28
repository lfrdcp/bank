<template>
    <div id="app">
        <v-app id="inspire">
            <v-content>
                <v-container>

                    <v-col>
                        <v-btn
                            color="blue"
                            class="mx-auto text-center text-white"
                            @click.stop="dialog = true">
                            Agregar dirección al cliente
                        </v-btn>
                    </v-col>


                    <v-dialog
                        v-model="dialog"
                        max-width="30%">
                        <v-form @submit.prevent="store" v-model="valid">
                            <v-card>

                                <v-toolbar color="primary" dark>
                                    <v-toolbar-title>Agregar dirección al cliente</v-toolbar-title>
                                    <div class="flex-grow-1"></div>
                                    <v-icon>explore</v-icon>
                                </v-toolbar>
                                <v-container>
                                    <v-col>
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


                    <v-data-table
                        :headers="headers"
                        :items="direcciones"
                        :items-per-page="10"
                        :footer-props="flechas"
                        class="elevation-10"
                    >

                        <template v-slot:item.editar="{ item }">

                            <v-btn v-if="tipo_usuario=='Administrador' || tipo_usuario=='Supervisor'"
                                @click="editarDireccionCliente(item)"
                                class="mx-auto text-center text-white"
                                color="orange">
                                <i class="material-icons">mode_edit</i>
                            </v-btn>

                        </template>
                        <template v-slot:item.eliminar="{ item }">

                            <v-btn v-if="tipo_usuario=='Administrador' || tipo_usuario=='Supervisor'"
                                @click="eliminarDireccionCliente(item)"
                                class="mx-auto text-center text-white"
                                color="red">
                                <i class="material-icons">delete_forever</i>
                            </v-btn>
                        </template>
                    </v-data-table>


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

                headers: [
                    {text: 'Cuadrante', value: 'cuadrante'},
                    {text: 'Zona geográfica', value: 'zona_geo'},
                    {text: 'Dirección', value: 'direccion'},
                    {text: 'Número exterior', value: 'num_ext'},
                    {text: 'Número interior', value: 'num_int'},
                    {text: 'Código Postal', value: 'cp'},
                    {text: 'Colonia', value: 'colonia'},
                    {text: 'poblacion', value: 'poblacion'},
                    {text: 'Estado', value: 'estado'},

                    {text: 'Editar', value: 'editar', sortable: false},
                    {text: 'Eliminar', value: 'eliminar', sortable: false},
                ],

                flechas: {
                    showFirstLastPage: true,
                    prevIcon: 'undo',
                    nextIcon: 'redo'
                },


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

                reglaCp: [v => v.length <= 5 || 'El máximo es 5', v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números'],
                reglaNumeros: [v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números'],
                reglasLetras: [v => /^[a-zA-Z]+$/.test(v) || 'Solo puede ingresar letras'],
            }
        },
        created() {
            /*swal({
                title: "Nota",
                text: 'Si no tienes algun dato para la casilla, ingresa: 0',
                icon: "info",
                button: "Esta bien",
            });*/

            axios.get(`/agregarDatosCliente/${this.id_cliente}`)
                .then(res => {
                    this.direcciones = res.data;
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
            eliminarDireccionCliente(direccion) {
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
                            axios.delete(`/eliminarDireccionCliente`, {params: {id_direccion: direccion.id_direccion}})
                                .then((res) => {
                                    console.log(res.data);
                                });
                            axios.get(`/agregarDatosCliente/${this.id_cliente}`)
                                .then(res => {
                                    this.direcciones = res.data;
                                    console.log(this.direcciones);
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

            editarDireccionCliente(direccion) {
                console.log(direccion);
                EventBus.$emit('direccion', direccion);
                EventBus.$emit('dialogDireccionCliente', true);
            },
            store() {
                swal({
                    title: "¡Cargando!",
                    text: "Por favor espere",
                    icon: "info",
                    button: false,
                });


                axios.post('/guardarDatosCliente', {
                    cuadrante: this.cuadrante,
                    zona_geo: this.zona_geo,
                    direccion: this.direccion,
                    num_ext: this.num_ext,
                    num_int: this.num_int,
                    tipo_direccion: 'casa',
                    cp: this.cp,
                    colonia: this.colonia,
                    poblacion: this.poblacion,
                    estado: this.estado,
                    id_cliente: this.id_cliente
                }).then(function (res) {
                    console.log(res.data);
                    if (res.data === 'Agregado') {
                        swal(res.data, "Se guardaron los datos del cliente", "success");
                    } else if ('Ya existe esta direccion') {
                        swal(res.data, "Los datos no se pueden guardar", "info");
                    } else {
                        swal(res.data, "Toma captura y comunicate con el desarrollador", "warning");
                    }

                }).catch(function (err) {
                    console.log(err);
                });
                axios.get(`/agregarDatosCliente/${this.id_cliente}`)
                    .then(res => {
                        this.direcciones = res.data;
                        console.log(this.direcciones);
                    });
            }
        }
    }
</script>

<style scoped>

</style>
