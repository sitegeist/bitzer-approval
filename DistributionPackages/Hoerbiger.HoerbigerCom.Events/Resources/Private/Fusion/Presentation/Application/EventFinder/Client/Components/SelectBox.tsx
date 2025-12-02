import * as React from "react";
import cx from "classnames";
import {FilterOption} from "../Definitions";

interface SelectBoxProps {
	name: string,
	emptyValue?: string,
	initialValue?: string,
	options?: FilterOption[] | [],
	getLabelForValue: (name: string, value: string, count: number) => string,
	onChange?: (value: string, category: string) => void
}

export const SelectBox = ({ name, emptyValue, initialValue, options, getLabelForValue, onChange }: SelectBoxProps): JSX.Element => {
	const handleChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
		const { value } = e.target;
		onChange && onChange(value || "", name);
	};

	function compareOptions(elem1: FilterOption, elem2: FilterOption ) {
		const optionElem1 = getLabelForValue(name, elem1.value, elem1.totalNumberOfResults);
		const optionElem2 = getLabelForValue(name, elem2.value, elem2.totalNumberOfResults);

		if (optionElem1 < optionElem2) {
			return -1;
		}
		if (optionElem1 > optionElem2) {
			return 1;
		}

		return 0;
	}

	return (
		<div className="w-full flex justify-between relative">
			<select
				className={cx(
					"p-select appearance-none w-full border-b-2 border-neutral-darker bg-light",
					"focus:outline-hidden pr-[45px] font-sans-light pl-[16px] min-h-[48px] text-lg"
				)}
				name={name}
				value={initialValue}
				onChange={handleChange}
			>
				{
					(emptyValue)
						? <option value="">
							{emptyValue}
						</option>
						: null
				}
				{
					options?.sort(compareOptions).map((value, index) => (
						<option
							key={value.value}
							value={value.value}
							disabled={value.totalNumberOfResults === 0}
						>
							{getLabelForValue(name, value.value, value.totalNumberOfResults)}
						</option>
					))
				}
			</select>
			<svg
				className="absolute h-[24px] right-0 my-[10px] pr-[16px] pointer-events-none"
				viewBox="0 0 32 32"
				fill="none"
				xmlns="http://www.w3.org/2000/svg"
			>
				<path fillRule="evenodd" clipRule="evenodd" d="M3.29183 9.29183C3.38473 9.19871 3.49508 9.12482 3.61657 9.07441C3.73806 9.024 3.8683 8.99805 3.99983 8.99805C4.13137 8.99805 4.26161 9.024 4.3831 9.07441C4.50459 9.12482 4.61494 9.19871 4.70783 9.29183L15.9998 20.5858L27.2918 9.29183C27.3848 9.19886 27.4952 9.12511 27.6167 9.07479C27.7381 9.02447 27.8683 8.99857 27.9998 8.99857C28.1313 8.99857 28.2615 9.02447 28.383 9.07479C28.5045 9.12511 28.6149 9.19886 28.7078 9.29183C28.8008 9.38481 28.8746 9.49519 28.9249 9.61667C28.9752 9.73815 29.0011 9.86835 29.0011 9.99983C29.0011 10.1313 28.9752 10.2615 28.9249 10.383C28.8746 10.5045 28.8008 10.6149 28.7078 10.7078L16.7078 22.7078C16.6149 22.801 16.5046 22.8748 16.3831 22.9253C16.2616 22.9757 16.1314 23.0016 15.9998 23.0016C15.8683 23.0016 15.7381 22.9757 15.6166 22.9253C15.4951 22.8748 15.3847 22.801 15.2918 22.7078L3.29183 10.7078C3.19871 10.6149 3.12482 10.5046 3.07441 10.3831C3.024 10.2616 2.99805 10.1314 2.99805 9.99983C2.99805 9.8683 3.024 9.73806 3.07441 9.61657C3.12482 9.49508 3.19871 9.38473 3.29183 9.29183V9.29183Z" fill="currentColor"/>
			</svg>
		</div>
	);
};
