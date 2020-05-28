<template>
    <div id="app">
        <v-app style="width: 15rem; height: 5rem;">
            <v-content>
                <v-container>
                    <v-layout wrap>

                        <v-card style="padding: 6px;"
                                class="mx-auto text-center text-white"
                                color="blue ">
                            <button @click="ventanaInfo" type="button" data-toggle="modal"
                                    data-target="#crearDespacho">
                                Agregar despacho
                            </button>
                        </v-card>


                        <div class="modal fade" id="crearDespacho" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Agregar despacho</h5>

                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <v-form @submit.prevent="store" v-model="valid">

                                            <v-text-field
                                                class="my-input"
                                                v-model="idExterno"
                                                :rules="[
                                                verIdsExt,
                                                v => !!v || 'Necesita ingresar el id del despacho',
                                                v => /^[0-9a-zA-Z]+$/.test(v) || 'Solo puede ingresar letras, números, no caracteres especiales o espacios en blanco']"
                                                :counter="35"
                                                hint="Por ejemplo: 125"
                                                label="ID del despacho"
                                                required
                                            ></v-text-field>
                                            <br>


                                            <v-text-field
                                                class="my-input"
                                                v-model="nombre"
                                                :rules="[
                                                sellerId,
                                                v => !!v || 'Necesita ingresar el nombre del despacho',
                    v => /^[0-9a-zA-Z\\_]+$/.test(v) || 'Solo puede ingresar letras, números y guion bajo, no caracteres especiales o espacios en blanco'
                    ]"
                                                :counter="35"
                                                hint="Por ejemplo: alfredo_castaneda"
                                                label="Nombre del despacho"
                                                required
                                            ></v-text-field>
                                            <br>

                                            <v-col>
                                                <div class="title">Fecha de pago</div>
                                                <v-date-picker v-model="fecha"
                                                               color="light blue accent-2"
                                                               :landscape="$vuetify.breakpoint.smAndUp"
                                                               required
                                                               no-title scrollable
                                                               year-icon="calendar_today"
                                                               prev-icon="skip_previous"
                                                               next-icon="skip_next"
                                                               locale="es"
                                                ></v-date-picker>
                                            </v-col>
                                            <br>


                                            <v-text-field
                                                class="my-input"
                                                v-model="costo"
                                                :rules="costoReglas"
                                                hint="Por ejemplo, 150"
                                                label="Costo de la renta del despacho"
                                                required
                                            ></v-text-field>
                                            <br>


                                            <v-select
                                                :items="items"
                                                v-model="pago"
                                                label="¿El despacho ya pagó?"
                                                :rules="selectReglas"
                                                required
                                            ></v-select>
                                            <br>


                                            <v-text-field
                                                v-model="metodo"
                                                :rules="metodoReglas"
                                                hint="Por ejemplo: Efectivo, Tarjeta"
                                                label="Metodo de pago del despacho"
                                                required
                                            ></v-text-field>
                                            <br><br>


                                            <v-card style="padding: 6px;"
                                                    class="mx-auto text-center text-white"
                                                    color="blue ">
                                                <v-btn
                                                    type="submit"
                                                    :disabled="!valid"
                                                    class="mx-auto text-center text-white"
                                                    color="blue "
                                                >
                                                    Agregar despacho
                                                </v-btn>

                                            </v-card>


                                        </v-form>
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
    import EventBus from '../../EventBus';

    export default {

        data() {
            return {
                idExterno: '',
                despachos: [],
                rules: [],
                loading: true,
                items: ['Si', 'No'],
                nombre: '',
                costo: '',
                pago: '',
                metodo: '',
                fecha: new Date().toISOString().substr(0, 10),
                menu: false,
                valid: true,
                selectReglas: [
                    v => !!v || 'Necesita seleccionar si el despacho ya pago',
                ],
                costoReglas: [
                    v => !!v || 'Necesita ingresar el costo del despacho',
                    v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números',
                ],
                metodoReglas: [
                    v => !!v || 'Necesita ingresar el metodo de pago del despacho',
                    v => /^[a-zA-Z]+$/.test(v) || 'Solo puede ingresar letras',
                ],
            }
        },
        created() {
            EventBus.$on('despachos', data => {
                this.despachos = data;
            });


        },
        methods: {
            ventanaInfo() {
                swal({
                    title: "Nota",
                    text: 'Una vez registrado el despacho, no se puede cambiar el nombre',
                    icon: "info",
                    button: "Esta bien",
                });
            },
            verIdsExt(value) {
                var igual = false;

                for (let i = 0; i < this.despachos.length; i++) {
                    if (value === this.despachos[i].id_despacho_externo.trim()) {
                        igual = true;
                    }
                }

                if (igual) {
                    return "Ya existe el id externo";
                } else {
                    return true;
                }
            },
            sellerId(value) {
                var igual = false;

                for (let i = 0; i < this.despachos.length; i++) {
                    if (value === this.despachos[i].nombre.trim()) {

                        igual = true;
                    }
                }

                if (igual) {
                    return "Ya existe el despacho";
                } else {
                    return true;
                }
            },
            store() {
                swal({
                    title: "¡Cargando!",
                    text: "Por favor espere",
                    icon: "info",
                    button: false,
                });

                var pagoAux;

                if (this.pago === "Si") pagoAux = true;
                if (this.pago === "No") pagoAux = false;

                $('#crearDespacho').modal('hide');


                axios.post('/despacho', {
                    nombre: this.nombre,
                    fecha: this.fecha,
                    costo: this.costo,
                    pago: pagoAux,
                    metodo: this.metodo,
                    idExterno: this.idExterno,
                }).then(function (res) {


                    if (res.data.correcto) {

                        swal("¡Listo!", "Se agrego el despacho al registro, se creo su base de datos y tablas correspondientes", "success");

                        EventBus.$emit('despachoNuevo', res.data.despacho);
                    } else {
                        var i = 0;
                        var msj;
                        for (i = 0; i < res.data.despacho['errorInfo'].length; i++) {
                            msj = msj + '\n' + res.data.despacho['errorInfo'][i];
                        }
                        swal({
                            title: "Toma captura del mensaje que aparece a continuación y contactate con el desarrollador:",
                            text: msj,
                            icon: "error",
                            button: "Entendido",
                        });
                    }

                }).catch(function (err) {
                    console.log(err);
                });

                this.nombre = '';
                this.fecha = '';
                this.costo = '';
                this.pago = '';
                this.metodo = '';


            }
        }
    }
</script>

<style>
    .top-space {
        margin-top: 20px
    }

    .my-input input {
        text-transform: lowercase;
    }
</style>

