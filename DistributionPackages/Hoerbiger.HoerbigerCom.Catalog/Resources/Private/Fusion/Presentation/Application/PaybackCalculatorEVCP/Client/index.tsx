import * as React from "react";
import {
	AdditionalRevenue,
	AdditionalRevenueTable,
	ApplicationData,
	Calculation,
	CalculationResult,
	CompressorOperatingData,
	CompressorOperatingDataTable,
	InputFields,
	Result,
	ValidationErrors
} from "./Defintions";
import {InputRow} from "./Components/InputRow";
import {Diagram} from "./Components/Diagram";
import {GridRow} from "./Components/GridRow";
import {ArrowRight} from "./Components/Icons";

export const PaybackCalculatorEVCP = (props: ApplicationData): JSX.Element => {
	const startPercentage = 5;
	const duration = 6;
	const [hasDiagram, setHasDiagram] = React.useState<boolean>(false);
	const [inputs, setInputs] = React.useState<InputFields>(props.inputFields);

	const [showFirstContainer, setShowFirstContainer] = React.useState<boolean>(false);
	const [showSecondContainer, setShowSecondContainer] = React.useState<boolean>(false);

	const [calculations, setCalculations] = React.useState<Calculation>({
		gasCompositionOfRawNaturalGasTotal: 0,
		compressorOperatingData: {
			percentage: 1,
			standardCubicFeet: 0,
			heatingValue: 0
		},
		compressorOperatingDataTable: {
			five: {percentage: 0, heatingValue: 0, standardCubicFeet: 0},
		},
		additionalRevenue: {
			percentage: 0,
			dryNaturalGas: 0,
			NGL: 0,
			total: 0,
		},
		additionalRevenueTable: {
			five: {percentage: 0, dryNaturalGas: 0, NGL: 0, total: 0},
		},
		projectCostSubtotalEVCP: 0,
		projectCostTotal: 0,
		calculationResult: {
			0: {
				percentage: 0,
				additionalRevenuePerDay: 0,
				additionalRevenuePerYear: 0,
				paybackTime: 0
			}
		},
		paybackTimePerDay: 0,
		paybackTimePerMonth: 0
	});

	const [hasErrors, setHasErrors] = React.useState<ValidationErrors>({
		cost_dryNaturalGas: null,
		cost_NGL: null,
		compressor_annualOperationHours: null,
		compressor_cylindersNumber: null,
		compressor_nominalFlowRate: null,
		compressor_outputIncrease:  null,
	});

	const validateFormElement = (element: string, value: number): boolean => {
		let message: string | null = null;
		switch (element) {
		case "cost_dryNaturalGas":
		case "cost_NGL":
			if (value > 0 && value <= 99) {
				message = null;
			} else {
				message = props.getLabelFor("error.message.cost");
			}
			break;
		case "compressor_annualOperationHours":
			if (value >= 1 && value <= 8760) {
				message = null;
			} else {
				message = props.getLabelFor("error.message.annualOperationHours");
			}
			break;
		case "compressor_cylindersNumber":
			if (value >= 1 && value <= 10) {
				message = null;
			} else {
				message = props.getLabelFor("error.message.cylindersNumber");
			}
			break;
		case "compressor_nominalFlowRate":
			if (value > 0 && value <= 99) {
				message = null;
			} else {
				message = props.getLabelFor("error.message.nominalFlowRate");
			}
			break;
		case "compressor_outputIncrease":
			if (value >= 5 && value <= 10) {
				message = null;
			} else {
				message = props.getLabelFor("error.message.outputIncrease");
			}

			break;
		}

		setHasErrors(hasErrors => ({...hasErrors, [element]: message}));
		return (message === null);
	};

	const handleInputChange = (value: string, name: string) => {
		const isValid = validateFormElement(name, Number(value));

		if (isValid) {
			setInputs(values => ({...values, [name]: Number(value)}));
		}
	};

	React.useEffect(() => {
		setHasDiagram(false);

		const gasCompositionOfRawNaturalGasTotal = props.calculationFields.composition_dryNaturalGas + props.calculationFields.composition_NGL + props.calculationFields.composition_others;

		const standardCubicFeet = inputs.compressor_nominalFlowRate * inputs.compressor_outputIncrease / 100;
		const heatingValue = standardCubicFeet * props.calculationFields.unit_avgHeatingValue4NG;

		const dryNaturalGas = heatingValue * props.calculationFields.composition_dryNaturalGas / 100 * inputs.cost_dryNaturalGas;
		const NGL = heatingValue * props.calculationFields.composition_others / 100 * inputs.cost_NGL;
		const total = dryNaturalGas + NGL;

		const projectCostSubtotalEVCP = props.calculationFields.cost_actuators + props.calculationFields.cost_localPanel + props.calculationFields.cost_installation;
		const projectCostTotal = props.calculationFields.cost_generalSubtotal + props.calculationFields.cost_scheduleAndControl + props.calculationFields.cost_customerIntegration + props.calculationFields.cost_commission;

		const compressorOperatingDataTable: CompressorOperatingDataTable = {};

		for (let i = 0; i < duration; i++) {
			const tmp = props.inputFields.compressor_nominalFlowRate * (startPercentage + i) / 100;
			compressorOperatingDataTable[startPercentage + i] = {
				percentage: startPercentage + i,
				standardCubicFeet: tmp,
				heatingValue: tmp * props.calculationFields.unit_avgHeatingValue4NG
			} as CompressorOperatingData;
		}

		const additionalRevenueTable: AdditionalRevenueTable = {};

		for (let i = 0; i < duration; i++) {
			const dryNaturalGas = compressorOperatingDataTable[startPercentage + i].heatingValue * props.calculationFields.composition_dryNaturalGas / 100 * inputs.cost_dryNaturalGas;
			const NGL = compressorOperatingDataTable[startPercentage + i].heatingValue * props.calculationFields.composition_NGL / 100 * inputs.cost_NGL;
			additionalRevenueTable[startPercentage + i] = {
				percentage: startPercentage + i,
				dryNaturalGas,
				NGL,
				total: dryNaturalGas + NGL
			} as AdditionalRevenue;
		}

		const calculationResult: CalculationResult = {};

		for (let i = 0; i < duration; i++) {
			const day = additionalRevenueTable[startPercentage + i].total;
			const year = additionalRevenueTable[startPercentage + i].total * (inputs.compressor_annualOperationHours / 24);

			calculationResult[startPercentage + i] = {
				percentage: startPercentage + i,
				additionalRevenuePerDay: day,
				additionalRevenuePerYear: year,
				paybackTime: projectCostTotal / year * 12
			} as Result;
		}

		const paybackTimePerDay = projectCostTotal / total;
		const paybackTimePerMonth = paybackTimePerDay / 365 * 12;

		setCalculations({
			gasCompositionOfRawNaturalGasTotal,
			compressorOperatingData: {
				percentage: 1,
				standardCubicFeet,
				heatingValue
			},
			compressorOperatingDataTable,
			additionalRevenue: {
				percentage: 1,
				dryNaturalGas,
				NGL,
				total,
			},
			additionalRevenueTable,
			projectCostSubtotalEVCP,
			projectCostTotal,
			calculationResult,
			paybackTimePerDay,
			paybackTimePerMonth
		});
	},[inputs]);

	const toggleFirstContainer = () => {
		setShowFirstContainer(!showFirstContainer);
	};

	const toggleSecondContainer = () => {
		setShowSecondContainer(!showSecondContainer);
	};

	React.useEffect(() => {
		setHasDiagram(true);
	}, [calculations]);

	return (
		<div>
			<div className="grid grid-cols-1 md:grid-cols-2 md:gap-x-sm">

				{/* input Felder Linke Spalte */}
				<div className="flex flex-col justify-between">
					<div>
						<div className="text-excite text-2xl font-sans-regular">
							{props.getLabelFor("label.headline.compressorOperatingData")}
						</div>
						<div>
							<div>
								<InputRow
									label={props.getLabelFor("label.compressor.annualOperatingHours")}
									unit={props.getLabelFor("label.unit.compressor.annualOperatingHours")}
									tooltip={null}
									inputName="compressor_annualOperationHours"
									inputValue={inputs.compressor_annualOperationHours}
									step="1"
									min={1}
									max={8760}
									errorMessage={hasErrors.compressor_annualOperationHours}
									getLabelFor={props.getLabelFor}
									onChange={handleInputChange}
								/>
							</div>
							<div>
								<InputRow
									label={props.getLabelFor("label.compressor.cylindersNumber")}
									unit={props.getLabelFor("label.unit.compressor.cylindersNumber")}
									tooltip={null}
									inputName="compressor_cylindersNumber"
									inputValue={inputs.compressor_cylindersNumber}
									step="1"
									min={1}
									max={10}
									errorMessage={hasErrors.compressor_cylindersNumber}
									getLabelFor={props.getLabelFor}
									onChange={handleInputChange}
								/>
							</div>
							<div>
								<InputRow
									label={props.getLabelFor("label.compressor.nominalFlowRate")}
									unit={props.getLabelFor("label.unit.compressor.nominalFlowRate")}
									tooltip={{
										abbreviation: props.getLabelFor("abbreviation.mmscfd"),
										description: props.getLabelFor("description.mmscfd"),
										notice: null,
										link: null,
									}}
									inputName="compressor_nominalFlowRate"
									inputValue={inputs.compressor_nominalFlowRate}
									step="0.01"
									min={0.01}
									max={99.00}
									errorMessage={hasErrors.compressor_nominalFlowRate}
									getLabelFor={props.getLabelFor}
									onChange={handleInputChange}
								/>
							</div>
							<div>
								<InputRow
									label={props.getLabelFor("label.compressor.outputIncrease")}
									unit=""
									tooltip={null}
									inputName="compressor_outputIncrease"
									inputValue={inputs.compressor_outputIncrease}
									step="0.1"
									min={5.0}
									max={10.0}
									errorMessage={hasErrors.compressor_outputIncrease}
									getLabelFor={props.getLabelFor}
									onChange={handleInputChange}
								/>
							</div>
						</div>
					</div>

					{/*<div className="text-[red] h-touch border-b-[1px] border-[red] flex items-center">*/}
					{/*	payback time: {calculations.paybackTimePerMonth}*/}
					{/*</div>*/}
				</div>

				{/* input Felder rechte Spalte */}
				<div className="flex flex-col justify-between ">
					<div>
						<div className="text-excite text-2xl font-sans-regular">
							{props.getLabelFor("label.headline.naturalGasGathering")}
						</div>
						<div>
							<InputRow
								label={props.getLabelFor("label.prices.dryNaturalGas")}
								unit={props.getLabelFor("label.unit.prices.dryNaturalGas")}
								tooltip={{
									abbreviation: props.getLabelFor("abbreviation.mmbtu"),
									description: props.getLabelFor("description.mmbtu"),
									notice: props.getLabelFor("notice.dryNaturalGas"),
									link: "https://www.eia.gov/naturalgas/weekly/#tabs-prices-2",
								}}
								inputName="cost_dryNaturalGas"
								inputValue={inputs.cost_dryNaturalGas}
								step="0.01"
								min={0.01}
								max={99.0}
								errorMessage={hasErrors.cost_dryNaturalGas}
								getLabelFor={props.getLabelFor}
								onChange={handleInputChange}
							/>

							<InputRow
								label={props.getLabelFor("label.prices.NGL")}
								unit={props.getLabelFor("label.prices.NGL")}
								tooltip={{
									abbreviation: props.getLabelFor("abbreviation.mmbtu"),
									description: props.getLabelFor("description.mmbtu"),
									notice: props.getLabelFor("notice.NGL"),
									link: "https://www.eia.gov/dnav/ng/hist/ngm_epg0_plc_nus_dmmbtum.htm",
								}}
								inputName="cost_NGL"
								inputValue={inputs.cost_NGL}
								step="0.01"
								min={0.01}
								max={99.0}
								errorMessage={hasErrors.cost_NGL}
								getLabelFor={props.getLabelFor}
								onChange={handleInputChange}
							/>
						</div>
					</div>
					{/*<div className="text-[red] h-touch border-b-[1px] border-[red] flex items-center">*/}
					{/*	Additional revenues per day {calculations.additionalRevenue.total}*/}
					{/*</div>*/}
				</div>
			</div>


			<div className="grid grid-cols-1 md:grid-cols-2 md:gap-x-sm my-sm">
				{/* Herleitung linke Spalte */}
				<div className="flex flex-col justify-between">
					{
						showFirstContainer &&
							<div className="bg-light p-sm h-full">
								<div className="text-excite text-2xl font-sans-regular">
									{props.getLabelFor("label.headline.conversionFactors")}
								</div>
								<div className="grid grid-cols-2 gap-y-[10px]">
									<GridRow
										label={props.getLabelFor("label.conversionFactor.btu")}
										value={props.calculationFields.unit_btu + " " + props.getLabelFor("label.unit.conversionFactor.btu")}
										tooltip={{
											abbreviation: props.getLabelFor("abbreviation.btu"),
											description: props.getLabelFor("description.btu"),
											notice: null,
											link: null,
										}}
									/>

									<GridRow
										label={props.getLabelFor("label.conversionFactor.mmBtu")}
										value={props.calculationFields.unit_mmbtu + " " + props.getLabelFor("label.unit.conversionFactor.mmBtu")}
										tooltip={{
											abbreviation: props.getLabelFor("abbreviation.mmbtu"),
											description: props.getLabelFor("description.mmbtu"),
											notice: null,
											link: null,
										}}
									/>

									<GridRow
										label={props.getLabelFor("label.conversionFactor.scf")}
										value={props.calculationFields.unit_scf + " " + props.getLabelFor("label.unit.conversionFactor.scf")}
										tooltip={null}
									/>

									<div className="col-span-2 font-sans-bold">
										{props.getLabelFor("label.headline.gasComposition")}
									</div>

									<GridRow
										label={props.getLabelFor("label.conversionFactor.avgHeatingValue4NG")}
										value={props.calculationFields.unit_avgHeatingValue4NG + " " + props.getLabelFor("label.unit.conversionFactor.avgHeatingValue4NG")}
										tooltip={{
											abbreviation: props.getLabelFor("abbreviation.btu"),
											description: props.getLabelFor("description.btu"),
											notice: null,
											link: null,
										}}
									/>
								</div>

								<div className="text-excite text-2xl font-sans-regular">
									{props.getLabelFor("label.headline.gasCompositionRaw")}
								</div>
								<div className="grid grid-cols-2">
									<GridRow
										label={props.getLabelFor("label.addRevenues.dryNaturalGas")}
										value={props.calculationFields.composition_dryNaturalGas + " " + props.getLabelFor("label.unit.addRevenues.dryNaturalGas")}
										tooltip={null}
									/>
									<GridRow
										label={props.getLabelFor("label.addRevenues.NGL")}
										value={props.calculationFields.composition_NGL + " " + props.getLabelFor("label.unit.addRevenues.NGL")}
										tooltip={null}
									/>
									<GridRow
										label={props.getLabelFor("label.addRevenues.others")}
										value={props.calculationFields.composition_others + " " + props.getLabelFor("label.unit.addRevenues.others")}
										tooltip={null}
									/>

									<div className="col-span-2 font-sans-bold">
										{props.getLabelFor("label.headline.gasComposition")}
									</div>

									<GridRow
										label={props.getLabelFor("label.addRevenues.total")}
										value={calculations.gasCompositionOfRawNaturalGasTotal + " " + props.getLabelFor("label.unit.addRevenues.total")}
										tooltip={null}
									/>
								</div>
							</div>
					}

					<div
						className="font-sans-regular text-lg py-xs cursor-pointer"
						onClick={toggleFirstContainer}
					>
						{
							showFirstContainer
								? (
									<span className="flex items-center gap-ch">
										<ArrowRight />
										{props.getLabelFor("label.disableDerivation")}
									</span>
								)
								: (
									<span className="flex items-center gap-ch">
										<ArrowRight />
										{props.getLabelFor("label.showDerivation")}
									</span>
								)
						}
					</div>
				</div>

				{/* Herleitung rechte Spalte */}
				<div className="flex flex-col justify-between">

					{
						showSecondContainer &&
							<div className="bg-light p-sm h-full grid grid-cols-2 gap-y-[10px] gap-ch">
								<GridRow
									label={props.getLabelFor("label.projectCost.numberOfCompressors")}
									value={inputs.compressor_cylindersNumber + ""}
									tooltip={null}
								/>
								<GridRow
									label={props.getLabelFor("label.projectCost.actuators")}
									value={props.calculationFields.cost_actuators + " " + props.getLabelFor("label.unit.dollar")}
									tooltip={null}
								/>
								<GridRow
									label={props.getLabelFor("label.projectCost.localPanel")}
									value={props.calculationFields.cost_localPanel + " " + props.getLabelFor("label.unit.dollar")}
									tooltip={null}
								/>
								<GridRow
									label={props.getLabelFor("label.projectCost.installation")}
									value={props.calculationFields.cost_installation + " " + props.getLabelFor("label.unit.dollar")}
									tooltip={null}
								/>

								<GridRow
									label={props.getLabelFor("label.projectCost.subTotal")}
									value={calculations.projectCostSubtotalEVCP + " " + props.getLabelFor("label.unit.dollar")}
									tooltip={null}
								/>

								<GridRow
									label={props.getLabelFor("label.projectCost.generalSubtotal")}
									value={props.calculationFields.cost_generalSubtotal + " " + props.getLabelFor("label.unit.dollar")}
									tooltip={null}
								/>
								<GridRow
									label={props.getLabelFor("label.projectCost.scheduleAndControl")}
									value={props.calculationFields.cost_scheduleAndControl + " " + props.getLabelFor("label.unit.dollar")}
									tooltip={null}
								/>
								<GridRow
									label={props.getLabelFor("label.projectCost.customerIntegration")}
									value={props.calculationFields.cost_customerIntegration + " " + props.getLabelFor("label.unit.dollar")}
									tooltip={null}
								/>

								<GridRow
									label={props.getLabelFor("label.projectCost.commission")}
									value={props.calculationFields.cost_commission + " " + props.getLabelFor("label.unit.dollar")}
									tooltip={null}
								/>

								<GridRow
									label={props.getLabelFor("label.projectCost.total")}
									value={calculations.projectCostTotal + " " + props.getLabelFor("label.unit.dollar")}
									tooltip={null}
								/>
							</div>
					}

					<div
						className="font-sans-regular text-lg py-xs cursor-pointer"
						onClick={toggleSecondContainer}
					>
						{
							showSecondContainer
								? (
									<span className="flex items-center gap-ch">
										<ArrowRight />
										{props.getLabelFor("label.disableDerivation")}
									</span>
								)
								: (
									<span className="flex items-center gap-ch">
										<ArrowRight />
										{props.getLabelFor("label.showDerivation")}
									</span>
								)
						}
					</div>
				</div>
			</div>

			{/* Diagram */}
			<div className="w-full">
				{
					(calculations.calculationResult && hasDiagram) &&
						<Diagram
							applicationRoot={props.applicationRoot}
							chartLabels={{
								chartTitle: props.getLabelFor("label.chart.title"),
								barChartLabel: props.getLabelFor("label.chart.barChartLabel"),
								lineChartLabel: props.getLabelFor("label.chart.lineChartLabel"),
								xAxisLabel: props.getLabelFor("label.chart.xAxisLabel"),
								yLeftLabel: props.getLabelFor("label.chart.yLeftLabel"),
								yRightLabel: props.getLabelFor("label.chart.yRightLabel"),
							}}
							xAxisData={Object.values(calculations.calculationResult).map((option) => (
								option.percentage + "%"
							))}
							barChartData={Object.values(calculations.calculationResult).map((option) => (
								option.additionalRevenuePerYear
							))}
							lineChartData={Object.values(calculations.calculationResult).map((option) => (
								option.paybackTime
							))}
							getLabelFor={props.getLabelFor}
						/>
				}
			</div>
			<div className="w-full font-sans-light py-xs text-sm">
				{props.getLabelFor("label.footnote")}
			</div>
			<div className="w-full font-sans-light text-sm">
				{props.getLabelFor("label.disclaimer")}
			</div>

			{/*=================================== Kommt raus =================================================*/}
			<br/>
			<br/>
			<hr/>
			<br/>
			<br/>



			Hello World ... PaybackCalculatorEVCP React Application {props.getLabelFor("cost_dryNaturalGas")}

			<div>
				Part 1:
				<div>
					Price dry natural gas:	{props.inputFields.cost_dryNaturalGas}	$ per MMBTU

				</div>
				<div>
					Price NGL composite:	{props.inputFields.cost_NGL}	$ per MMBTU

				</div>
			</div>

			<br/>
			<br/>
			<hr/>
			<br/>
			<br/>

			<div>
				Part 2:
				<div>Compressor operating data:</div>
				<div>

				</div>
				<div>

				</div>
				<div>

				</div>
				<div>

				</div>
				<div>-----</div>
				<div>
					in standard cubic feet	0,49	MMSCFD
					<div className="text-[green]">
						{calculations.compressorOperatingData.standardCubicFeet}
					</div>
				</div>
				<div>
					in heating value	505	MMBTU per day
					<div className="text-[green]">
						{calculations.compressorOperatingData.heatingValue}
					</div>
				</div>

				<br/>
				<hr/>
				<br/>
				<br/>

				<div>
					Part 3:
					Additional revenues per day
					<div>
						Dry natural gas:	1175	$ per day
						<div className="text-[green]">
							{calculations.additionalRevenue.dryNaturalGas}
						</div>
					</div>
					<div>
						NGL:	307	$ per day
						<div className="text-[green]">
							{calculations.additionalRevenue.NGL}
						</div>
					</div>
					<div>
						Total	1482	$ per day
						<div className="text-[green]">
							{calculations.additionalRevenue.total}
						</div>
					</div>

				</div>

				<br/>
				<hr/>
				<br/>
				<br/>

				<div>
					Part 4:
					Project costs for end-user for one compressor:
					<div>
						Total number of eVCPs per compressor	<div className="text-[green]">{inputs.compressor_cylindersNumber}</div>
					</div>

					<div>
						eVCP actuators per piece	<div className="text-[green]">{props.calculationFields.cost_actuators}</div>
					</div>
					<div>
						Local panel per eVCP	<div className="text-[green]">{props.calculationFields.cost_localPanel}</div>
					</div>
					<div>
						Cabling, labour mechanical and electrical  installation	<div className="text-[green]">{props.calculationFields.cost_installation}</div>
					</div>

					<div>
						Subtotal per eVCP	141000
						<div className="text-[green]">
							{calculations.projectCostSubtotalEVCP}
						</div>
					</div>

					<div>
						Subtotal for total number of eVCPs	<div className="text-[green]">{props.calculationFields.cost_generalSubtotal}</div>
					</div>

					<div>
						Technical clarification meeting , development of control scheme and compressor loading shedule	<div className="text-[green]">{props.calculationFields.cost_scheduleAndControl}</div>
					</div>
					<div>
						Integration into customer control system	<div className="text-[green]">{props.calculationFields.cost_customerIntegration}</div>
					</div>
					<div>
						Commissioning / start-up services	<div className="text-[green]">{props.calculationFields.cost_commission}</div>
					</div>

					<div>
						Total	324000
						<div className="text-[green]">
							{calculations.projectCostTotal}
						</div>
					</div>
				</div>


				<br/>
				<hr/>
				<br/>
				<br/>

				<div>
					Part 5:
					{
						calculations.compressorOperatingDataTable &&
						Object.entries(calculations.compressorOperatingDataTable).map(([key, value]) => (
							<div
								key={key}
							>
								<span>{value.percentage} Prozente   </span>
								<span >standard cubic fee: <span className="text-[green]">{value.standardCubicFeet}   </span></span>
								<span>heating value: <span className="text-[green]">{value.heatingValue}</span></span>
							</div>
						))
					}
				</div>


				<br/>
				<hr/>
				<br/>
				<br/>

				<div>
					Part 6:
					{
						calculations.additionalRevenueTable &&
						Object.entries(calculations.additionalRevenueTable).map(([key, value]) => (
							<div
								key={key}
							>
								<span>{value.percentage} Prozente </span>
								<span>dry natural gas: <span className="text-[green]">{value.dryNaturalGas}   </span></span>
								<span>NGL: <span className="text-[green]">{value.NGL}</span></span>
								<span>total: <span className="text-[green]">{value.total}</span></span>
							</div>
						))
					}
				</div>

				<br/>
				<hr/>
				<br/>
				<br/>

				<div>
					Part 7:
					{
						calculations.calculationResult &&
							<div>
								{
									Object.entries(calculations.calculationResult).map(([key, value]) => (
										<div
											key={key}
										>
											<span>{value.percentage} Prozente </span>
											<span>Additional revenue per day [USD]: <span className="text-[green]">{value.additionalRevenuePerDay}   </span></span>
											<span>Additional revenue per year [USD]: <span className="text-[green]">{value.additionalRevenuePerYear}</span></span>
											<span>Payback time [months]: <span className="text-[green]">{value.paybackTime}</span></span>
										</div>
									))
								}
							</div>
					}
				</div>
			</div>
		</div>
	);
};
