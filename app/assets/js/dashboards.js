let config = {
	type: 'bar',
	data: {labels: _leyendas_ventas_credito,datasets: [{backgroundColor: '#17a2b8',data: _datos_grafico_tendencia_venta_credito,}]},
	options: {...noLeyendnoGridLines,}
};

const myChart = new Chart(document.getElementById('sales-chart'), config);

let config2 = {
	type: 'line',
	data: {labels: _leyendas_ventas_contado,datasets: [{backgroundColor: '#17a2b8',borderColor: '#17a2b8',data: _datos_grafico_tendencia_venta_contado,}]},
	options: {...noLeyendnoGridLines,}
};
const myChart2 = new Chart(document.getElementById('visitors-chart'), config2);

const saludo = (Parametros) =>{
	_visitorsChart.data.datasets[0].data[2] = 50;
	_visitorsChart.update();
}
