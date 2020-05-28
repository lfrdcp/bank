<template>
    <div id="app">
        <v-app>
            <v-content>
                <v-container>
                    <v-layout wrap>
                        <spinner-component v-if="loading"></spinner-component>


                        <table
                            class="shadow-lg table table-bordered responsive">
                            <thead>
                            <tr class="bg-secondary text-white">
                                <th scope="col">Nombre del despacho</th>
                                <th scope="col">¿Pago?</th>
                                <th scope="col">Forma de pago</th>
                                <th scope="col">Fecha de pago</th>
                                <th scope="col">Editar</th>
                                <th scope="col">Eliminar</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr v-for="(item, index) in despachos" :key="index">
                                <td>{{item.nombre}}</td>
                                <td v-if="item.pago">Si</td>
                                <td v-else>No</td>

                                <td>{{item.metodo}}</td>
                                <td>{{item.fecha}}</td>
                                <td>
                                    <v-card @click="modalupdate(item)" style="padding: 6px;"
                                            class="mx-auto text-center text-white"
                                            color="yellow darken-4">
                                        <i class="material-icons"
                                           style="font-size: 18px;">mode_edit</i>
                                    </v-card>


                                </td>
                                <td>
                                    <v-card @click="borrar(item.id_despacho, index)" style="padding: 6px;"
                                            class="mx-auto text-center text-white"
                                            color="red darken-1">
                                        <i class="material-icons" style="font-size: 18px;">delete_forever</i>
                                    </v-card>

                                </td>
                            </tr>

                            </tbody>
                        </table>


                        <div class="modal fade" id="editarDespacho" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Editar despacho</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <v-form @submit.prevent="update(id_despacho,nombre,fecha,costo,pago,metodo)"
                                                v-model="valid">
                                            <input v-model="id_despacho" type="hidden">


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
                                                hint="Por ejemplo: 150"
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


                                            <v-card style="padding: 6px;" class="mx-auto text-center text-white"
                                                    color="yellow darken-4">
                                                <v-btn
                                                    type="submit" :disabled="!valid"
                                                    class="mx-auto text-center text-white" color="yellow darken-4">
                                                    <i class="material-icons" style="font-size: 18px;">mode_edit</i>
                                                    Editar despacho
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
    import axios from 'axios';
    import EventBus from '../../EventBus';

    export default {

        data() {
            return {
                items: ['Si', 'No'],
                id_despacho: '',
                nombre: '',
                costo: '',
                pago: '',
                metodo: '',
                despachos: [],
                loading: true,
                fecha: new Date().toISOString().substr(0, 10),
                menu: false,
                valid: true,
                selectReglas: [
                    v => !!v || 'Necesita seleccionar si el despacho ya del despacho',
                ],
                nombreReglas: [
                    v => !!v || 'Necesita ingresar el nombre del despacho',
                    v => /^[0-9a-zA-Z\\_]+$/.test(v) || 'Solo puede ingresar letras, números y guion bajo, no caracteres especiales o espacios en blanco',
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
            axios.get('/despacho')
                .then(res => {
                    this.loading = false;
                    this.despachos = res.data;

                    console.log(this.despachos);

                    EventBus.$emit('despachos', this.despachos);

                    if (this.despachos.length === 0) {
                        swal("El despacho esta vacio", {
                            icon: "info",
                            button: "Entendido",
                        });
                    }
                });

            EventBus.$on('despachoNuevo', data => {
                this.despachos.push(data)
            });


        },
        methods: {
            modalupdate(item) {
                this.id_despacho = item.id_despacho;
                this.nombre = item.nombre.trim();
                this.fecha = item.fecha;
                this.costo = item.costo;
                if (item.pago) this.pago = "Si";
                if (!item.pago) this.pago = "No";
                this.metodo = item.metodo.trim();
                $('#editarDespacho').modal('show');
            },

            update(id, nom, fech, cos, pag, met) {
                if (this.pago === "Si") pag = true;
                if (this.pago === "No") pag = false;
                const params = {fecha: fech, costo: cos, pago: pag, metodo: met};
                axios.put(`/despacho/${this.id_despacho}`, params)
                    .then(res => {
                        this.nombre = '';
                        this.fecha = '';
                        this.costo = '';
                        this.pago = '';
                        this.metodo = '';
                        const index = this.despachos.findIndex(item => item.id_despacho === id);
                        this.despachos[index] = res.data;
                    });
                $('#editarDespacho').modal('hide');
                swal("¡Listo!", "Se edito un despacho", "success");
            },
            borrar(id_despacho, index) {
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

                            axios.delete(`/despacho/${id_despacho}`)
                                .then(() => {
                                    this.despachos.splice(index, 1);
                                });

                            swal("¡Listo! Despacho eliminado con exito", {
                                icon: "success",
                            });
                            this.modoEditar = false;
                            this.telefono = {id_tel: '', numero_tel: '', id_cli: this.id_c};
                        } else {
                            swal("¡El despacho sigue!", {
                                button: "Esta bien",
                            });
                        }
                    });

            },
        }
    }
</script>

<style>
    .my-input input {
        text-transform: lowercase;
    }
</style>
