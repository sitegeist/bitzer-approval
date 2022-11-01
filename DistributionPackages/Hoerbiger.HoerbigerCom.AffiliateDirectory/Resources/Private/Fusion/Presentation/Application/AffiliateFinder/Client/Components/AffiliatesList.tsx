import * as React from "react";
import cx from "classnames";

import {AffiliateGroups} from "../Definitions";
import {AffiliateCard} from "./AffiliateCard";
import {StampedeIcon} from "@hoerbiger/shared/Resources/Private/Fusion/Presentation/Application/Components";

type AffiliatesListProps = {
	affiliateGroups: AffiliateGroups;
	hasPages: boolean;
	selectedGeoIdentifier: string|null;
	onSelectGeoIdentifier: (geoIdentifier: string) => void;
};

export const AffiliatesList = ({affiliateGroups, hasPages, selectedGeoIdentifier, onSelectGeoIdentifier}: AffiliatesListProps): JSX.Element => {
	const [currentPage, setCurrentPage] = React.useState(0);

	React.useEffect(() => {
		if (selectedGeoIdentifier) {
			setCurrentPage(Math.max(0, Object.keys(affiliateGroups).indexOf(selectedGeoIdentifier)));
		} else {
			setCurrentPage(0);
		}
	}, [affiliateGroups, selectedGeoIdentifier, setCurrentPage]);

	return (
		<div className={cx(
			"flex flex-col justify-between space-y-xs"
		)}>
			{
				Object.entries(affiliateGroups)
					.slice(
						hasPages ? currentPage : 0,
						hasPages ? currentPage + 1 : undefined
					)
					.map(([geoIdentifier, affiliates]) => (
						<AffiliateCard
							key={geoIdentifier}
							affiliates={affiliates}
						/>
					))
			}
			{
				hasPages && Object.keys(affiliateGroups).length > 1
					? (
						<div className="flex flex-wrap justify-center">
							<span
								className="w-touch h-touch flex items-center justify-center cursor-pointer text-neutral"
								onClick={() => onSelectGeoIdentifier(Object.keys(affiliateGroups)[currentPage - 1])}
							>
								<StampedeIcon
									iconName="chevronLeft"
									size={1.5}
								/>
							</span>

							{
								Array.from({length: Object.keys(affiliateGroups).length}, (v, i) => i)
									.map(page => (
										<span
											key={page}
											onClick={() => onSelectGeoIdentifier(Object.keys(affiliateGroups)[page])}
											className="w-touch h-touch flex items-center justify-center cursor-pointer"
										>
											<span className={cx(
												"w-[40%] h-[40%] rounded-full relative",
												currentPage === page ? "bg-brand" : "bg-neutral"
											)}>
												<span className={cx(
													"bg-light w-2 h-2 rounded-full absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2",
													currentPage === page ? "opacity-0" : "opacity-0",
												)}></span>
											</span>
										</span>
									))
							}

							<span
								className="w-touch h-touch flex items-center justify-center cursor-pointer text-decorative"
								onClick={() => onSelectGeoIdentifier(Object.keys(affiliateGroups)[currentPage + 1])}
							>
								<StampedeIcon
									iconName="chevronRight"
									size={1.5}
								/>
							</span>
						</div>
					)
					: null
			}
		</div>
	);
};
