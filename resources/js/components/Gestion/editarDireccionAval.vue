<template>
    <div id="app">

        <v-dialog
            v-model="dialog"
            max-width="30%">
            <v-form @submit.prevent="update" v-model="valid">
                <v-card>

                    <v-toolbar color="orange" dark>
                        <v-toolbar-title>Editar dirección del aval</v-toolbar-title>
                        <div class="flex-grow-1"></div>
                        <v-icon>explore</v-icon>
                    </v-toolbar>

                    <v-container>
                        <v-col>
                            <div v-for="(item,index) in direccion">
                                <v-text-field
                                    label="Código Postal"
                                    v-model="item.cp"
                                    :rules="reglaCp"
                                    :counter="5"
                                    outlined
                                    hint="Por ejemplo: 62000"
                                ></v-text-field>

                                <v-text-field
                                    label="Cuadrante"
                                    v-model="item.cuadrante"
                                    :rules="reglaNumeros"
                                    outlined
                                ></v-text-field>

                                <v-text-field
                                    label="Zona geográfica"
                                    v-model="item.zona_geo"
                                    :rules="reglaNumeros"
                                    outlined
                                ></v-text-field>

                                <v-text-field
                                    label="Dirección"
                                    v-model="item.direccion"
                                    hint="Por ejemplo: Priv. #5 de Mayo"
                                    outlined
                                ></v-text-field>

                                <v-text-field
                                    label="Número exterior"
                                    v-model="item.num_ext"
                                    outlined
                                ></v-text-field>

                                <v-text-field
                                    label="Número interior"
                                    v-model="item.num_int"
                                    outlined
                                ></v-text-field>

                                <v-text-field
                                    label="Población"
                                    v-model="item.poblacion"
                                    outlined
                                ></v-text-field>

                                <v-text-field
                                    label="Colonia"
                                    v-model="item.colonia"
                                    outlined
                                ></v-text-field>

                                <v-text-field
                                    label="Estado"
                                    v-model="item.estado"
                                    outlined
                                ></v-text-field>
                            </div>
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
                                :disabled="!valid"
                                class="mx-auto text-center text-white"
                                color="orange">
                                Editar dirección del aval
                            </v-btn>

                        </v-card-actions>
                    </v-container>


                </v-card>
            </v-form>
        </v-dialog>
    </div>

</template>

<script>
    import EventBus from '../../EventBus';

    export default {

        data() {
            return {
                direccion: [],
                dialog: false,

                valid: true,

                reglaCp: [v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números'],
                reglaNumeros: [v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números'],
                reglasLetras: [v => /^[a-zA-Z\\ñ\\ ]+$/.test(v) || 'Solo puede ingresar letras'],
            }
        },
        created() {
            EventBus.$on('direccion', data => {
                this.direccion = data;
            });
            EventBus.$on('dialogDireccion', data => {
                this.dialog = data;
            });
            EventBus.$on('id_cliente', data => {
                this.id_cliente = data;
            });
        },
        methods: {
            update() {
                axios.put(`/editarDireccion`, this.direccion[0])
                    .then(res => {
                        console.log(res.data);
                    });
                swal({
                    title: "¡Editado!",
                    text: "Dirección del aval editado",
                    icon: "success",
                    button: "Esta bien",
                });
            },
        },

    }
</script>

<style scoped>

</style>
