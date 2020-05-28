<template>
    <div id="app">

        <v-dialog
            v-model="dialog"
            max-width="30%">
            <v-form @submit.prevent="update" v-model="valid">
                <v-card>
                    <v-toolbar color="orange" dark>
                        <v-toolbar-title>Editar nombre del aval</v-toolbar-title>
                        <div class="flex-grow-1"></div>
                        <v-icon>account_circle</v-icon>
                    </v-toolbar>

                    <v-container>
                        <v-col>
                            <v-text-field
                                label="Nombre de aval"
                                :rules="reglasLetras"
                                v-model="aval.nombre_aval"
                                outlined
                            ></v-text-field>
                        </v-col>

                        <v-card-actions>
                            <div class="flex-grow-1"></div>

                            <v-btn
                                color="red"
                                class="mx-auto text-center text-white"
                                @click="dialog = false"
                            >
                                Cerrar
                            </v-btn>

                            <v-btn
                                @click="dialog = false"
                                type="submit"
                                :disabled="!valid"
                                class="mx-auto text-center text-white"
                                color="orange">
                                Editar nombre
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
                aval: '',

                valid: true,
                dialog: false,

                reglasLetras: [v => /^[a-zA-Z\\ñ\\ ]+$/.test(v) || 'Solo puede ingresar letras'],
            }
        },
        created() {
            EventBus.$on('aval', data => {
                this.aval = data;
            });
            EventBus.$on('dialogAval', data => {
                this.dialog = data;
            });
        },
        methods: {
            update() {
                axios.put(`/editarNombre`, this.aval)
                    .then(res => {
                        console.log(res.data);
                    });
                swal({
                    title: "¡Editado!",
                    text: "Nombre del aval editado",
                    icon: "success",
                    button: "Esta bien",
                });
            },
        },

    }
</script>

<style scoped>

</style>
