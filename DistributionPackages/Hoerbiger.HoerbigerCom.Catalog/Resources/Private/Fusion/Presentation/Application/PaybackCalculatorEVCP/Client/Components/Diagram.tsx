import * as React from "react";
import Chart from "chart.js/auto";
import {ChartConfiguration} from "chart.js";
import {
	ChartParams
} from "../Defintions";

export const Diagram = (props: ChartParams): JSX.Element => {
	const chartRef = React.createRef<HTMLCanvasElement>();
	const startPercentage = 5;
	const duration = 6;

	React.useEffect(() => {
		const diagramRoot = chartRef.current?.getContext("2d");

		const barchartBackgrounds: string[] = [];
		for (let i = startPercentage; i < (startPercentage + duration); i++) {
			if (i === props.selectedIncrease) {
				barchartBackgrounds.push("#A89379");
			} else {
				barchartBackgrounds.push("#4f81bd");
			}
		}

		if (diagramRoot) {
			const data = {
				labels: props.xAxisData,
				datasets: [
					{
						label: props.chartLabels.barChartLabel,
						data: props.barChartData,
						borderColor: "#4f81bd",
						backgroundColor: barchartBackgrounds,
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
					locale: props.locale,
					maintainAspectRatio: false,
					responsive: true,
					interaction: {
						mode: "index",
						intersect: false,
					},
					plugins: {
						title: {
							display: true,
							color: "#A89379",
							text: props.chartLabels.chartTitle,
							font: {
								size: 30
							},
							padding: 30
						},
						legend: {
							position: "bottom",
							labels: {
								font: {
									size: 18
								}
							}
						},
						tooltip: {
							callbacks: {
								title(tooltipItems): string | string[] | void {
									return tooltipItems[0].label + " " + props.getLabelFor("label.chart.extension");
								}
							}
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
								text: props.chartLabels.yLeftLabel,
								font: {
									size: 18
								}
							},
							ticks: {
								font: {
									size: 18
								}
							}
						},
						y_right: {
							type: "linear",
							display: true,
							position: "right",
							min: 0,

							title: {
								display: true,
								text: props.chartLabels.yRightLabel,
								font: {
									size: 18
								}
							},

							// grid line settings
							grid: {
								drawOnChartArea: false, // only want the grid lines for one axis to show up
							},
							ticks: {
								font: {
									size: 18
								}
							}
						},
						x: {
							title: {
								display: true,
								text: props.chartLabels.xAxisLabel,
								font: {
									size: 18
								}
							},
							grid: {
								display: false
							},
							ticks: {
								font: {
									size: 18
								}
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
		<div data-select="diagram" className="h-[500px]">
			<canvas
				ref={chartRef}
				className="h-[500px]"
				data-select="mapElement"
			></canvas>
		</div>
	);
};
