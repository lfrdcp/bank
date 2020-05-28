<template>
    <div id="app">
        <v-app>
            <v-card>
                <v-system-bar color="blue darken-2"></v-system-bar>
                <v-app-bar dark color="blue">
                    <v-toolbar-title>Gráfica de avance por gestor</v-toolbar-title>
                    <div class="flex-grow-1"></div>
                </v-app-bar>
            </v-card>
            <div class="my-2">
                <v-btn @click="grafica_1='area'" color="blue" dark>Clic
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

                                <v-card
                                    class="mx-auto"
                                    max-width="344"
                                    outlined
                                >

                                    <v-list flat>
                                        <v-list-item-group color="primary">
                                            <v-list-item v-for="(item, index) in series2">
                                                <v-icon color="primary">account_circle</v-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title>{{chartOptions2.labels[index]}} : {{item}} %
                                                    </v-list-item-title>
                                                </v-list-item-content>
                                            </v-list-item>
                                        </v-list-item-group>
                                    </v-list>

                                </v-card>

                            </v-container>
                        </v-card>
                    </v-col>
                    <v-col xs="12" sm="12" md="6" lg="6">
                        <v-card>
                            <v-system-bar color="blue darken-2"></v-system-bar>
                            <v-container class="pa-2" fluid>

                                <apexchart :type=grafica_2 height=400 :options="chartOptions2" :series="series2"/>

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
                total: 0,
                series2: [0],
                chartOptions2: {
                    title: {
                        text: 'Gráfica de avance por gestor'
                    },
                    plotOptions: {
                        radialBar: {
                            offsetY: 0,
                            startAngle: 18,
                            endAngle: 273,
                            hollow: {
                                margin: 1,
                                size: '50%'
                            },
                            dataLabels: {
                                name: {
                                    show: true,
                                },
                                value: {
                                    show: true,
                                }
                            }
                        }
                    },
                    labels: [],
                    legend: {
                        show: true,
                        floating: true,
                        fontSize: '13px',
                        position: 'right',
                        offsetX: 250,
                        offsetY: 10,
                        labels: {
                            useSeriesColors: true,
                        },
                        markers: {
                            size: 1
                        },

                        itemMargin: {
                            horizontal: 1,
                        }
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            legend: {
                                show: true
                            }
                        }
                    }]
                },


                grafica_1: 'bar',
                grafica_2: 'radialBar',
                recargar: 0,
                series: [],
                chartOptions: {

                    legend: {
                        position: 'top'
                    },
                    plotOptions: {
                        shadow: {
                            enabled: false,
                            color: '#bbb',
                            top: 3,
                            left: 2,
                            blur: 3,
                            opacity: 1
                        },
                        bar: {
                            barHeight: '100%',
                            distributed: true,
                            horizontal: false,
                            dataLabels: {
                                position: 'bottom'
                            },
                        },
                    },

                    stroke: {
                        width: 7,
                        curve: 'straight',

                    },
                    dataLabels: {
                        enabled: true,
                    },
                    xaxis: {
                        categories: [],
                    },
                    title: {
                        text: 'Gráfica de avance por gestor'
                    },
                    yaxis: {
                        title: {
                            text: 'Gestores'
                        }
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
                axios.get(`/grafica_gestor_index`).then(res => {
                    this.series = res.data[0];
                    this.chartOptions = {
                        xaxis: res.data[1]
                    };
                    this.series2 = res.data[2];
                    this.total = res.data[3];
                    this.chartOptions2 = {
                        labels: res.data[4]
                    };
                });
                this.updateChart();
            })
        },
        methods: {
            updateChart() {
                setInterval(function () {
                    axios.get(`/grafica_gestor_index`).then(res => {
                        this.series = res.data[0];
                        this.chartOptions = {
                            xaxis: res.data[1]
                        };
                        this.series2 = res.data[2];
                        this.total = res.data[3];
                        this.chartOptions2 = {
                            labels: res.data[4]
                        };
                    });
                    this.recargar++;
                    console.log(this.recargar);
                }.bind(this), 60000);
            },
        }
    };
</script>

