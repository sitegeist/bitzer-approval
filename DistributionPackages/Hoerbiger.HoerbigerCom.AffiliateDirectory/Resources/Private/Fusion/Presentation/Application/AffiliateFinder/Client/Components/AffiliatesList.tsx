import * as React from "react";
import cx from "classnames";

import {Affiliate} from "../Definitions";
import {AffiliateCard} from "./AffiliateCard";

type AffiliatesListProps = {
	affiliates: Affiliate[];
	hasPages: boolean;
	perPage: number;
};

export const AffiliatesList = ({affiliates, hasPages, perPage}: AffiliatesListProps): JSX.Element => {
	const [currentPage, setCurrentPage] = React.useState(0);

	React.useEffect(() => {
		setCurrentPage(0);
	}, [affiliates, setCurrentPage]);

	return (
		<div className={cx(
			"flex flex-col space-y-xs p-xs"
		)}>
			{
				hasPages
					? (
						<>
							{
								affiliates.slice(currentPage * perPage, currentPage * perPage + perPage).map(affiliate => (
									<AffiliateCard
										key={affiliate.identifier}
										{...affiliate}
									/>
								))
							}
							{
								<div className="flex justify-center space-x-ch">
									{
										Array.from({length: Math.ceil(affiliates.length / perPage)}, (v, i) => i)
											.map(page => (
												<span
													key={page}
													onClick={() => setCurrentPage(page)}
													className={cx(
														"w-2xs h-2xs cursor-pointer rounded-full",
														currentPage === page ? "bg-decorative" : "bg-neutral"
													)}
												></span>
											))
									}
								</div>
							}
						</>
					)
					: affiliates.map(affiliate => (
						<AffiliateCard
							key={affiliate.identifier}
							{...affiliate}
						/>
					))

			}
		</div>
	);
};

AffiliatesList.defaultProps = {
	perPage: 2,
};
