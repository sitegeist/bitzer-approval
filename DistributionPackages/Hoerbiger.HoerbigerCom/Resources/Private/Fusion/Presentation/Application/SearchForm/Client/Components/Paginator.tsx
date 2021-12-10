import * as React from "react";
import cx from "classnames";

import {LabelGetter, SearchRequestParams} from "../Definitions";
import {StampedeIcon} from "@hoerbiger/shared/Resources/Private/Fusion/Presentation/Application/Components";

type PaginatorProps = {
	currentPage: number;
	numberOfPages: number;
	onChange: (update: Partial<SearchRequestParams>) => void;
	getLabelFor: LabelGetter;
};
export const Paginator = (props: PaginatorProps): JSX.Element => {
	const pagesToRender = Array.from(new Set([
		1,
		props.currentPage - 1,
		props.currentPage,
		props.currentPage + 1,
		props.numberOfPages
	]))
		.filter(page => page >= 1 &&  page <= props.numberOfPages)
		.sort((a, b) => a - b);

	return (
		<div className="flex justify-center items-center space-x-md uppercase font-sans-bold text-lg">
			<div
				onClick={() => {
					if (props.currentPage - 1 >= 1) {
						props.onChange({page: props.currentPage - 1});
					}
				}}
				className="flex items-center"
			>
				<PaginatorItem
					key="PREV"
					label={<StampedeIcon iconName="chevronLeft"/>}
					isDisabled={props.currentPage - 1 < 1}
				/>
				<span className={cx(
					"hidden md:flex ml-ch",
					props.currentPage - 1 < 1 ? "text-neutral" : "cursor-pointer"
				)}>
					{props.getLabelFor("label.paginator.prev")}
				</span>
			</div>
			<PaginatorItem
				label={props.currentPage}
				isActive={true}
				className="sm:hidden"
			/>
			<div className="hidden sm:flex justify-center items-center space-x-xs">
				{
					pagesToRender.map((page: number, index: number, list: number[]) => (
						<React.Fragment key={page}>
							{
								index > 0 && list[index - 1] + 1 < page
									? <div>...</div>
									: null
							}
							<PaginatorItem
								label={page}
								onClick={() => props.onChange({page})}
								isActive={page === props.currentPage}
							/>
						</React.Fragment>
					))
				}
			</div>
			<div
				onClick={() => {
					if (props.currentPage + 1 <= props.numberOfPages) {
						props.onChange({page: props.currentPage + 1});
					}
				}}
				className="flex items-center"
			>
				<span className={cx(
					"hidden md:flex mr-ch",
					props.currentPage + 1 > props.numberOfPages ? "text-neutral" : "cursor-pointer"
				)}>
					{props.getLabelFor("label.paginator.next")}
				</span>
				<PaginatorItem
					key="NEXT"
					label={<StampedeIcon iconName="chevronRight"/>}
					isDisabled={props.currentPage + 1 > props.numberOfPages}
				/>
			</div>
		</div>
	);
};

type PaginatorItemProps = {
	label: React.ReactNode;
	onClick?: () => void;
	isActive?: boolean;
	isDisabled?: boolean;
	className?: string;
}
const PaginatorItem = (props: PaginatorItemProps): JSX.Element => (
	<div
		onClick={props.isDisabled ? undefined : props.onClick}
		className={cx(
			"min-h-touch min-w-touch rounded-full border",
			"flex items-center justify-center text-center border-2",
			props.className,
			props.isDisabled
				? "border-neutral text-neutral bg-light"
				: props.isActive
					? "border-brand text-light bg-brand"
					: "border-light-contrast text-light-contrast bg-light cursor-pointer hover:drop-shadow-brand",
		)}
	>{props.label}</div>
);
