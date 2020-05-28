<template>
    <div id="app">
        <v-app style="height: 1rem; width: 90%">
            <v-container fluid position="fixed">


                <v-dialog v-model="dialog" scrollable max-width="500">
                    <template v-slot:activator="{ on }">
                        <v-btn absolute fab top right color="primary" dark v-on="on">
                            <v-badge v-model="show" color="red">
                                <template v-slot:badge>
                                    <span v-text="total"></span>
                                </template>
                                <v-icon>notification_important</v-icon>
                            </v-badge>
                        </v-btn>
                    </template>

                    <v-card class="mx-auto" max-width="500" tile>
                        <v-system-bar color="blue darken-2" dark>
                        </v-system-bar>
                        <v-toolbar color="blue" dark>
                            <v-toolbar-title>Notificaciones</v-toolbar-title>
                            <v-app-bar-nav-icon>
                                <v-badge v-model="show" color="red">
                                    <template v-slot:badge>
                                        <span v-text="total"></span>
                                    </template>
                                    <v-icon>notification_important</v-icon>
                                </v-badge>
                            </v-app-bar-nav-icon>
                        </v-toolbar>

                        <v-list shaped>

                            <v-dialog
                                ref="dialog"
                                v-model="modal"
                                :return-value.sync="date"
                                persistent
                                width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="date"
                                        label="Notificaciones del día:"
                                        prepend-icon="event"
                                        readonly
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker v-model="date" scrollable locale="es">
                                    <v-spacer></v-spacer>
                                    <v-btn text color="primary" @click="modal = false">Cancelar</v-btn>
                                    <v-btn text color="primary" @click="$refs.dialog.save(date),otroDia()">Listo</v-btn>
                                </v-date-picker>
                            </v-dialog>
                            <b>Notas</b>
                            <v-divider></v-divider>
                            <v-list-item-group v-model="item" color="green">
                                <v-list-item v-for="(item, i) in notas" :key="i">

                                    <div v-if="item.check ===true">
                                        <v-icon color="green">check_circle</v-icon>
                                    </div>
                                    <div v-if="item.check ===null || item.check ===false">
                                        <v-icon color="orange">error</v-icon>
                                    </div>

                                    <v-list-item-icon @click="cambiarGestion(item.id_gestion)">
                                        <v-list-item v-text="item.hora"></v-list-item>
                                        <v-switch
                                            v-model="item.check"
                                            color="green"
                                        ></v-switch>
                                    </v-list-item-icon>

                                    <v-list-item-content @click="abrirPestana(item.id)">
                                        <v-list-item-title v-text="item.titulo"></v-list-item-title>
                                        <v-list-item-subtitle v-text="item.nombre"></v-list-item-subtitle>
                                        <v-list-item-subtitle v-text="item.id"></v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list-item-group>
                        </v-list>

                    </v-card>
                </v-dialog>
            </v-container>

        </v-app>
    </div>
</template>

<script>
    export default {
        name: "navbar",
        data() {
            return {
                date: new Date().toISOString().substr(0, 10),
                modal: false,
                total: '',
                notas: [],
            }
        },
        created() {
            axios.get(`/notificacion`,
                {
                    params: {
                        tipo: 'hoy'
                    }
                }
            ).then(res => {
                this.total = res.data.total;
                this.notas = res.data.nota;
            });
        },
        methods: {
            cambiarGestion(id) {
                const params = {tipo: 'gestion', id: id};
                axios.put(`/notificacion_check`, params).then(res => {
                    this.total = res.data;
                });
            },
            abrirPestana(id) {
                window.open(`gestion/${id}`, "_blank");
            },
            otroDia() {
                axios.get(`/notificacion`,
                    {
                        params: {
                            tipo: 'otro',
                            fecha: this.date
                        }
                    }
                ).then(res => {
                    this.total = res.data.total;
                    this.notas = res.data.nota;
                });
            }
        }
    }
</script>

<style scoped>

</style>
