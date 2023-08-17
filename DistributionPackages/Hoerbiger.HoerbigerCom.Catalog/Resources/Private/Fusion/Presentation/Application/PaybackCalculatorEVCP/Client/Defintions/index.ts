export type LabelGetter = (key: string, fallback?: string) => string;

export interface ApplicationData {
	applicationRoot: HTMLElement;
	inputFields: InputFields;
	calculationFields: CalculationFields;
	getLabelFor: LabelGetter;
}

export interface InputFields {
	cost_dryNaturalGas: number;
	cost_NGL: number;
	compressor_annualOperationHours: number;
	compressor_cylindersNumber: number;
	compressor_nominalFlowRate: number;
	compressor_outputIncrease: number;
}

export interface InputRowParams {
	inputName: string;
	inputValue: number|null;
	label: string;
	unit: string;
	tooltip: Tooltip;
	getLabelFor: LabelGetter;
	onChange: (value: string, name: string) => void;
}

export interface Tooltip {
	abbreviation: string;
	description: string;
}

export interface ChartParams {
	applicationRoot: HTMLElement;
	chartLabels: CharLabels
	xAxisData: string[],
	barChartData: number[],
	lineChartData: number[],
	getLabelFor: LabelGetter;
}

export interface CharLabels {
	chartTitle: string;
	barChartLabel: string;
	yLeftLabel: string
	lineChartLabel: string;
	yRightLabel: string;
	xAxisLabel: string
}

export interface CalculationFields {
	unit_btu: number;
	unit_mmbtu: number;
	unit_scf: number;

	unit_avgHeatingValue4NG: number;

	composition_dryNaturalGas: number;
	composition_NGL: number;
	composition_others: number;

	cost_actuators: number;
	cost_localPanel: number;
	cost_installation: number;

	cost_generalSubtotal: number;
	cost_scheduleAndControl: number;
	cost_customerIntegration: number;
	cost_commission: number;
}

export interface Calculation {
	gasCompositionOfRawNaturalGasTotal: number;
	compressorOperatingData: CompressorOperatingData
	compressorOperatingDataTable: CompressorOperatingDataTable
	additionalRevenue: AdditionalRevenue;
	additionalRevenueTable: AdditionalRevenueTable;
	projectCostSubtotalEVCP: number;
	projectCostTotal: number;
	calculationResult: CalculationResult;
	paybackTimePerDay: number;
	paybackTimePerMonth: number;
}

export interface CompressorOperatingData {
	percentage: number;
	standardCubicFeet: number;
	heatingValue: number;
}

export interface AdditionalRevenue {
	percentage: number;
	dryNaturalGas: number;
	NGL: number;
	total: number;
}

export interface CompressorOperatingDataTable {
	[key: string|number]: CompressorOperatingData,
}

export interface AdditionalRevenueTable {
	[key: string|number]: AdditionalRevenue;
}

export interface CalculationResult {
	[key: string|number]: Result;
}

export interface Result {
	percentage: number;
	additionalRevenuePerDay: number;
	additionalRevenuePerYear: number;
	paybackTime: number
}

export interface GridRowParams {
	label: string;
	value: string;
	tooltip: string | null;
}
