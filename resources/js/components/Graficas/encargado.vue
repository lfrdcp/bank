<template>
    <div id="app">
        <v-app>
            <v-card>
                <v-system-bar color="blue darken-2"></v-system-bar>
                <v-app-bar dark color="blue">
                    <v-toolbar-title>Gráfica de avance por encargado</v-toolbar-title>
                    <div class="flex-grow-1"></div>
                </v-app-bar>
            </v-card>
            <div class="my-2">
                <v-btn @click="grafica_1='area',grafica_2='line',grafica_3='bar'" color="blue" dark>Clic
                    para
                    cambiar
                    tipo de
                    gráficas
                </v-btn>
            </div>
            <v-card>
                <v-col>
                    <v-card>
                        <v-system-bar color="blue darken-2"></v-system-bar>
                        <v-container class="pa-2" fluid>

                            <apexchart :type=grafica_1 height=400 :options="chartOptions" :series="series"/>

                        </v-container>
                    </v-card>
                </v-col>
                <v-row>
                    <v-col xs="12" sm="12" md="6" lg="6">
                        <v-card>
                            <v-system-bar color="blue darken-2"></v-system-bar>
                            <v-container class="pa-2" fluid>

                                <apexchart :type=grafica_3 height=400 :options="chartOptions" :series="series"/>

                            </v-container>
                        </v-card>
                    </v-col>
                    <v-col xs="12" sm="12" md="6" lg="6">
                        <v-card>
                            <v-system-bar color="blue darken-2"></v-system-bar>
                            <v-container class="pa-2" fluid>

                                <apexchart :type=grafica_2 height=400 :options="chartOptions" :series="series"/>

                            </v-container>
                        </v-card>
                    </v-col>
                </v-row>
            </v-card>


        </v-app>
    </div>
</template>

<script>
    export default {

        data: function () {
            return {
                grafica_1: 'bar',
                grafica_2: 'area',
                grafica_3: 'line',
                recargar: 0,
                series: [],
                chartOptions: {
                    stroke: {
                        width: 7,
                        curve: 'smooth'
                    },
                    legend: {
                        position: 'top'
                    },
                    colors: ['#673AB7', '#2196F3'],
                    plotOptions: {
                        bar: {
                            horizontal: false,
                        },
                    },
                    dataLabels: {
                        enabled: true,
                    },
                    xaxis: {
                        categories: [],
                    },
                    title: {
                        text: 'Gráfica de avance por encargado'
                    },
                    yaxis: {
                        title: {
                            text: '$ (Pesos)'
                        }
                    },
                    fill: {
                        opacity: .8
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return "$ " + val + " Pesos"
                            }
                        }
                    },
                },

            }
        },

        mounted: function () {
            this.$nextTick(function () {
                axios.get(`/grafica_encargado_index`).then(res => {
                    this.series = res.data[0];
                    this.chartOptions = {
                        xaxis: res.data[1]
                    }
                });
                this.updateChart();
            })
        },
        methods: {
            updateChart() {
                setInterval(function () {
                    axios.get(`/grafica_encargado_index`).then(res => {
                        this.series = res.data[0];
                        this.chartOptions = {
                            xaxis: res.data[1]
                        }
                    });
                    this.recargar++;
                    console.log(this.recargar);
                }.bind(this), 60000);
            },
        }
    };
</script>

