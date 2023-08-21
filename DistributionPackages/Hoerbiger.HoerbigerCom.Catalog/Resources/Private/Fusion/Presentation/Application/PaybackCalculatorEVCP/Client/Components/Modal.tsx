import {ArrowRight, CloseBox} from "./Icons";
import * as React from "react";
import cx from "classnames";

import {ModalParams} from "../Defintions";

export const Modal: React.FC<ModalParams> = (props) => {
	return (
		<div className="fixed w-screen h-screen bg-excite-transparent inset-0 z-50 grid place-items-center">
			<div className="w-[400px] bg-light p-sm flex flex-col gap-ch justify-between shadow relative">
				<div className="flex flex-col gap-ch">
					<h3 className="font-sans-regular text-xl">What does &quot;{props.info.abbreviation}&quot; mean</h3>
					<p className="capitalize">{props.info.description}</p>
				</div>
				{
					(props.info.notice && props.info.link) &&
						<div className="flex flex-col gap-y-ch my-ch">
							<p className="mb-ch">
								{props.info.notice}
							</p>

							<a href={props.info.link} target="blank">
								<span className="flex gap-x-ch items-center font-bold">
									<ArrowRight />
									more informations
								</span>
							</a>
						</div>
				}
				<div
					className="cursor-pointer font-bold"
					onClick={props.onClick}
				>
					<div className={cx(
						"flex items-center justify-center gap-ch absolute top-0 right-0 md:top-[10px] md:right-[10px] p-[10px] w-touch h-touch",
						"text-2xl text-excite"
					)}>
						<CloseBox />
					</div>
				</div>
			</div>
		</div>
	);
};
