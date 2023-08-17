import * as React from "react";
import Chart from "chart.js/auto";
import {ChartConfiguration} from "chart.js";
import {
	ChartParams
} from "../Defintions";

export const Diagram = (props: ChartParams): JSX.Element => {
	const chartRef = React.createRef<HTMLCanvasElement>();

	React.useEffect(() => {
		const diagramRoot = chartRef.current?.getContext("2d");

		if (diagramRoot) {
			const data = {
				labels: props.xAxisData,
				datasets: [
					{
						label: props.chartLabels.barChartLabel,
						data: props.barChartData,
						borderColor: "#4f81bd",
						backgroundColor: "#4f81bd",
						yAxisID: "y_left",
						order: 1
					},
					{
						label: props.chartLabels.lineChartLabel,
						data: props.lineChartData,
						borderColor: "#8D172E",
						backgroundColor: "#8D172E",
						yAxisID: "y_right",
						type: "line",
						order: 0
					}
				]
			};

			const config = {
				type: "bar",
				data: data,
				options: {
					maintainAspectRatio: false,
					responsive: true,
					interaction: {
						mode: "index",
						intersect: false,
					},
					plugins: {
						title: {
							display: true,
							text: props.chartLabels.chartTitle,
							font: {
								size: 30
							}
						},
						legend: {
							position: "bottom"
						}
					},
					scales: {
						y_left: {
							stacked: true,
							grid: {
								display: true,
								color: "rgba(255,99,132,0.2)"
							},
							type: "linear",
							display: true,
							position: "left",
							min: 0,
							title: {
								display: true,
								text: props.chartLabels.yLeftLabel
							},
						},
						y_right: {
							type: "linear",
							display: true,
							position: "right",
							min: 0,

							title: {
								display: true,
								text: props.chartLabels.yRightLabel
							},

							// grid line settings
							grid: {
								drawOnChartArea: false, // only want the grid lines for one axis to show up
							},
						},
						x: {
							title: {
								display: true,
								text: props.chartLabels.xAxisLabel
							},
							grid: {
								display: false
							}
						},
					}
				},
			} as ChartConfiguration;

			new Chart(
				diagramRoot,
				config
			);
		}
	}, []);

	return (
		<div data-select="diagram">
			<canvas
				ref={chartRef}
				className="h-[500px]"
				data-select="mapElement"
			></canvas>
		</div>
	);
};
