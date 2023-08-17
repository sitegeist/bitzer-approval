import * as React from "react";
import {GridRowParams} from "../Defintions";
import {InfoIcon} from "./Icons";
import {Modal} from "./Modal";

export const GridRow = (props: GridRowParams) => {
	const [modalIsOpen, setModalIsOpen] = React.useState<boolean>();

	const handleTooltip = () => {
		setModalIsOpen(true);
	};

	return (
		<>
			<div className="font-sans-bold">
				{props.label}
			</div>
			<div className="flex items-center gap-ch">
				<div>
					{props.value}
				</div>
				{
					props.tooltip &&
						<div onClick={handleTooltip} className="cursor-pointer">
							<InfoIcon />
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
		</>
	);
};
