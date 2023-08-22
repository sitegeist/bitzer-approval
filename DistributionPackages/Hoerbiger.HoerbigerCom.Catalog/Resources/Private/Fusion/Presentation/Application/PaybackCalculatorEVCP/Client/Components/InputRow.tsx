import * as React from "react";
import cx from "classnames";

import {InputRowParams} from "../Defintions";
import {InfoIcon} from "./Icons";
import {Modal} from "./Modal";

export const InputRow = (props: InputRowParams): JSX.Element => {
	const onChange = (e: React.ChangeEvent<HTMLInputElement>) => {
		const value = e.currentTarget.value;
		props.onChange(value, props.inputName);
	};

	const [modalIsOpen, setModalIsOpen] = React.useState<boolean>(false);
	const [labelModalIsOpen, setLabelModalIsOpen] = React.useState<boolean>(false);

	const handleTooltip = () => {
		setModalIsOpen(true);
	};

	const handleLabelTooltip = () => {
		setLabelModalIsOpen(true);
	};

	const handleFocus = (e: React.MouseEvent<HTMLInputElement>) => {
		e.currentTarget.select();
	};

	return (
		<div className={cx(
			"grid grid-cols-2 items-center min-h-touch gap-ch",
			"border-b-[1px] border-light-contrast py-ch"
		)}>
			<div className={cx(
				"font-bold flex items-center gap-ch",
				props.errorMessage && "self-start"
			)}>
				{props.label}

				{
					props.labelTooltip &&
						<div onClick={handleLabelTooltip} className="cursor-pointer">
							<InfoIcon />
						</div>
				}
			</div>
			<div className={cx(
				"flex flex-col",
				props.errorMessage && "gap-[5px] py-[5px]"
			)}>
				<div className="flex items-center gap-ch">
					<div className="">
						<input
							name={props.inputName}
							type="number"
							value={props.inputValue ?? 0}
							className="bg-light focus:outline-brand w-[72px] text-right border border-[#C0C0C0] pr-ch lg:pr-0"
							step={props.step}
							min={props.min}
							max={props.max}
							onChange={onChange}
							onClick={handleFocus}
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
			{
				(modalIsOpen && props.tooltip) &&
					<Modal
						info={props.tooltip}
						onClick={() => setModalIsOpen(false)}
					/>
			}
			{
				(labelModalIsOpen && props.labelTooltip) &&
					<Modal
						info={props.labelTooltip}
						onClick={() => setLabelModalIsOpen(false)}
					/>
			}
		</div>
	);
};
