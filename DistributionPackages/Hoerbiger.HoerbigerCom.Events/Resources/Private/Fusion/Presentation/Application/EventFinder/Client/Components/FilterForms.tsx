import * as React from "react";
import {
	ActiveFilters,
	DynamicFilters,
	FilterFormProps,
} from "../Definitions";

import { SelectBox } from "./SelectBox";
import cx from "classnames";
import {ArrowCounterClockWise} from "./Icons";

export const FilterForms = (props: FilterFormProps): JSX.Element => {
	const [inputs, setInputs] = React.useState<DynamicFilters>({selectedFilters: new Map});

	const handleDynamicFilterChange = (value: string, name: string) => {
		if (value) {
			inputs.selectedFilters.set(name, value);
		} else {
			inputs.selectedFilters.delete(name);
		}

		setInputs(values => ({...values, selectedFilters: inputs.selectedFilters}));
	};

	const updateRequestParameters = (event: React.FormEvent | null) => {
		if (event) {
			event.preventDefault();
		}

		const activeFilters:ActiveFilters[] = [];

		inputs.selectedFilters.forEach((value, key) => {
			const filter = {
				name: key,
				value: value
			};
			activeFilters.push(filter);
		});

		props.onSubmit({
			activeFilters: activeFilters
		});
	};

	const resetFilters = () => {
		setInputs({
			selectedFilters: new Map,
		});
	};

	const getLabelForValue = (name: string, value: string, count: number) => {
		if (name === "type") {
			return props.getLabelFor(
				"label.filters.type." + value
			);
		}

		if (name === "countryCode") {
			return props.getLabelFor(
				"label.filters.countryCode." + value
			);
		}

		return value;
	};

	const getSelectedOptionForFilter = (identifier: string) => {
		return inputs.selectedFilters.get(identifier) || "";
	};

	return (
		<>
			<form
				onSubmit={updateRequestParameters}
				className="py-sm space-y-sm relative"
			>
				<div className="flex flex-col md:flex-row space-y-sm md:space-y-0 md:space-x-xs w-full">
					<div className={cx(
						"flex items-center justify-between",
						"text-xl sm:text-2xl pr-xs"
					)}>
						{props.getLabelFor("label.filter.headline")}
					</div>
					<div className={cx(
						"flex flex-col md:flex-row justify-between w-full",
						"space-y-sm md:space-y-0 md:space-x-xs lg:space-x-sm"
					)}>
						{
							props.availableFilter?.map((filter) => (
								<SelectBox
									key={filter.name}
									name={filter.name}
									emptyValue={props.getLabelFor("label.availableOptions." + filter.name)}
									initialValue={getSelectedOptionForFilter(filter.name)}
									options={filter.options}
									getLabelForValue={getLabelForValue}
									onChange={handleDynamicFilterChange}
								/>
							))
						}
					</div>
					<div>
						<button className={cx(
							"inline-flex items-center min-w-touch min-h-touch text-lg",
							"pr-[20px] pl-[24px] py-2 min-h-[24px] w-full bg-neutral-lighter"
						)}>
							<span className="w-full">
								<div className="md:flex md:items-center md:justify-between">
									<span className="pt-[4px]">
										{props.getLabelFor("label.submit")}
									</span>
								</div>
							</span>
						</button>
					</div>
					<div className="flex justify-between">
						<button className={cx(
							"inline-flex items-center min-w-touch min-h-touch text-lg opacity-50",
							"px-[15px] py-2 min-h-[24px] w-full cursor-pointer bg-neutral-lighter"
						)}>
							<span
								className="w-full"
								onClick={resetFilters}
							>
								<div className="md:flex md:items-center md:justify-between">
									<span className="pt-[4px] hidden md:flex">
										<ArrowCounterClockWise isDisabled={false}/>
									</span>
									<span className="pt-[4px] md:hidden">
										{props.getLabelFor("label.select.reset")}
									</span>
								</div>
							</span>
						</button>
					</div>
				</div>
			</form>
		</>
	);
};
