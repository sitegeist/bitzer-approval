import {ArrowRight} from "./Icons";
import * as React from "react";
import {ModalParams} from "../Defintions";

export const Modal = (props: ModalParams) => {
	return (
		<div className="fixed w-screen h-screen bg-[#A8937911] inset-0 z-50 grid place-items-center">
			<div className="w-[300px] bg-light p-sm flex flex-col gap-ch justify-between">
				<h3 className="font-bold text-xl">What does {props.info.abbreviation} mean</h3>
				<p>{props.info.description}</p>
				{
					(props.info.notice && props.info.link) &&
						<div>
							{props.info.notice}
							<a href={props.info.link} target="blank">more informations</a>
						</div>
				}
				<div
					className="cursor-pointer font-bold"
					onClick={props.onClick}
				>
					<div className="flex items-center gap-ch">
						<ArrowRight />
						Fenster schließen
					</div>
				</div>
			</div>
		</div>
	);
};
