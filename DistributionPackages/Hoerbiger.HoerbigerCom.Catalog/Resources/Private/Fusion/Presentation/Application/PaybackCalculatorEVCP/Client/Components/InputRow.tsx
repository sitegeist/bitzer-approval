import * as React from "react";
import cx from "classnames";

import {InputRowParams, LabelGetter} from "../Defintions";
import {InfoIcon} from "./Icons";
import {Modal} from "./Modal";

export const InputRow = (props: InputRowParams): JSX.Element => {
	const onChange = (e: React.ChangeEvent<HTMLInputElement>) => {
		const value = e.currentTarget.value;
		props.onChange(value, props.inputName);
	};

	const [modalIsOpen, setModalIsOpen] = React.useState<boolean>();

	const handleTooltip = () => {
		setModalIsOpen(true);
	};

	return (
		<div className={cx(
			"grid grid-cols-2 items-center min-h-touch gap-ch",
			"border-b-[1px] border-light-contrast"
		)}>
			<div className={cx(
				"font-bold",
				props.errorMessage && "text-[red]"
			)}>
				{props.label}
			</div>
			<div className={cx(
				"flex flex-col",
				props.errorMessage && "gap-[5px] pt-[5px] pb-[5px]"
			)}>
				<div className="flex items-center gap-ch">
					<div className="">
						<input
							name={props.inputName}
							type="number"
							value={props.inputValue ?? 0}
							className="bg-light focus:outline-brand w-[72px] text-right border border-[#C0C0C0]"
							step={props.step}
							min={props.min}
							max={props.max}
							onChange={onChange}
						/>
					</div>
					<span>
						{props.unit}
					</span>
					{
						props.tooltip &&
						<div onClick={handleTooltip} className="cursor-pointer">
							<InfoIcon />
						</div>
					}
				</div>
				{
					props.errorMessage &&
						<div className="text-[red]">
							{props.errorMessage}
						</div>
				}
			</div>
			{ modalIsOpen &&
				<Modal
					info={props.tooltip}
					onClick={() => setModalIsOpen(false)}
				/>
			}
		</div>
	);
};
